<?php
session_start();
$u = $_SESSION['usuarios'];

$letras = new NumberFormatter('es', NumberFormatter::SPELLOUT);

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
initcap(s.suc_descri) suc_descri,
s.suc_telefono,
s.suc_email,
initcap(s.suc_direccion) suc_direccion,
initcap(c.ciu_descripcion) ciudad
from sucursales s
join empresa e on e.emp_cod = s.emp_cod
join ciudad c on c.ciu_cod = s.ciu_cod
where s.emp_cod = {$u['emp_cod']} and s.suc_cod = {$u['suc_cod']}";

$resEmp = pg_query($conexion, $sqlEmp);
$emp = pg_fetch_assoc($resEmp);

$notven_cod = $_POST['notven_cod'];
$tipcomp_cod = $_POST['tipcomp_cod'];

//Datos de cabecera
$sqlCab = "select 
	f.notven_timbrado timbrado,
	to_char(t.tim_fec_ini, 'dd/mm/yyyy') vigencia_ini,
	to_char(f.notven_timb_fec_venc, 'dd/mm/yyyy') vigencia_fin,
	f.tipcomp_descri tipo,
	f.notven_nronota comprobante,
	to_char(f.notven_fecha ,'DD \"de\" TMMonth \"de\" YYYY') fecha,
	initcap(f.ven_tipfac::varchar) condicion,
	initcap(f.cliente) cliente,
	f.per_nrodoc ruc,
	initcap(ciu.ciu_descripcion||', '||c.cli_direccion) direc,
	p.per_telefono telf,
    f.ven_nrofac factura
from v_nota_venta_cab f 
	join timbrados_auditoria t on t.tim_nro = f.notven_timbrado::integer
	join clientes c on c.cli_cod = f.cli_cod 
		join personas p on p.per_cod = c.per_cod
        join ciudad ciu on ciu.ciu_cod = c.ciu_cod
where f.notven_cod = $notven_cod
group by 1,2,3,4,5,6,7,8,9,10,11,12;";

$resulCab = pg_query($conexion, $sqlCab);
$cabecera = pg_fetch_assoc($resulCab);

//Datos de detalle
$sqlDet = "select
    cd.notvendet_cantidad cant,
    initcap(cd.itm_descri) item,
    cd.notvendet_precio precio,
    cd.exenta,
    cd.iva5,
    cd.iva10
from v_nota_venta_det cd
where cd.notven_cod = $notven_cod";

$resulDet = pg_query($conexion, $sqlDet);
$datosDet = pg_fetch_all($resulDet);

//Datos de totales
$sqlTot = "select 
    sum(cd.exenta+cd.iva5+cd.iva10) monto,
    sum(cd.exenta) exenta,
    sum(cd.iva5) iva5,
    sum(cd.iva10) iva10
from v_nota_venta_det cd
where cd.notven_cod= $notven_cod";

$resulTot = pg_query($conexion, $sqlTot);
$total = pg_fetch_assoc($resulTot);

?>

