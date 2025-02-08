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
    <title>Nota venta</title>
</head>
<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$notven_cod = $_GET['notven_cod'];


$sql = "select * from v_nota_venta_cab
        where notven_cod = $notven_cod;";

$resultado = pg_query($conexion, $sql);
$datos = pg_fetch_all($resultado);


?>

<body>

    <style>
        .grilla {
            page-break-inside: avoid;
            width: 100%;
            border-collapse: collapse;
        }

        .cabecera{
            border: 2px solid black;
            font-size: 15px;
        }

        .cabecera th{
            text-align: left;
            font-weight: bold;
            padding: 14px;
        }

        .detalle {
            background-color: lightblue;
            font-weight: bold;
            text-align: center;
            font-size: 13px;
        }
        
        .cuerpo:nth-child(even) {
            background-color: #f9f9f9;
            text-align: center;
        }

        .cuerpo, #subtotal, #total, #impuesto, td {
            padding: 8px;
            border: 1px solid black;
            text-align: left;
            font-size: 10px;
        }

        #subtotal, #total, #impuesto {
            background-color: lightgrey;
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

<h1 style='text-align: center;'>NOTA DE VENTA</h1>
    <?php foreach ($datos as $cabecera) { ?>
        <table class="grilla">
            <thead class="cabecera">
                <tr style="border: 2px solid black; font-size: 20px">
                    <th colspan="4" style = "text-align: center;"><?php echo $cabecera['emp_razonsocial']; ?></th>
                    <th colspan="2" style = "border: 2px solid black" >NOTA: <?php echo $cabecera['notven_nronota']; ?> </th>
                </tr>
                <tr style="border: 2px solid black;">
                    <th colspan="4"> <?php echo $cabecera['tipcomp_descri']; ?></th>
                    <th colspan="2"></th>
                </tr>
                <tr>
                    <th>RUC: <?php echo $cabecera['per_nrodoc']; ?></th>
                    <th colspan="5">CLIENTE: <?php echo $cabecera['cliente']; ?></th>
                </tr>
                <tr>
                    <th>FECHA: <?php echo date('d-m-Y', strtotime($cabecera['notven_fecha'])); ?></th>
                    <th colspan="3">CONCEPTO: <?php echo $cabecera['notven_concepto'];?></th>
                    <th colspan="2">FACTURA: <?php echo $cabecera['ven_nrofac'];?></th>
                </tr>
                <tr>
                    <th>USUARIO: <?php echo $cabecera['usu_login'] ?></th>
                    <th colspan="3">SUCURSAL: <?php echo $cabecera['suc_descri'] ?></th>
                    <th colspan="2">ESTADO: <?php echo $cabecera['notven_estado'] ?></th>
                </tr>
            </thead>
            <tbody>
                <tr class="detalle">
                    <td>ITEM</td>
                    <td>CANT.</td>
                    <td>PRECIO</td>
                    <td>EXENTA</td>
                    <td>IVA 5%</td>
                    <td>IVA 10%</td>
                </tr>
                <?php 
                    $cab = $cabecera['notven_cod'];
                    $sql2 = "select * from v_nota_venta_det
                    where notven_cod = $cab";
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
                        $impuesto10 = $detalle['notvendet_precio'];
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
                            <?php echo $detalle['notvendet_cantidad']; ?>
                        </td>
                        <td>
                            <?php echo number_format($detalle['notvendet_precio']); ?>
                        </td>
                        <td>
                            <?php echo number_format($detalle['excenta']); ?>
                        </td>
                        <td>
                            <?php echo number_format($detalle['iva5']); ?>
                        </td>
                        <td>
                            <?php echo number_format($impuesto10); ?>
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
                        <td style='font-weight: bold;'> <?php echo number_format($totalExe);?> </td>
                        <td style='font-weight: bold;'> <?php echo number_format($totalI5);?> </td>
                        <td style='font-weight: bold;'> <?php echo number_format($totalI10);?> </td>
                    </tr>
                    <tr id = "total">
                        <td colspan='5' style='font-weight: bold;'>TOTAL A PAGAR: </td>
                        <td style='font-weight: bold;'> <?php echo number_format($totalGral);?> </td>
                    </tr>
                    <tr id = "impuesto">
                        <td colspan='2' style='font-weight: bold;'>IVA 5%: <?php if (isset($discrIva5_format)){ echo $discrIva5_format; } else {echo '0.00';}?></td>
                        <td colspan='2' style='font-weight: bold;'>IVA 10%: <?php if (isset($discrIva10_format)){ echo $discrIva10_format; } else {echo '0.00';}?> </td>
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
$dompdf->setPaper('A4', 'portrait');

// Renderizar el contenido HTML a PDF
$dompdf->render();

// Generar el archivo PDF y guardarlo en el servidor o descargarlo
$dompdf->stream('notas_ventas.pdf', array("Attachment" => false));
?>