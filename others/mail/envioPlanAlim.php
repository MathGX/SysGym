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

$alim_cod = $_POST['alim_cod'];

$sql = "select * from v_plan_alimenticio_cab
where alim_cod = $alim_cod;";

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
            font-family: Arial, sans-serif; 
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
            font-family: Arial, sans-serif; 
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
            flex-wrap: wrap;
        }

        .detalle table thead td {
            text-align: center; 
            font-size:10px; 
            font-weight:bold;
            white-space:normal;
            border: 1px solid black;
            background-color: #4d4d4d;
            color: white;
            padding: 5px;
            flex: 1 1 auto; 
        }

        .detalle tbody td{
            font-size: 10px;
            border: 1px solid black;
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
                    <h3 style="margin:0 auto; border:1px solid black; border-radius:20px; background-color:#4d4d4d; color:white">PLAN ALIMENTICIO N° <?php echo $cabecera['alim_cod'];?> </h3>
                </div>
            </div>
            <br><br>
        <?php } ?>
        <div class="grilla">
            <div class="cabecera">
                <table>
                    <tr>
                        <td class="subrayar"  style="width:7%">Cliente: </td>
                        <td style="width:32.5%;"> <?php echo $cabecera['cliente'];?> </td>
                        <td class="subrayar"  style="width:8.5%">N° de inscripcion: </td>
                        <td style="width:7.5%;"> <?php echo $cabecera['ins_cod'];?> </td>
                        <td class="subrayar" style="width:10%;" colspan="1"> Fecha de emisión: </td>
                        <td style="width:25%;"><?php echo date('d/m/Y', strtotime($cabecera['alim_fecha']));?> </td>
                    </tr>
                </table>
                <br>
            </div>
            <br>
            <div class="detalle" style="display:flexbox;">
                <div style="font-family:'Times New Roman',Times,serif; font-style:italic;">Mediante la presente se detallan los alimentos que componen el plan de tipo "<?php echo $cabecera['tiplan_descri'];?>"</div>
                <table>
                    <thead>
                        <tr>
                            <td style="">DIA</td>
                            <td style="">HORARIO</td>
                            <td style="">COMIDA</td>
                            <td style="width:7.5%;">CANT. PROTEINAS</td>
                            <td style="width:7.5%;">CANT. CARBOHIDRATOS</td>
                            <td style="width:7.5%;">CANT. CALORIAS</td>
                        </tr>
                    </thead>
                    <?php
                    $cab = $cabecera['alim_cod'];
                    $sql2 = "select * from v_plan_alimenticio_det
                    where alim_cod = $cab order by dia_cod";
                    $resultado2 = pg_query($conexion, $sql2);
                    $datos2 = pg_fetch_all($resultado2);
                    
                    foreach ($datos2 as $row => $detalle) { ?>
                        <tbody>
                            <tr>
                                <td class="left"> <?php echo $detalle['dia_descri'];?> </td>
                                <td class="left"> <?php echo $detalle['hrcom_descri'];?> </td>
                                <td class="left"> <?php echo $detalle['comi_descri']?> </td>
                                <td class="right"> <?php echo $detalle['alimdet_proteina']?> </td>
                                <td class="right"> <?php echo $detalle['alimdet_carbohidratos']?> </td>
                                <td class="right"> <?php echo $detalle['alimdet_calorias']?> </td>
                            </tr>
                            <?php if ($row < count($datos2) - 1 && $detalle['dia_descri'] !== $datos2[$row + 1]['dia_descri']) { ?>
                                <tr style="background-color: #d3d3d3;"> 
                                    <td colspan="6">&nbsp;</td>
                                </tr>
                            <?php }?>
                        </tbody>
                    <?php } ?>
                </table>
            </div>
            <br><br>
            <div class="final">
                <table style="margin:0 auto; width:30%;">
                    <tr>
                        <td style="font-weight:bold; text-align:right;"> Emitido por:</td>
                        <td style="padding-left:5px;"> <?php echo $u['per_nombres'] . " " . $u['per_apellidos'] ?> </td>
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
$per_email = $_POST['per_email'];

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
    $mail->addAddress($per_email, $_POST['cliente']);      // Destinatario

    // Adjuntar el PDF generado desde la variable
    $mail->addStringAttachment($pdfOutput, 'Plan alimenticio nro '.$alim_cod.'pdf', 'base64', 'application/pdf');

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Plan alimenticio Nro. '.$alim_cod; //asunto del correo
    $mail->Body = '<html lang="es">
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            line-height: 1.6;
                            margin: 20px;
                            color: #333;
                        }
                        .footer {
                            margin-top: 20px;
                            font-size: 0.9em;
                            color: #777;
                        }
                    </style>
                    <body>
                        <p>Estimado/a '.$_POST['cliente'].'</p>

                        <p>
                            Por medio de la presente, se adjunta a este correo el plan alimenticio 
                            solicitado por usted. En el documento encontrará un desglose detallado 
                            de los alimentos que debe consumir con su valor nutricional, así como los 
                            horarios de ingesta.
                        </p>

                        <p>
                            Si tiene alguna pregunta o requiere aclaraciones adicionales, 
                            no dude en ponerse en contacto. Estamos a su disposición para ayudarle.
                        </p>

                        <p>Le deseamos un buen resto de jornada.</p>

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