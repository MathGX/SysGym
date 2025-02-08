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
    <title>Reporte cuentas a pagar</title>
</head>
<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$desde = $_GET['desde'];
$hasta = $_GET['hasta'];


$sql = "select * from v_compra_cab
        where com_fecha between '$desde' and '$hasta';";
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

        .cuerpo, td {
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

<h2>CUENTAS A PAGAR</h2>
    <?php foreach ($datos as $cabecera) { ?>
        <table class="grilla">
            <thead class="cabecera">
                <tr>
                    <th>COMPRA NRO.: <?php echo $cabecera['com_cod']; ?></th>
                    <th>MANDANTE: <?php echo $cabecera['pro_razonsocial']; ?></th>
                    <th colspan="2">SUCURSAL: <?php echo $cabecera['suc_descri']; ?></th>
                </tr>
                <tr>
                    <th>FACTURA NRO.: <?php echo $cabecera['com_nrofac']; ?></th>
                    <th>TIPO DE COMPRA: <?php echo $cabecera['com_tipfac']; ?></th>
                    <th>INTÉRVALO: <?php echo $cabecera['com_intefecha']; ?></th>
                </tr>
            </thead>
            <tbody>
                <tr class="detalle">
                    <td>CUOTAS</td>
                    <td>TOTAL</td>
                    <td>SALDO</td>
                    <td>ESTADO</td>
                </tr>
                <?php 
                    $sql2 = "select * from cuentas_pagar cp
                            where cp.com_cod = {$cabecera['com_cod']};";
                    $resultado2 = pg_query($conexion, $sql2);
                    $datos2 = pg_fetch_all($resultado2);
                ?>
                <?php foreach ($datos2 as $detalle) { ?>
                    <tr class = "cuerpo">
                        <td>
                            <?php echo $detalle['cuenpag_cuotas'] ?>
                        </td>
                        <td>
                            <?php echo $detalle['cuenpag_montotal'] ?>
                        </td>
                        <td>
                            <?php echo $detalle['cuenpag_saldo'] ?>
                        </td>
                        <td>
                            <?php echo $detalle['cuenpag_estado'] ?>
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
$dompdf->setPaper('A5', 'landscape');

// Renderizar el contenido HTML a PDF
$dompdf->render();

// Generar el archivo PDF y guardarlo en el servidor o descargarlo
$dompdf->stream('reporte_pedidos_compra.pdf', array("Attachment" => false));
?>