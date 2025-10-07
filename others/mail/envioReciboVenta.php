<?php
session_start();
$u = $_SESSION['usuarios'];

//Establecemos el retorno del documento en formato json
header("Content-type: application/json; charset=utf-8");

$fechaActual = date('d-m-Y');

$letras = new NumberFormatter('es', NumberFormatter::SPELLOUT);

ob_start();

require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

//datos de la empresa
$sqlEmp = "select
e.emp_razonsocial,
e.emp_ruc,
s.suc_descri,
s.suc_telefono,
s.suc_email,
s.suc_direccion,
initcap(c.ciu_descripcion) ciudad
from sucursales s
join empresa e on e.emp_cod = s.emp_cod
join ciudad c on c.ciu_cod = s.ciu_cod
where s.emp_cod = {$u['emp_cod']} and s.suc_cod = {$u['suc_cod']}";

$resEmp = pg_query($conexion, $sqlEmp);
$datosEmp = pg_fetch_all($resEmp);

//datos del cobro
$cobr_cod = $_POST['cobr_cod'];

$sqlCobro = "select
    cd.cobr_cod codigo,
    sum(cd.cobrdet_monto) monto,
    to_char(cc.cobr_fecha,'DD \"de\" TMMonth \"de\" YYYY') fecha,
    cc.cliente cliente,
    cc.per_email email,
    cc.cobr_nrocuota||'/'||cc.cuencob_cuotas cuotas,
    cc.ven_nrofac factura
from v_cobros_det cd
    join v_cobros_cab cc on cd.cobr_cod = cc.cobr_cod
where cd.cobr_cod = $cobr_cod
group by 1,3,4,5,6,7;";

$resultado = pg_query($conexion, $sqlCobro);
$datos = pg_fetch_all($resultado);

//medios de pago
$sqlForma = "select 
	vcd.forcob_descri,
	vcd.cobrdet_monto 
from v_cobros_det vcd
where vcd.cobr_cod = $cobr_cod;";


$resForma = pg_query($conexion, $sqlForma);
$datosForma = pg_fetch_all($resForma);
?>

<body>

    <style>

        .contenedor {
            width: calc(100% - 30px); /* Compensa los 15px de margen izquierdo y derecho */
            margin: 0 auto; /* Centra el contenedor */
            padding: 20px;
            border: 1px solid black;
            border-radius: 30px;
            box-sizing: border-box; /* Incluye padding y border en el ancho total */
        }

        .titulo_izq {
            width: 33.3%; 
            border: 1px solid black;  
            border-radius: 10px;  
            text-align: center;
        }

        .titulo_med {
            position: absolute; 
            top: 30; 
            left: 50%; 
            text-align: center; 
            transform: translateX(-50%); 
            space-blank: normal; 
            width:30%;
        }

        .titulo_der {
            position: absolute; 
            top: 20px; 
            right: 20px; 
            text-align: right;  
            width: 33.3%;
        }

        .cuerpo {
            margin-top: 25px; 
            font-size:25px;
            width: 100%;
            cellspacing: 0;
            cellpadding: 0;
        }

        .cuerpo_td_izq {
            vertical-align: bottom; 
            padding-right: 5px; 
            font-weight: bold;
        }

        .cuerpo_td_der {
            vertical-align: bottom; 
            background-color: #d3d3d3; 
            padding: 5px; 
            border-radius: 10px;
        }

        .firma {
            margin-top: 25px; 
            margin-left: auto; 
            width: 300px; 
            text-align: center; 
            border:1px solid black;  
            border-radius:10px; 
            padding:5px;
        }

    </style>

    <?php foreach ($datos as $cabecera) {
        foreach ($datosEmp as $emp) { ?>
            <div class="contenedor" style="position: relative;">
                <!-- titulo -->
                <div class="titulo_izq">
                    <div style="font-size:30px; font-weight:bold;"><?php echo $emp['emp_razonsocial'];?></div>
                    <div style="font-size:20px;">RUC: <?php echo $emp['emp_ruc']; ?></div>
                    <div style="font-size:15px; font-weight:bold;"><?php echo $emp['suc_direccion']; ?></div>
                </div>
                <div class="titulo_med">
                    <div style="font-size:30px; font-weight:bold;">RECIBO DE PAGO</div>
                </div>
                <div class="titulo_der">
                    <div style="font-size:30px; font-weight:bold; margin-bottom:5px;">N° <?php echo $cabecera['codigo'];?></div>
                    <span style="font-size:24px; font-weight:bold; background-color:#d3d3d3; padding:5px; border-radius:10px; margin-bottom:5px;">Gs. <?php echo number_format($cabecera['monto'],0,',','.')?></span>
                    <div style="font-size:20px; margin:10px;"><?php echo $emp['ciudad'].', '.$cabecera['fecha'];?></div>
                </div>
                <!-- cuerpo del recibo -->
                <table  class="cuerpo">
                    <tr>
                        <td width="30%" class="cuerpo_td_izq">RECIBI (MOS) DE: </td>
                        <td width="100%" class="cuerpo_td_der">
                            <span style="font-size:25px;"><?php echo $cabecera['cliente']?></span>
                            <div style="border-bottom: 1px solid black;"></div>
                        </td>
                    </tr>
                </table>
                <table class="cuerpo">
                    <tr>
                        <td width="50%" class="cuerpo_td_izq">LA SUMA DE GUARANIES: </td>
                        <td width="100%" class="cuerpo_td_der">
                            <span style="font-size:25px;"><?php echo mb_strtoupper($letras -> format($cabecera['monto']))?></span>
                            <div style="border-bottom: 1px solid black;"></div>
                        </td>
                    </tr>
                </table>
                <table class="cuerpo">
                    <tr>
                        <td width="33%" class="cuerpo_td_izq">EN CONCEPTO DE: </td>
                        <td width="100%" class="cuerpo_td_der">
                            <span style="font-size:25px;"> LA CUOTA <?php echo $cabecera['cuotas']?>, POR LA COMPRA N° <?php echo $cabecera['factura']?></span>
                            <div style="border-bottom: 1px solid black;"></div>
                        </td>
                    </tr>
                </table>
                <!-- firma -->
                <div class="firma">
                    <span style="font-size:15px; margin-bottom:5px; border-bottom:1px solid black; display:inline-block;"><?php echo $u['per_nombres'] . " " . $u['per_apellidos'] ?></span>
                    <div style="font-size:15px; margin:10px;"><?php echo $u['perf_descri']?> </div>
                </div>
                <br>
                <div class="titulo" style="font-size:20px; text-align: center;"><?php echo $emp['suc_descri'].' ------- '.$emp['suc_telefono'].' ------- '.$emp['suc_email']?></div>
            </div>
        <?php }
        } ?>

