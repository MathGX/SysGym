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
    <title>Reporte de presupuesto de proveedores</title>
</head>
<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$desde = $_GET['desde'];
$hasta = $_GET['hasta'];


$sql = "select * from v_presupuesto_prov_cab
        where presprov_fecha between '$desde' and '$hasta';";
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
        }
        
        .cuerpo:nth-child(even) {
            background-color: #f9f9f9;
        }

        .cuerpo, td {
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

<h2>PRESUPUESTOS DE PROVEEDORES </h2>
    <?php foreach ($datos as $cabecera) { ?>
        <table class="grilla">
            <thead class="cabecera">
                <tr>
                    <th>NRO.: <?php echo $cabecera['presprov_cod']; ?></th>
                    <th>USUARIO: <?php echo $cabecera['usu_login'] ?></th>
                    <th>SUCURSAL: <?php echo $cabecera['suc_descri'] ?></th>
                    <th>PEDIDO NRO.: <?php echo $cabecera['pedcom_cod'] ?></th>
                </tr>
                <tr>
                    <th>PROVEEDOR: <?php echo $cabecera['pro_razonsocial']; ?></th>
                    <th>FECHA: <?php echo $cabecera['presprov_fecha']; ?></th>
                    <th>VENCIMIENTO: <?php echo $cabecera['presprov_fechavenci']; ?></th>
                    <th>ESTADO: <?php echo $cabecera['presprov_estado'] ?></th>
                </tr>
            </thead>
            <tbody>
                <tr class="detalle">
                    <td>ITEM</td>
                    <td>CANTIDAD</td>
                    <td>PRECIO</td>
                    <td>TOTAL</td>
                </tr>
                <?php 
                    $sql2 = "select * from v_presupuesto_prov_det
                            where presprov_cod = {$cabecera['presprov_cod']};";
                    $resultado2 = pg_query($conexion, $sql2);
                    $datos2 = pg_fetch_all($resultado2);
                ?>
                <?php foreach ($datos2 as $detalle) { 
                    $totalGral = 0;
                    if ($detalle['tipitem_cod'] == 1) {
                        $totalGral = $detalle['presprovdet_precio'];
                    } else {
                        $totalGral = $detalle['total'];
                    }
                    ?>
                    <tr class = "cuerpo">
                        <td>
                            <?php echo $detalle['itm_descri']; ?>
                        </td>
                        <td>
                            <?php echo $detalle['presprovdet_cantidad']; ?>
                        </td>
                        <td>
                            <?php echo $detalle['presprovdet_precio']; ?>
                        </td>
                        <td>
                            <?php echo $totalGral ?>
                        </td>
                    </tr>
                <?php } ?>
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