<body>

    <style>

        .contenedor {
            width: calc(100% - 30px); /* Compensa los 15px de margen izquierdo y derecho */
            margin: 0 auto; /* Centra el contenedor */
            padding: 10px;
            box-sizing: border-box; /* Incluye padding y border en el ancho total */
        }

        .titulo_izq {
            width: 53.5%; 
            border: 1px solid black;  
            border-radius: 10px;  
            text-align: center;
            padding: 15px;
        }

        .titulo_der {
            border: 1px solid black;  
            border-radius: 10px;  
            padding: 9px;
            text-align: center;  
            width: 45%;
            position: absolute;
            top: 10px;
            right: 10px; 
        }

        .cuerpo {
            margin-top: 5px; 
            font-size:15px;
            width: 100%;
            border: 1px solid black;  
            border-radius: 10px; 
        }        
        
        .grilla {
            margin-top: 5px; 
            font-size:15px;
            width: 100%;
            border-collapse: collapse;
            border: 1px solid black;  
            border-radius: 10px; 
        }

        .grilla td {
            border: 1px solid black;  
            padding: 5px;
        }

    </style>

    <div class="contenedor" style="position: relative; font-family: Arial, sans-serif;">
        <!-- titulo -->
        <table class="titulo_izq">
            <tbody>
                <tr>
                    <?php
                    // Construir ruta absoluta al logo en el servidor
                    $logoPath = rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . '/SysGym/images/logo.jpg';
                    if (file_exists($logoPath)) {
                        // Intentar detectar el mime type (jpg, png, gif, etc.)
                        $ext = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
                        $mime = 'image/jpeg';
                        if ($ext === 'png') $mime = 'image/png';
                        if ($ext === 'gif') $mime = 'image/gif';

                        // Leer y codificar en base64 para que Dompdf lo acepte sin problemas
                        $data = base64_encode(file_get_contents($logoPath));
                        $src = 'data:' . $mime . ';base64,' . $data;
                        // Mostrar la imagen con estilo de ajuste responsivo
                        echo '<td rowspan="4"><img src="' . $src . '" alt="" style="max-width:120px; height:auto; display:block; border-radius:15px;"/></td>';
                    } else {
                        // Fallback: intentar usar la ruta web (útil cuando se muestra en navegador)
                        echo '<td rowspan="4"><img src="/SysGym/images/logo.jpg" alt="" style="max-width:120px; height:auto; display:block; border-radius:15px;"/></td>';
                    }
                    ?>
                    <td style="font-size:30px; font-weight:bold;"><?php echo $emp['emp_razonsocial'];?></td>
                    <tr>
                        <td style="font-size:20px;"><?php echo $emp['suc_descri']; ?></td>
                    </tr>
                    <tr>
                        <td style="font-size:15px; font-weight:bold;"><?php echo $emp['ciudad'].', '.$emp['suc_direccion']; ?></td>
                    </tr>
                    <tr>
                        <td style="font-size:15px; font-weight:bold;">Telf.:<?php echo $emp['suc_telefono']; ?></td>
                    </tr>
                </tr>
            </tbody>
        </table>
        <table class="titulo_der">
            <tbody>
                <tr>
                    <td><b>Timbrado N° <?php echo $cabecera['timbrado']; ?></b></td>
                </tr>
                <tr>
                    <td><b>Fecha Inicio Vigencia: </b><?php echo $cabecera['vigencia_ini']; ?></td>
                </tr>
                <tr>
                    <td><b>Fecha Fin Vigencia: </b><?php echo $cabecera['vigencia_fin']; ?></td>
                </tr>
                <tr>
                    <td><b>RUC: </b><?php echo $emp['emp_ruc']; ?></td>
                </tr>
                <tr>
                    <td><b><?php echo $cabecera['tipo'];?></b></td>
                </tr>
                <tr>
                    <td><b>N° <?php echo $cabecera['comprobante'];?></b></td>
                </tr>
            </tbody>
        </table>

        
        <!-- cuerpo del recibo -->
        <table class="cuerpo">
            <table style="border-collapse:collapse; width:100%;">
                <tbody>
                    <tr>
                        <td style="padding:5px; width:50%; border-bottom: 1px solid black; border-right: 1px solid black;"><b>FECHA DE EMISIÓN: </b><?php echo $cabecera['fecha'];?></td>
                        <td style="padding:5px; width:50%; border-bottom: 1px solid black; background-color: #d3d3d3;"><b>Factura de Venta: </b><?php echo $cabecera['factura'];?></td>
                    </tr>
                    <tr>
                        <td style="padding:5px; width:70%;"><b>Nombre o Razón Social: </b><?php echo $cabecera['cliente'];?></td>
                        <td style="padding:5px; width:30%;"><b>RUC o C.I. N°: </b><?php echo $cabecera['ruc'];?></td>
                    </tr>
                    <tr>
                        <td style="padding:5px; width:70%;"><b>Dirección: </b><?php echo $cabecera['direc'];?></td>
                        <td style="padding:5px; width:30%;"><b>Teléfono: </b><?php echo $cabecera['telf'];?></td>
                    </tr>
                </tbody>
            </table>            
        </table>

        <?php if ($tipcomp_cod == 3) {

            //Datos de currier nota de remision
            $sqlRemi = "select 
                initcap(vc.funcionario) funcionario,
                cv.chapve_chapa chapa,
                mv.marcve_descri||' '||mv2.modve_descri vehiculo
            from v_nota_venta_cab vc
                join chapa_vehiculo cv on cv.chapve_cod = vc.notven_chapa_vehi 
                join marca_vehiculo mv on mv.marcve_cod = vc.marcve_cod 
                join modelo_vehiculo mv2 on mv2.modve_cod = vc.modve_cod
            where vc.notven_cod= $notven_cod";

            $resulRemi = pg_query($conexion, $sqlRemi);
            $remision = pg_fetch_assoc($resulRemi);
        ?>
        <table class="cuerpo">
            <table style="border-collapse:collapse; width:100%;">
                <tbody>
                    <tr>
                        <td colspan="2" style="padding:5px; border-bottom: 1px solid black;"><b>DATOS PARA EL ENVÍO</b><td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding:5px;"><b>Currier: </b><?php echo $remision['funcionario'];?></td>
                    </tr>
                    <tr>
                        <td style="padding:5px; width:50%;"><b>Vehiculo: </b><?php echo $remision['vehiculo'];?></td>
                        <td style="padding:5px; width:50%;"><b>Chapa: </b><?php echo $remision['chapa'];?></td>
                    </tr>
                </tbody>
            </table>
        </table>
        <?php }?>

        <table class="grilla">
            <tbody>
                <tr align="center" style="background-color:#d3d3d3; font-weight:bold;">
                    <td>Cant.</td>
                    <td>Producto o Servicio</td>
                    <td>Precio Unit.</td>
                    <?php if ($tipcomp_cod == 3) {?>
                    <td>Total</td>
                    <?php } else {?>
                    <td>Exenta</td>
                    <td>IVA 5%</td>
                    <td>IVA 10%</td>
                    <?php } ?>
                </tr>
                <?php foreach ($datosDet as $det) { ?>
                <tr>
                    <td style="text-align:center;"><?php echo $det['cant'];?></td>
                    <td><?php echo $det['item'];?></td>
                    <td style="text-align:right;"><?php echo number_format($det['precio'],0,',','.');?></td>
                    <?php if ($tipcomp_cod == 3) {?>
                    <td style="text-align:right;"><?php echo number_format($det['exenta']+$det['iva5']+$det['iva10'],0,',','.');?></td>
                    <?php } else {?>
                    <td style="text-align:right;"><?php echo number_format($det['exenta'],0,',','.');?></td>
                    <td style="text-align:right;"><?php echo number_format($det['iva5'],0,',','.');?></td>
                    <td style="text-align:right;"><?php echo number_format($det['iva10'],0,',','.');?></td>
                    <?php } ?>
                </tr>
                <?php } ?>
                <?php if (in_array ($tipcomp_cod, [1,2])) {?>
                <tr>
                    <td colspan="3" style="text-align:left; background-color:#d3d3d3; font-weight:bold;">Sub Totales</td>
                    <td style="text-align:right; font-weight:bold;"><?php echo number_format($total['exenta'],0,',','.');?></td>
                    <td style="text-align:right; font-weight:bold;"><?php echo number_format($total['iva5'],0,',','.');?></td>
                    <td style="text-align:right; font-weight:bold;"><?php echo number_format($total['iva10'],0,',','.');?></td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align:left; background-color:#d3d3d3; font-weight:bold;">Total a Pagar</td>
                    <td style="text-align:right; font-weight:bold;"><?php echo number_format($total['monto'],0,',','.');?></td>
                </tr>
                <tr>
                    <td colspan="6"><b>Total a Pagar (en letras) guaraníes: </b><?php echo mb_strtoupper($letras -> format($total['monto']))?></td>
                </tr>
                <tr>
                    <td colspan="2"><b>Liquidación del IVA: </b></td>
                    <td style="text-align:right;"><b>(5%): <?php echo number_format($total['iva5']/21,2,',','.');?></b></td>
                    <td style="text-align:right;"><b>(10%): <?php echo number_format($total['iva10']/11,2,',','.');?></b></td>
                    <td  colspan="2" style="text-align:right;"><b>Total IVA: <?php echo number_format(($total['iva10']/11)+($total['iva5']/21),2,',','.');?></b></td>
                </tr>                    
                <?php } ?>
            </tbody>
        </table>
    </div>

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
$dompdf->setPaper('A4', 'landscape');

