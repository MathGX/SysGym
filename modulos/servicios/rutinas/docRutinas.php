<?php
session_start();
$u = $_SESSION['usuarios'];

date_default_timezone_set('America/Asuncion');
$horaActual = date("H:i:s");

ob_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RUTINA DEL CLIENTE</title>
</head>
<?php
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


$rut_cod = $_GET['rut_cod'];

$sql = "select * from v_rutinas_cab
where rut_cod = $rut_cod;";

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
        }

        .detalle table thead td {
            text-align: center; 
            font-size:12px; 
            font-weight:bold;
            white-space:normal;
            border: 1px solid black;
            background-color: #4d4d4d;
            color: white;
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
                    <h3 style="margin:0 auto; border:1px solid black; border-radius:20px; background-color:#4d4d4d; color:white">RUTINA N° <?php echo $cabecera['rut_cod'];?> </h3>
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
                        <td style="width:25%;"><?php echo $cabecera['rut_fecha'];?> </td>
                    </tr>
                </table>
                <br>
            </div>
            <br>
            <div class="detalle">
                <div style="font-family:'Times New Roman',Times,serif; font-style:italic;">Mediante la presente se detallan los ejercicios de "<?php echo $cabecera['tiprut_descri'];?>"</div>
                <table>
                    <thead>
                        <tr>
                            <td style="width:15%;">DIA</td>
                            <td style="width:30%;">EJERCICIO</td>
                            <td style="width:30%;">EQUIPO A UTILIZAR</td>
                            <td style="width:12.5%;">SERIES</td>
                            <td style="width:12.5%;">REPETICIONES</td>
                        </tr>
                    </thead>
                    <?php
                    $cab = $cabecera['rut_cod'];
                    $sql2 = "select * from v_rutinas_det
                    where rut_cod = $cab order by dia_cod";
                    $resultado2 = pg_query($conexion, $sql2);
                    $datos2 = pg_fetch_all($resultado2);
                    
                    foreach ($datos2 as $row => $detalle) { ?>
                        <tbody>
                            <tr>
                                <td class="left"> <?php echo $detalle['dia_descri'];?> </td>
                                <td class="left"> <?php echo $detalle['ejer_descri'];?> </td>
                                <td class="left"> <?php echo $detalle['equi_descri']?> </td>
                                <td class="right"> <?php echo $detalle['rutdet_series']?> </td>
                                <td class="right"> <?php echo $detalle['rutdet_repeticiones']?> </td>
                            </tr>
                            <?php if ($row < count($datos2) - 1 && $detalle['dia_descri'] !== $datos2[$row + 1]['dia_descri']) { ?>
                                <tr style="background-color: #d3d3d3;"> 
                                    <td colspan="5">&nbsp;</td>
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
                        <td style="padding-left:5px;"> <?php echo $u['per_nombres'] . " " . $u['per_apellidos']. " a las " .$horaActual. " hs"  ?> </td>
                    </tr>
                </table>
            </div>
        </div>
        <br>
        <?php foreach ($datosEmp as $pie) { ?>
            <div class="titulo" style="font-size:12px; font-family: Arial, sans-serif; "><?php echo $pie['suc_descri'].' ------- '.$pie['suc_telefono'].' ------- '.$pie['suc_email']?></div>
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

// Generar el archivo PDF y guardarlo en el servidor o descargarlo
$dompdf->stream(' NRO '.$rut_cod, array("Attachment" => false));

// Obtener el contenido del PDF como string
$pdfOutput = $dompdf->output(); // Guardar en variable


?>