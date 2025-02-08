<?php
session_start();
$usuario = $_SESSION['usuarios']['per_nombres'] . " " . $_SESSION['usuarios']['per_apellidos']; 
$perfil = $_SESSION['usuarios']['perf_descri'];
$modulo = $_SESSION['usuarios']['mod_descri'];

$fechaActual = date('d-m-Y');

ob_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de órdenes de compra</title>
</head>
<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$desde = $_GET['desde'];
$hasta = $_GET['hasta'];


$sql = "select * from v_orden_compra_cab
        where ordcom_fecha between '$desde' and '$hasta';";
$resultado = pg_query($conexion, $sql);
$datos = pg_fetch_all($resultado);


?>

<body>

    <style>
        .grilla {
        page-break-inside: avoid;
        width: 100%;
        font-size: 10px;
        border-collapse: collapse;
        }

        .cabecera th {
        width: 90px;
        text-align: left;
        font-weight: bold;
        padding: 8px;
        }

        .detalle {
        background-color: lightblue;
        font-weight: bold;
        text-align: center;
        border: 1px solid black;
        }
        
        .cuerpo:nth-child(even) {
            background-color: #f9f9f9;
        }

        .cuerpo, #subtotal, #total, #impuesto, td {
        width: 90px;
        padding: 8px;
        border: 1px solid black;
        }

        .item {
            display: block;
            margin-bottom: 10px;
            font-family: 'Times New Roman', Times, serif;
        }

        .label {
            font-weight: bold;
            font-size: 13px;
        }

        .valor {
            font-size: 12px;
        }

        .grilla2 {
            width: 300px;
            padding: 8px;
            border: 0px;
        }
    </style>

<h2>ORDENES DE COMPRA</h2>
    <?php foreach ($datos as $cabecera) { ?>
        <table class="grilla">
            <thead class="cabecera">
                <tr>
                    <th>NRO.: <?php echo $cabecera['ordcom_cod']; ?></th>
                    <th>USUARIO: <?php echo $cabecera['usu_login'] ?></th>
                    <th>SUCURSAL: <?php echo $cabecera['suc_descri'] ?></th>
                    <th>PEDIDO NRO.: <?php echo $cabecera['presprov_cod'] ?></th>
                    <th colspan="2">PROVEEDOR: <?php echo $cabecera['pro_razonsocial']; ?></th>
                </tr>
                <tr>
                    <th>FECHA: <?php echo $cabecera['ordcom_fecha']; ?></th>
                    <th>CONDICION: <?php echo $cabecera['ordcom_condicionpago']; ?></th>
                    <th>CUOTAS: <?php echo $cabecera['ordcom_cuota']; ?></th>
                    <th>INTÉRVALO: <?php echo $cabecera['ordcom_intefecha']; ?></th>
                    <th>ESTADO: <?php echo $cabecera['ordcom_estado'] ?></th>
                </tr>
            </thead>
            <tbody>
                <tr class="detalle">
                    <td>ITEM</td>
                    <td>CANTIDAD</td>
                    <td>PRECIO UNIT.</td>
                    <td>EXCENTA</td>
                    <td>IVA 5%</td>
                    <td>IVA 10%</td>
                </tr>
                <?php 
                    $sql2 = "select * from v_orden_compra_det
                            where ordcom_cod = {$cabecera['ordcom_cod']};";
                    $resultado2 = pg_query($conexion, $sql2);
                    $datos2 = pg_fetch_all($resultado2);
                ?>
                <?php 
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
                    $totalExe += floatval ($detalle['excenta']);
                    $totalI5 += floatval ($detalle['iva5']);
                    $totalI10 += floatval ($impuesto10);
                    ?>
                    <tr class = "cuerpo">
                        <td>
                            <?php echo $detalle['itm_descri']; ?>
                        </td>
                        <td>
                            <?php echo $detalle['ordcomdet_cantidad']; ?>
                        </td>
                        <td>
                            <?php echo $detalle['ordcomdet_precio']; ?>
                        </td>
                        <td>
                            <?php echo $detalle['excenta']; ?>
                        </td>
                        <td>
                            <?php echo $detalle['iva5']; ?>
                        </td>
                        <td>
                            <?php echo $impuesto10 ?>
                        </td>
                    </tr>

                    <?php
                    $discrIva5 = floatval ($totalI5/21);
                    $discrIva5_format = number_format($discrIva5, 2);

                    $discrIva10 = floatval ($totalI10/11);
                    $discrIva10_format = number_format($discrIva10, 2);
                    }
                    
                    $totalIva = ($discrIva5 + $discrIva10);                  
                    $totalIva_format = number_format($totalIva, 2);

                    $totalGral = ($totalExe + $totalI5 + $totalI10);?>

                    <tr id="subtotal">
                        <td colspan='3' style='font-weight: bold;'>SUBTOTAL: </td>
                        <td style='font-weight: bold;'> <?php echo $totalExe?> </td>
                        <td style='font-weight: bold;'> <?php echo $totalI5?> </td>
                        <td style='font-weight: bold;'> <?php echo $totalI10?> </td>
                    </tr>
                    <tr id = "total">
                        <td colspan='5' style='font-weight: bold;'>TOTAL A PAGAR: </td>
                        <td style='font-weight: bold;'> <?php echo $totalGral?> </td>
                    </tr>
                    <tr id = "impuesto">
                        <td style='font-weight: bold;'>IVA 5%: <?php echo $discrIva5_format?> </td>
                        <td colspan='3' style='font-weight: bold;'>IVA 10%: <?php echo $discrIva10_format?> </td>
                        <td colspan='2' style='font-weight: bold;'>TOTAL IVA: <?php echo $totalIva_format?> </td>
                    </tr>
            </tbody>
        </table>
    <?php } ?>

    <div class="usuario">
        <table class ="grilla2">
            <tbody>
                <tr>
                    <td class="grilla2">
                        <div class="item">
                            <span class="label">EMITIDO POR:</span>
                            <span class="valor">
                                <?php echo $usuario; ?>
                            </span>
                        </div>
                        <div class="item">
                            <span class="label">PERFIL:</span>
                            <span class="valor">
                                <?php echo $perfil; ?>
                            </span>
                        </div>
                    </td>
                    <td class="grilla2">
                        <div class="item">
                            <span class="label">MÓDULO:</span>
                            <span class="valor">
                                <?php echo $modulo; ?>
                            </span>
                        </div>
                        <div class="item">
                            <span class="label">FECHA:</span>
                            <span class="valor">
                                <?php echo $fechaActual; ?>
                            </span>
                        </div>
                    </td>
                </tr>
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

// Generar el archivo PDF y guardarlo en el servidor o descargarlo
$dompdf->stream('reporte_presupuestos_prov.pdf', array("Attachment" => false));
?>