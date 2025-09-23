<?php
session_start();
$u = $_SESSION['usuarios'];

//Establecemos el retorno del documento en formato json
header("Content-type: application/json; charset=utf-8");

ob_start();

require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$sqlEmp = "select
e.emp_razonsocial,
e.emp_ruc,
s.suc_descri,
s.suc_telefono,
s.suc_email,
s.suc_direccion
from sucursales s 
join empresa e on e.emp_cod = s.emp_cod 
where s.emp_cod = {$u['emp_cod']} and s.suc_cod = {$u['suc_cod']}";

$resEmp = pg_query($conexion, $sqlEmp);
$datosEmp = pg_fetch_all($resEmp);

$ordcom_cod = $_POST['ordcom_cod'];

$sql = "select * from v_orden_compra_cab
where ordcom_cod = $ordcom_cod;";

$resultado = pg_query($conexion, $sql);
$datos = pg_fetch_all($resultado);


?>

<body>

    <style>

        .contenedor {
            display: flex;               /* Activa Flexbox */
            flex-direction: column;      /* Alinea los elementos en columna */
            justify-content: center;      /* Centra verticalmente */
            align-items: center;          /* Centra horizontalmente */
            height: 100vh;               /* Altura del contenedor igual a la altura de la ventana */
        }

        .titulo {
            text-align: center;           /* Centra el texto dentro del div */
        }

        .subrayar {
            border-bottom: 1px solid black;
            font-size:15px; 
            font-weight: bold;
        }

        .grilla {
            page-break-inside: avoid;
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            white-space:nowrap;
        }

        .right {
            text-align: right;
            padding-right: 5px;
        }

        .left{
            text-align: left;
            padding-left: 5px;
        }

        .cabecera table {
            width: 100%;
            border-collapse: collapse;
        }

        .detalle table {
            width: 100%;
            border-collapse: collapse;
        }

        .detalle table thead td {
            text-align: center; 
            font-size:12px; 
            font-weight:bold;
            white-space:nowrap;
            border: 1px solid black;
            background-color: #4d4d4d;
            color: white;
        }

        .detalle tbody td{
            font-size: 10px;
            border: 1px solid black;
        }

        .detalle tfoot td {
            border: 1px solid black;
            font-weight: bold;
            font-size: 10px;
            background-color: #d4d4d4;
        }

        .final table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        .final table td {
            font-size: 10px;
            white-space:nowrap;
        }

    </style>

    <?php foreach ($datos as $cabecera) { 
        foreach ($datosEmp as $tit) { ?>
            <div class="contenedor">
                <div class="titulo">
                    <div style="font-size:22px; font-weight:bold;"> <?php echo $tit['emp_razonsocial'];?> </div>
                    <div style="font-size:14px;"> RUC: <?php echo $tit['emp_ruc']; ?> </div>
                    <div style="font-size:10px; font-weight:bold;"><?php echo $tit['suc_direccion']; ?> </div>
                    <br>
                    <h3 style="margin:0 auto; border:1px solid black; border-radius:20px; width:50%; background-color:#4d4d4d; color:white">ORDEN COMPRA N° <?php echo $cabecera['ordcom_cod'];?> </h3>
                </div>
            </div>
            <br><br>
        <?php } ?>
        <div class="grilla">
            <div class="cabecera">
                <table>
                    <tr>
                        <td class="subrayar"  style="width:8.5%">Proveedor: </td>
                        <td> <?php echo $cabecera['pro_razonsocial'];?> </td>
                    </tr>
                </table>
                <br>
                <table>
                    <tr>
                        <td class="subrayar" style="width:10%;" colspan="1"> Fecha de emisión: </td>
                        <td style="width:12%;"><?php echo $cabecera['ordcom_fecha'];?> </td>
                        <td class="subrayar" style="width:10%;">Condición de Pago: </td>
                        <td> <?php echo $cabecera['ordcom_condicionpago'];?> </td>
                    </tr>
                </table>
                <br>
                <table>
                    <tr>
                        <td class="subrayar" style="width:9%;"> Cantidad de Cuotas: </td>
                        <td style="width:5%;"><?php echo $cabecera['ordcom_cuota'];?> </td>
                        <td class="subrayar" style="width:9%;"> Monto de Cuota: </td>
                        <td> <?php echo number_format($cabecera['ordcom_montocuota'], 0, ',','.');?> </td>
                    </tr>
                </table>
            </div>
            <br>
            <div class="detalle">
                <div style="font-family:'Times New Roman',Times,serif; font-style:italic;">Mediante la presente nota se solicitan los siguientes articulos</div>
                <table>
                    <thead>
                        <tr>
                            <td>Cant.</td>
                            <td style="width:38%;">Item</td>
                            <td style="width:12%;">Precio Unit.</td>
                            <td style="width:12%;">Exenta</td>
                            <td style="width:12%;">IVA 5%</td>
                            <td style="width:12%;">IVA 10%</td>
                        </tr>
                    </thead>
                    <?php
                    $cab = $cabecera['ordcom_cod'];
                    $sql2 = "select * from v_orden_compra_det
                    where ordcom_cod = $cab";
                    $resultado2 = pg_query($conexion, $sql2);
                    $datos2 = pg_fetch_all($resultado2);
                    
                    $totalExe = 0;
                    $totalI5 = 0;
                    $totalI10 = 0;
                    $impuesto10 = 0;
                    $discrIva5 = 0;
                    $discrIva10 = 0;
                    $totalIva = 0;
                    $totalGral = 0;
                foreach ($datos2 as $detalle) { 
                    if ($detalle['tipitem_cod'] == 1) {
                        $impuesto10 = $detalle['ordcomdet_precio'];
                    } else {
                        $impuesto10 = $detalle['iva10'];
                    }
                    $totalExe += floatval ($detalle['exenta']);
                    $totalI5 += floatval ($detalle['iva5']);
                    $totalI10 += floatval ($impuesto10);
                    ?>
                    <tbody>
                        <tr>
                            <td class="right"> <?php echo $detalle['ordcomdet_cantidad'].' '.$detalle['uni_descri'];?> </td>
                            <td class="left"> <?php echo $detalle['itm_descri'];?> </td>
                            <td class="right"> <?php echo number_format($detalle['ordcomdet_precio'], 0, ',','.');?> </td>
                            <td class="right"> <?php echo number_format($detalle['exenta'], 0, ',','.');?> </td>
                            <td class="right"> <?php echo number_format($detalle['iva5'], 0, ',','.');?> </td>
                            <td class="right"> <?php echo number_format($detalle['iva10'], 0, ',','.');?> </td>
                        </tr>
                    </tbody>
                    <?php
                    $discrIva5 = floatval ($totalI5/21);
                    $discrIva5_format = number_format($discrIva5, 2, ',','.');

                    $discrIva10 = floatval ($totalI10/11);
                    $discrIva10_format = number_format($discrIva10, 2, ',','.');
                    }
                    
                    $totalIva = ($discrIva5 + $discrIva10);                  
                    $totalIva_format = number_format($totalIva, 2, ',','.');

                    $totalGral = ($totalExe + $totalI5 + $totalI10);?>
                    <tfoot>
                        <tr>
                            <td class="left" colspan="3"> Subtotal: </td>
                            <td class="right"> <?php echo number_format($totalExe);?> </td>
                            <td class="right"> <?php echo number_format($totalI5);?> </td>
                            <td class="right"> <?php echo number_format($totalI10);?> </td>
                        </tr>
                        <tr>
                            <td class="left" colspan="5"> Total a pagar: </td>
                            <td class="right"> <?php echo number_format($totalGral);?> </td>
                        </tr>
                        <tr>
                            <td class="left" colspan="2"> IVA 5%: <?php echo $discrIva5_format?> </td>
                            <td class="left" colspan="2"> IVA 10%: <?php echo $discrIva10_format ?> </td>
                            <td class="left" colspan="2"> Total IVA: <?php echo $totalIva_format?> </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <br><br>
            <div class="final">
                <table>
                    <tr>
                        <td style="width:auto; font-weight:bold;"> Emitido por:</td>
                        <td style="width:25%; border-bottom: 1px solid black;"> <?php echo $u['per_nombres'] . " " . $u['per_apellidos'] ?> </td>
                        <td class="left" style="width:auto; font-weight:bold;"> Autorizado por:</td>
                        <td style="width:25%; border-bottom: 1px solid black;"> </td>
                        <td  class="left" style="width:auto; font-weight:bold;"> Recibido por: </td>
                        <td style="width:20%; border-bottom: 1px solid black;"> </td>
                    </tr>
                </table>
            </div>
        </div>
        <br>
        <?php foreach ($datosEmp as $pie) { ?>
            <div class="titulo" style="font-size:12px;"><?php echo $pie['suc_descri'].' ------- '.$pie['suc_telefono'].' ------- '.$pie['suc_email']?></div>
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
$dompdf->setPaper('A4', 'portrait');

// Renderizar el contenido HTML a PDF
$dompdf->render();

// Obtener el contenido del PDF como string
$pdfOutput = $dompdf->output(); // Guardar en variable

//------------------------------------------------------------------ CORREO -----------------------------------------------------------------------
$pro_email = $_POST['pro_email'];

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
    $mail->addAddress($pro_email, $_POST['proveedor']);      // Destinatario

    // Adjuntar el PDF generado desde la variable
    $mail->addStringAttachment($pdfOutput, 'orden de compra nro '.$ordcom_cod.'pdf', 'base64', 'application/pdf');

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Orden de Compra Nro. '.$ordcom_cod; //asunto del correo
    $mail->Body = '<html lang="es">
                    <body>
                        <p>Estimado/a '.$_POST['proveedor'].'</p>

                        <p>
                            Por medio de la presente, confirmamos
                            nuestra orden de compra con los siguientes detalles:
                        </p>

                        <h3>Detalles de la Orden:</h3>
                        <p>
                            <strong>Número de Orden:</strong> '.$ordcom_cod.'<br />
                            <strong>Fecha:</strong> '.$_POST['ordcom_fecha'].'<br />
                            <strong>Proveedor:</strong> '.$_POST['proveedor'].'<br />
                            <strong>Dirección de Envío:</strong> '. $u['suc_direccion'].'
                        </p>

                        <h3>Condiciones:</h3>
                        <p>
                            <strong>Forma de Pago:</strong> '.$_POST['ordcom_condicionpago'].'<br />
                        </p>

                        <p>
                            Agradecemos su pronta atención a esta orden y quedamos atentos a la
                            confirmación del envío. Si necesitan más información o tienen alguna
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