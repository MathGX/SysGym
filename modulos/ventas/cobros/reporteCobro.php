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
    <title>Cobro</title>
</head>
<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$cobr_cod = $_GET['cobr_cod'];


$sql = "select * from v_cobros_cab
        where cobr_cod = $cobr_cod;";

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

        .cabecera {
            border: 2px solid black;
            font-size: 15px;
        }

        .cabecera th{
            text-align: left;
            font-weight: bold;
            padding: 17px;
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

        .cuerpo, td {
            padding: 8px;
            border: 1px solid black;
            text-align: center;
            font-size: 10px;
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

<h1 style='text-align: center;'>COBRO</h1>
    <?php foreach ($datos as $cabecera) { ?>
    <table class="grilla">
            <thead class="cabecera">
                <tr>
                    <th colspan="6" style = "text-align: center; font-size: 20px"><?php echo $cabecera['emp_razonsocial']; ?></th>
                </tr>
                <tr>
                    <th>NRO.: <?php echo $cabecera['cobr_cod']; ?></th>
                    <th colspan="5">FECHA Y HORA: <?php echo $cabecera['cobr_fecha']; ?></th>
                </tr>
                <tr>
                    <th>USUARIO: <?php echo $cabecera['usu_login'] ?></th>
                    <th colspan="3">SUCURSAL: <?php echo $cabecera['suc_descri'] ?></th>
                    <th colspan="2">ESTADO: <?php echo $cabecera['cobr_estado'] ?></th>
                </tr>
            </thead>
            <tbody>
                <tr class="detalle">
                    <td>CLIENTE</td>
                    <td>FACTURA</td>
                    <td>EFECTIVO</td>
                    <td>CHEQUE</td>
                    <td>TARJETA</td>
                    <td>TOTAL</td>
                </tr>
                <?php 
                    $cab = $cabecera['cobr_cod'];
                    $sql2 = "select
                    vc.ven_nrofac,
                    p.per_nombres ||' '||p.per_apellidos as cliente,
                    sum(cd.cobrdet_monto) as cobrdet_monto,
                    sum((case when cd.forcob_cod = 1 then cd.cobrdet_monto else 0 end)) as cheque,
                    sum((case when cd.forcob_cod = 2 then cd.cobrdet_monto else 0 end)) as efectivo,
                    sum((case when cd.forcob_cod = 3 then cd.cobrdet_monto else 0 end)) as tarjeta
                    from cobros_det cd
                        join cobros_cab cc on cd.cobr_cod = cc.cobr_cod
                        join cuentas_cobrar cc2 on cc2.ven_cod = cd.ven_cod 
                            join ventas_cab vc on vc.ven_cod = cc2.ven_cod 
                                join clientes c on c.cli_cod = vc.cli_cod 
                                    join personas p on p.per_cod = c.per_cod
                    where cc.cobr_cod = $cab
                    and cc.cobr_estado ilike 'ACTIVO'
                    group by ven_nrofac, per_nombres, per_apellidos;";
                    $resultado2 = pg_query($conexion, $sql2);
                    $datos2 = pg_fetch_all($resultado2);
                ?>
                <?php foreach ($datos2 as $detalle) { ?>
                    <tr class = "cuerpo">
                        <td>
                            <?php if (isset( $detalle['cliente'])) { 
                                echo number_format($detalle['cliente']); 
                            } else {
                                echo '-';
                            }?>
                        </td>
                        <td>
                            <?php if (isset( $detalle['ven_nrofac'])) { 
                                echo number_format($detalle['ven_nrofac ']); 
                            } else {
                                echo '-';
                            }?>
                        </td>
                        <td>
                            <?php if (isset( $detalle['efectivo'])) { 
                                echo number_format($detalle['efectivo']); 
                            } else {
                                echo '-';
                            }?>
                        </td>
                        <td>
                            <?php if (isset( $detalle['cheque'])) { 
                                echo number_format($detalle['cheque']); 
                            } else {
                                echo '-';
                            }?>
                        </td>
                        <td>
                            <?php if (isset( $detalle['tarjeta'])) { 
                                echo number_format($detalle['tarjeta']); 
                            } else {
                                echo '-';
                            }?>
                        </td>
                        <td>
                            <?php if (isset( $detalle['cobrdet_monto'])) { 
                                echo number_format($detalle['cobrdet_monto']); 
                            } else {
                                echo '-';
                            }?>
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
$dompdf->setPaper('A4', 'portrait');

// Renderizar el contenido HTML a PDF
$dompdf->render();

// Generar el archivo PDF y guardarlo en el servidor o descargarlo
$dompdf->stream('cobros.pdf', array("Attachment" => false));
?>