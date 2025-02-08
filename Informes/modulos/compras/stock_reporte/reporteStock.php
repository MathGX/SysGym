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
    <title>Reporte stock</title>
</head>
<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$deposito = $_GET['deposito'];


$sql = "select 
        i.itm_cod,
        i.itm_descri,
        s.sto_cantidad||' '||um.uni_descri as cant, 
        d.dep_descri
        from stock s
        join unidad_medida um on um.uni_cod = s.uni_cod
        join items i on i.itm_cod = s.itm_cod and i.tipitem_cod = s.tipitem_cod 
            join tipo_item ti on ti.tipitem_cod = i.tipitem_cod 
        join depositos d on d.dep_cod = s.dep_cod and d.suc_cod = s.suc_cod and d.emp_cod = s.emp_cod 
            join sucursales s2 on s2.suc_cod = d.suc_cod and s2.emp_cod = d.emp_cod 
                join empresa e on e.emp_cod = s2.emp_cod 
        where d.dep_descri ilike '%$deposito%'
        order by i.itm_cod;";

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
            width: 50PX;
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

<h2>STOCK DE ITEMS</h2>
    <table class="grilla">
        <thead>
            <tr>
                <th>N°</th>
                <th>ITEM</th>
                <th>CANTIDAD</th>
                <th>DEPOSITO</th>
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
                        <?php echo $fila['cant'] ?>
                    </td>
                    <td>
                        <?php echo $fila['dep_descri'] ?>
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
$dompdf->stream('reporte_stock.pdf', array("Attachment" => false));
?>