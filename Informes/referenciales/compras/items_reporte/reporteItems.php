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
    <title>Reporte items</title>
</head>
<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$tipitem_cod = $_GET['tipitem_cod'];
$tipimp_cod = $_GET['tipimp_cod'];
$uni_cod = $_GET['uni_cod'];


$sql = "select 
            i.*,
            ti.tipitem_descri,
            ti2.tipimp_descri,
            u.uni_descri
        from items i
            join tipo_item ti on ti.tipitem_cod = i.tipitem_cod 
            join tipo_impuesto ti2 on ti2.tipimp_cod = i.tipimp_cod 
            join unidad_medida u on u.uni_cod = i.uni_cod
        where 1=1";

//En caso de que el desde y/o hasta no estén vacío
if (!empty($desde) && !empty($hasta)) {
    $sql .= " and i.itm_cod between $desde and $hasta";
} else if (!empty($desde)) {
    $sql .= " and i.itm_cod >= $desde";
} else if (!empty($hasta)) {
    $sql .= " and i.itm_cod <= $hasta";
}
//En caso de que el tipo de tipo de item no esté vacío
if (!empty($tipitem_cod)) {
    $sql .= " and i.tipitem_cod = $tipitem_cod";
}
//En caso de que el tipo de tipo de impuesto no esté vacío
if (!empty($tipimp_cod)) {
    $sql .= " and i.tipimp_cod = $tipimp_cod";
}//En caso de que el tipo de unidad de medida no esté vacío
if (!empty($uni_cod)) {
    $sql .= " and i.uni_cod = $uni_cod";
}
//Se ordena la consulta
$sql .= " order by i.itm_cod;";

$resultado = pg_query($conexion, $sql);
$datos = pg_fetch_all($resultado);


?>

<body>

    <style>
        .grilla {
            width: 100%;
            font-size: 12px;
            border-collapse: collapse;
            
        }

        th,td {
            padding: 5px;
            border: 1px solid black;
            font-family: Arial, sans-serif; 
            font-size: 12px;
        }

        th {
            background-color: #d3d3d3;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        td {
            white-space: normal;
        }

        .titulo {
            font-family: Arial, sans-serif; 
            font-size: 12px; 
            max-width: 700px; 
            margin: 0 auto 20px auto; 
            padding: 15px; 
            border: 1px solid #ccc; 
            border-radius: 8px;
            background-color: #f9f9f9; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        }
    </style>

    <div class="titulo">
        <div style="text-align: center; font-weight: bold; font-size: 12px; margin-bottom: 12px; color: #000000ff;">
            Listado de Items
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 16px; font-size: 12px; color: #333;">
            <div style="line-height: 1.4;">
                <div><strong>Razón Social:</strong> <?php echo $_SESSION['usuarios']['emp_razonsocial']?></div>
                <div><strong>RUC:</strong> 80012345-6</div>
            </div>
            <div style="line-height: 1.4;">
                <div><strong>Fecha de Emisión:</strong> <?php echo $fechaActual?></div>
                <div><strong>Emitido por:</strong> <?php echo $usuario?></div>
            </div>
        </div>
    </div>

    <table class="grilla">
        <thead>
            <tr>
                <th>CODIGO</th>
                <th>ITEM</th>
                <th>UNIDAD DE MEDIDA</th>
                <th>TIPO DE ITEM</th>
                <th>COSTO</th>
                <th>PRECIO</th>
                <th>IMPUESTO</th>
                <th>STOCK MIN</th>
                <th>STOCK MAX</th>
                <th>ESTADO</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datos as $fila) { ?>
                <tr>
                    <td>
                        <?php echo $fila['itm_cod']; ?>
                    </td>
                    <td>
                        <?php echo $fila['itm_descri']; ?>
                    </td>
                    <td>
                        <?php echo $fila['uni_descri']; ?>
                    </td>
                    <td>
                        <?php echo $fila['tipitem_descri']; ?>
                    </td>
                    <td style="text-align:right;">
                        <?php echo number_format($fila['itm_costo'], 0, ',','.'); ?>
                    </td>
                    <td style="text-align:right;">
                        <?php echo number_format($fila['itm_precio'], 0, ',','.'); ?>
                    </td>
                    <td>
                        <?php echo $fila['tipimp_descri']; ?>
                    </td>
                    <td style="text-align:right;">
                        <?php echo number_format($fila['itm_stock_min'], 0, ',','.'); ?>
                    </td>
                    <td style="text-align:right;">
                        <?php echo number_format($fila['itm_stock_max'], 0, ',','.'); ?>
                    </td>
                    <td>
                        <?php echo $fila['itm_estado'] ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

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
$dompdf->stream('reporte_item.pdf', array("Attachment" => false));
?>