// Renderizar el contenido HTML a PDF
$dompdf->render();

// Obtener el contenido del PDF como string
$pdfOutput = $dompdf->output(); // Guardar en variable

//------------------------------------------------------------------ CORREO -----------------------------------------------------------------------
$per_email = $_POST['per_email'];
$tipcomp_descri = mb_convert_case($cabecera['tipo'], MB_CASE_TITLE, "UTF-8");

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
    $mail->Password = 'nhrv fdgu fhjb gcio'; // La contraseña de aplicación
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilitar SSL
    $mail->Port = 587; // Puerto para SSL

    // Remitente y destinatario
    $mail->setFrom('orbus.gym0@gmail.com', 'Orbus Gym');
    $mail->addAddress($per_email, $cabecera['cliente']);      // Destinatario

    // Adjuntar el PDF generado desde la variable
    $mail->addStringAttachment($pdfOutput, $tipcomp_descri.' Nro '.$cabecera['comprobante'].'pdf', 'base64', 'application/pdf');

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = $tipcomp_descri.' Nro. '.$cabecera['comprobante']; //asunto del correo
    $mail->Body = '<html lang="es">
                    <body>
                        <p>Estimado/a '.$cabecera['cliente'].'</p>

                        <p>
                            Por medio de la presente, le facilitamos su correspondiente '.$tipcomp_descri.':
                        </p>

                        <h3>Detalles de la '.$tipcomp_descri.':</h3>
                        <p>
                            <strong>Número de Nota:</strong> '.$cabecera['comprobante'].'<br />
                            <strong>Fecha:</strong> '.$cabecera['fecha'].'<br />
                            <strong>Cliente:</strong> '.$cabecera['cliente'].'<br />
                            <strong>Dirección de Envío:</strong> '. $cabecera['direc'].'
                        </p>

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