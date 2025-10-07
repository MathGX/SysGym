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
    <title>Reporte proveedores</title>
</head>
<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$tiprov_cod = $_GET['tiprov_cod'];

$sql = "select 
            p.*,
            tp.tiprov_descripcion
        from proveedor p
            join tipo_proveedor tp on tp.tiprov_cod = p.tiprov_cod
        where 1 = 1";

//En caso de que el desde y/o hasta no estén vacío
if (!empty($desde) && !empty($hasta)) {
    $sql .= " and p.pro_cod between $desde and $hasta";
} else if (!empty($desde)) {
    $sql .= " and p.pro_cod >= $desde";
} else if (!empty($hasta)) {
    $sql .= " and p.pro_cod <= $hasta";
}
//En caso de que el tipo de ciudad no esté vacío
if (!empty($tiprov_cod)) {
    $sql .= " and p.tiprov_cod = $tiprov_cod";
}
//Se ordena la consulta
$sql .= " order by p.pro_cod;";

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
            Listado de Proveedores
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
                <th>PROVEEDOR</th>
                <th>RUC</th>
                <th>TIMBRADO</th>
                <th>VENC. TIMBRADO</th>
                <th>TELF</th>
                <th>E-MAIL</th>
                <th>DIRECCION</th>
                <th>TIPO DE PROVEEDOR</th>
                <th>ESTADO</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datos as $fila) { ?>
                <tr>
                    <td>
                        <?php echo $fila['pro_cod']; ?>
                    </td>
                    <td>
                        <?php echo $fila['pro_razonsocial']; ?>
                    </td>
                    <td style="white-space: nowrap;">
                        <?php echo $fila['pro_ruc'] ?>
                    </td>
                    <td>
                        <?php echo $fila['pro_timbrado'] ?>
                    </td>
                    <td>
                        <?php echo date("d/m/Y", strtotime($fila['pro_timb_fec_venc'])) ?>
                    </td>
                    <td>
                        <?php echo $fila['pro_telefono'] ?>
                    </td>
                    <td>
                        <?php echo $fila['pro_email'] ?>
                    </td>
                    <td>
                        <?php echo $fila['pro_direccion'] ?>
                    </td>
                    <td>
                        <?php echo $fila['tiprov_descripcion'] ?>
                    </td>
                    <td>
                        <?php echo $fila['pro_estado'] ?>
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
$dompdf->stream('reporte_proveedores.pdf', array("Attachment" => false));
?>