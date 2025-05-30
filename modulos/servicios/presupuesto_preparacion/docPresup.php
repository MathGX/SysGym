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
    <title>PRESUPUESTO DE SERVICIOS</title>
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


$prpr_cod = $_GET['prpr_cod'];

$sql = "select * from v_presupuesto_prep_cab
where prpr_cod = $prpr_cod;";

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
                    <h3 style="margin:0 auto; border:1px solid black; border-radius:20px; background-color:#4d4d4d; color:white">PRESUPUESTO DE SERVICIOS N° <?php echo $cabecera['prpr_cod'];?> </h3>
                </div>
            </div>
            <br><br>
        <?php } ?>
        <div class="grilla">
            <div class="cabecera">
                <table>
                    <tr>
                        <td class="subrayar"  style="width:8.5%">Cliente solicitante: </td>
                        <td style="width:32.5%;"> <?php echo $cabecera['cliente'];?> </td>
                        <td class="subrayar"  style="width:8.5%">N° de inscripcion: </td>
                        <td> <?php echo $cabecera['ins_cod'];?> </td>
                    </tr>
                </table>
                <br>
                <table>
                    <tr>
                        <td class="subrayar" style="width:10%;" colspan="1"> Fecha de emisión: </td>
                        <td style="width:12%;"><?php echo $cabecera['prpr_fecha'];?> </td>
                        <td class="subrayar" style="width:10%;"> Fecha de vencimiento: </td>
                        <td> <?php echo $cabecera['prpr_fechavenci2'];?> </td>
                    </tr>
                </table>
                <br>
            </div>
            <br>
            <div class="detalle">
                <div style="font-family:'Times New Roman',Times,serif; font-style:italic;">Mediante la presente se detallan los montos de los servicios solicitados</div>
                <table>
                    <thead>
                        <tr>
                            <td>Cant. de meses</td>
                            <td style="width:38%;">Servicio</td>
                            <td style="width:12%;">Mensualidad</td>
                            <td style="width:12%;">Exenta</td>
                            <td style="width:12%;">IVA 5%</td>
                            <td style="width:12%;">IVA 10%</td>
                        </tr>
                    </thead>
                    <?php
                    $cab = $cabecera['prpr_cod'];
                    $sql2 = "select * from v_presupuesto_prep_det
                    where prpr_cod = $cab";
                    $resultado2 = pg_query($conexion, $sql2);
                    $datos2 = pg_fetch_all($resultado2);
                    
                    $totalExe = 0;
                    $totalI5 = 0;
                    $totalI10 = 0;
                    $discrIva5 = 0;
                    $discrIva10 = 0;
                    $totalIva = 0;
                    $totalGral = 0;
                    foreach ($datos2 as $detalle) { 
                        $totalExe += floatval ($detalle['exenta']);
                        $totalI5 += floatval ($detalle['iva5']);
                        $totalI10 += floatval ($detalle['iva10']);
                        ?>
                        <tbody>
                            <tr>
                                <td class="right"> <?php echo $detalle['prprdet_cantidad'];?> </td>
                                <td class="left"> <?php echo $detalle['itm_descri'];?> </td>
                                <td class="right"> <?php echo number_format($detalle['prprdet_precio'], 0, ',','.');?> </td>
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
                            <td class="right"> <?php echo number_format($totalExe, 0, ',','.');?> </td>
                            <td class="right"> <?php echo number_format($totalI5, 0, ',','.');?> </td>
                            <td class="right"> <?php echo number_format($totalI10, 0, ',','.');?> </td>
                        </tr>
                        <tr>
                            <td class="left" colspan="5"> Total a pagar: </td>
                            <td class="right"> <?php echo number_format($totalGral, 0, ',','.');?> </td>
                        </tr>
                        <tr>
                            <td class="left" colspan="2"> IVA 5%: <?php echo $discrIva5_format?> </td>
                            <td class="left" colspan="2"> IVA 10%: <?php echo $discrIva10_format ?> </td>
                            <td class="left" colspan="2"> Total IVA: <?php echo $totalIva_format?> </td>
                        </tr>
                    </tfoot>
                </table>
                <br>
                <div>
                    <?php $sql3= "select
                    max(vppd.prprdet_cantidad) cuotas,
                    (sum(vppd.exenta + vppd.iva5 + vppd.iva10) / max(vppd.prprdet_cantidad)) as monto
                    from v_presupuesto_prep_det vppd
                    where vppd.prpr_cod = $cab
                    group by vppd.prpr_cod
                    order by vppd.prpr_cod;";
                    $resultado3 = pg_query($conexion, $sql3);
                    $datos3 = pg_fetch_all($resultado3);

                    foreach ($datos3 as $pago) {?>
                        <div style ="font-size:15px; ">
                            En base al presupuesto presentado el plazo de pago queda en <strong><?php echo $pago['cuotas']?></strong> 
                            cuotas de <strong><?php echo number_format($pago['monto'], 0, ',','.');?></strong> Gs.
                        </div>
                    <?php };?>
                </div>
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

// Generar el archivo PDF y guardarlo en el servidor o descargarlo
$dompdf->stream(' NRO '.$prpr_cod, array("Attachment" => false));

// Obtener el contenido del PDF como string
$pdfOutput = $dompdf->output(); // Guardar en variable


?>