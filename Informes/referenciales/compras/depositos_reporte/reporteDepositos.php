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
    <title>Reporte depositos</title>
</head>
<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$desde = $_GET['desde'];
$hasta = $_GET['hasta'];


$sql = "select 
        d.*,
        e.emp_razonsocial,
        s.suc_descri,
        c.ciu_descripcion 
        from depositos d
        join ciudad c on c.ciu_cod = d.ciu_cod
        join sucursales s on s.suc_cod = d.suc_cod and s.emp_cod = d.emp_cod 
            join empresa e on e.emp_cod = s.emp_cod 
        where dep_cod between $desde and $hasta
        order by dep_cod;";

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
        <thead>
            <tr>
                <th>CODIGO</th>
                <th>EMPRESA</th>
                <th>SUCURSAL</th>
                <th>DEPOSITO</th>
                <th>CIUDAD</th>
                <th>ESTADO</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datos as $fila) { ?>
                <tr>
                    <td>
                        <?php echo $fila['dep_cod']; ?>
                    </td>
                    <td>
                        <?php echo $fila['emp_razonsocial']; ?>
                    </td>
                    <td>
                        <?php echo $fila['suc_descri'] ?>
                    </td>
                    <td>
                        <?php echo $fila['dep_descri'] ?>
                    </td>
                    <td>
                        <?php echo $fila['ciu_descripcion'] ?>
                    </td>
                    <td>
                        <?php echo $fila['dep_estado'] ?>
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
$dompdf->stream('reporte_depositos.pdf', array("Attachment" => false));
?>