</body>

</html>

<?php
$html = ob_get_clean();

require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/vendor/autoload.php"; // Incluir el archivo de autoloading de Dompdf

// Crear una instancia de Dompdf
$dompdf = new Dompdf\Dompdf();

// Cargar el contenido HTML en Dompdf
$dompdf->loadHtml($html);

//Dar el formato horizontal y tamaño de hoja A4 al pdf
$dompdf->setPaper('A4', 'landscape', 'default', [
    'margin_top' => 15,
    'margin_right' => 15,
    'margin_bottom' => 15,
    'margin_left' => 15
]);

// Renderizar el contenido HTML a PDF
$dompdf->render();

// Obtener el contenido del PDF como string
$pdfOutput = $dompdf->output(); // Guardar en variable

//------------------------------------------------------------------ CORREO -----------------------------------------------------------------------
foreach ($datos as $correo) {
    $monto = $correo['monto'];
    $fecha = $correo['fecha'];
    $cliente = $correo['cliente'];
    $correoElectronico = $correo['email'];
    $cuota = $correo['cuotas'];
    $factura = $correo['factura'];
}

// Configurar PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Crear una instancia de PHPMailer
$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'orbus.gym0@gmail.com'; // Tu dirección de correo
    $mail->Password = 'vrug vzrr syrn njzl'; // La contraseña de aplicación
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilitar SSL
    $mail->Port = 587; // Puerto para SSL

    // Remitente y destinatario
    $mail->setFrom('orbus.gym0@gmail.com', 'Orbus Gym');
    $mail->addAddress($correoElectronico, $cliente);      // Destinatario

    // Adjuntar el PDF generado desde la variable
    $mail->addStringAttachment($pdfOutput, 'Recibo de pago nro '.$cobr_cod.'pdf', 'base64', 'application/pdf');

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Recibo de Pago Nro. '.$cobr_cod; //asunto del correo
    $mail->Body = '<html lang="es">
                    <body>
                        <p>Estimado/a '.$cliente.'</p>

                        <p>
                            Por medio de la presente, confirmamos
                            la recepcion de su pago según los siguientes detalles:
                        </p>

                        <h3>Detalles del pago:</h3>
                        <p>
                            <strong>Número de Recibo:</strong> '.$cobr_cod.'<br />
                            <strong>Fecha:</strong> '.$fecha.'<br />
                            <strong>Cliente:</strong> '.$cliente.'<br />
                            <strong>Total abonado:</strong> '.number_format($monto,0,',','.').'
                        </p>

                        <h3>Condiciones de pago:</h3>
                            <ul>';
                            foreach ($datosForma as $forma) {
                                $mail->Body .= '<li> <strong>'.$forma['forcob_descri'].':</strong> Gs. '.number_format($forma['cobrdet_monto'], 0, ',', '.').'</li>';
                            }

                            $mail->Body .= '
                            </ul>
                        <p>
                            Si necesitan más información o tienen alguna
                            pregunta, no duden en contactarse.
                        </p>

                        <p>Gracias por su colaboración.</p>

                        <p>
                            Saludos cordiales,<br />
                            '.$u['per_nombres'].' '.$u['per_apellidos'].'<br />
                            '.$u['perf_descri'].'<br />
                            '.$u['emp_razonsocial'].'<br />
                            '.$u['suc_telefono'].'<br />
                            orbus.gym0@gmail.com
                        </p>
                    </body>';

    //Enviar el correo
    $mail->send();

    $response = array(
        "mensaje" => "Correo enviado con éxito!",
        "tipo" => "success"
    );
    echo json_encode($response);

} catch (PHPMailerException $e) {
    $response = array(
        "mensaje" => "Error al enviar el correo electrónico: " . $e->getMessage(),
        "tipo" => "error"
    );
    echo json_encode($response);
}

?>