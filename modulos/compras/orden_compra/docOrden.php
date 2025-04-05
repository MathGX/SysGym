<?php
session_start();
$u = $_SESSION['usuarios'];

$fechaActual = date('d-m-Y');

ob_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ORDEN DE COMPRA</title>
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


$ordcom_cod = $_GET['ordcom_cod'];

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
                        <td><span class="subrayar">Proveedor:</span></td>
                        <td><?php echo $cabecera['pro_razonsocial']; ?></td>
                        <td><span class="subrayar">Fecha de emisión:</span></td>
                        <td><?php echo $cabecera['ordcom_fecha']; ?></td>
                    </tr>
                    <tr>
                        <td><span class="subrayar">Condición de Pago:</span></td>
                        <td><?php echo $cabecera['ordcom_condicionpago']; ?></td>
                        <td><span class="subrayar">Cantidad de Cuotas:</span></td>
                        <td><?php echo $cabecera['ordcom_cuota']; ?></td>
                    </tr>
                    <tr>
                        <td><span class="subrayar">Monto de Cuota:</span></td>
                        <td colspan="3"><?php echo number_format($cabecera['ordcom_montocuota'], 0, ',', '.'); ?></td>
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

// Generar el archivo PDF y guardarlo en el servidor o descargarlo
$dompdf->stream(' NRO '.$ordcom_cod, array("Attachment" => false));

// Obtener el contenido del PDF como string
$pdfOutput = $dompdf->output(); // Guardar en variable


?>