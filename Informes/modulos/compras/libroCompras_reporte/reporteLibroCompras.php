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
    <title>Reporte libro de compras</title>
</head>
<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$pro_cod = $_GET['pro_cod'];
$tipcomp_cod = $_GET['tipcomp_cod'];


$sql = "select
            lc.libcom_fecha,
            p.pro_ruc,
            p.pro_razonsocial,
            tc.tipcomp_descri,
            lc.libcom_nro_comprobante,
            (lc.libcom_exenta+lc.libcom_iva5+lc.libcom_iva10) facturado,
            round(lc.libcom_iva10/1.1) gravada10,
            round(lc.libcom_iva10/11) cf10,
            round(lc.libcom_iva5/1.05) gravada5,
            round(lc.libcom_iva5/21) cf5,
            lc.libcom_exenta 
        from libro_compras lc 
            join 	(select cc.com_cod, cc.pro_cod, cc.com_nrofac comprobante, cc.tipcomp_cod
                    from compra_cab cc 
                    union all 
                    select ncc.com_cod, ncc.pro_cod, ncc.notacom_nronota, ncc.tipcomp_cod
                    from nota_compra_cab ncc) c on lc.com_cod = c.com_cod and lc.libcom_nro_comprobante = comprobante and lc.tipcomp_cod = c.tipcomp_cod
                join proveedor p on p.pro_cod = c.pro_cod
            join tipo_comprobante tc on tc.tipcomp_cod = lc.tipcomp_cod
        where lc.libcom_fecha between '$desde' and '$hasta'";

//En caso de que el proveedor no esté vacío
if (!empty($pro_cod)) {
    $sql .= " and p.pro_cod = $pro_cod";
}
//En caso de que el tipo de comprobante no esté vacío
if (!empty($tipcomp_cod)) {
    $sql .= "lc.tipcomp_cod = $tipcomp_cod";
}
$resultado = pg_query($conexion, $sql);
$datos = pg_fetch_all($resultado);


?>

<body>
    <style>
        .grilla {
            width: 100%;
            font-size: 10px;
            border-collapse: collapse;
        }

        th,td {
            width: auto;
            padding: 8px;
            border: 1px solid black;
        }

        th {
            background-color: lightblue;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
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
            padding: 8px;
            border: 0px;
        }
    </style>

    <table class="grilla">
    <h1>LIBRO DE COMPRAS</h1>
        <thead>
            <tr>
                <th>N°</th>
                <th>FECHA</th>
                <th>MANDANTE</th>
                <th>RUC</th>
                <th>FACTURA N°</th>
                <th>IVA 5%</th>
                <th>IVA 10%</th>
                <th>EXCENTA</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datos as $fila) { ?>
                <tr>
                    <td>
                        <?php echo $fila['libcom_cod']; ?>
                    </td>
                    <td>
                        <?php echo $fila['libcom_fecha']; ?>
                    </td>
                    <td>
                        <?php echo $fila['pro_razonsocial'] ?>
                    </td>
                    <td>
                        <?php echo $fila['pro_ruc'] ?>
                    </td>
                    <td>
                        <?php echo $fila['libcom_numfactura'] ?>
                    </td>
                    <td>
                        <?php echo $fila['libcom_iva5'] ?>
                    </td>
                    <td>
                        <?php echo $fila['libcom_iva10'] ?>
                    </td>
                    <td>
                        <?php echo $fila['libcom_excenta'] ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

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
                </thead>
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
$dompdf->stream('reporte_libro_compras.pdf', array("Attachment" => false));
?>