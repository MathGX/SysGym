<?php
session_start();
$usuario = $_SESSION['usuarios']['per_nombres'] . " " . $_SESSION['usuarios']['per_apellidos']; 
$perfil = $_SESSION['usuarios']['perf_descri'];
$modulo = $_SESSION['usuarios']['mod_descri'];

$fechaActual = date('d/m/Y');

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
            row_number() over (order by lc.libcom_fecha, lc.libcom_cod) id,
            to_char(lc.libcom_fecha, 'dd/mm/yyyy') libcom_fecha,
            p.pro_ruc,
            p.pro_razonsocial,
            tc.tipcomp_descri,
            lc.libcom_nro_comprobante,
            (lc.libcom_exenta+lc.libcom_iva5+lc.libcom_iva10) facturado,
            round(lc.libcom_iva10/1.1) grav10,
            round(lc.libcom_iva10/11) cf10,
            round(lc.libcom_iva5/1.05) grav5,
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
        where lc.libcom_estado = 'ACTIVO'
            and lc.libcom_fecha between '$desde' and '$hasta'";

//En caso de que el proveedor no esté vacío
if (!empty($pro_cod)) {
    $sql .= " and p.pro_cod = $pro_cod";
}
//En caso de que el tipo de comprobante no esté vacío
if (!empty($tipcomp_cod)) {
    $sql .= "and lc.tipcomp_cod = $tipcomp_cod";
}
$resultado = pg_query($conexion, $sql);
$datos = pg_fetch_all($resultado);

//-------------------------------------------------------------SQL DE SUMA-------------------------------------------------------------
$sqlSuma = "select
            coalesce(sum(lc.libcom_exenta+lc.libcom_iva5+lc.libcom_iva10),0) sum_facturado,
            coalesce(sum(round(lc.libcom_iva10/1.1)),0) sum_grav10,
            coalesce(sum(round(lc.libcom_iva10/11)),0) sum_cf10,
            coalesce(sum(round(lc.libcom_iva5/1.05)),0) sum_grav5,
            coalesce(sum(round(lc.libcom_iva5/21)),0) sum_cf5,
            coalesce(sum(lc.libcom_exenta),0) sum_exenta,
            upper(to_char('$desde'::date, 'TMMonth')||' '||extract(year from '$desde'::date)) periodo
        from libro_compras lc 
            join 	(select cc.com_cod, cc.pro_cod, cc.com_nrofac comprobante, cc.tipcomp_cod
                    from compra_cab cc 
                    union all 
                    select ncc.com_cod, ncc.pro_cod, ncc.notacom_nronota, ncc.tipcomp_cod
                    from nota_compra_cab ncc) c on lc.com_cod = c.com_cod and lc.libcom_nro_comprobante = comprobante and lc.tipcomp_cod = c.tipcomp_cod
                join proveedor p on p.pro_cod = c.pro_cod
            join tipo_comprobante tc on tc.tipcomp_cod = lc.tipcomp_cod 
        where lc.libcom_estado = 'ACTIVO'
            and lc.libcom_fecha between '$desde' and '$hasta'";

//En caso de que el proveedor no esté vacío
if (!empty($pro_cod)) {
    $sql .= " and p.pro_cod = $pro_cod";
}
//En caso de que el tipo de comprobante no esté vacío
if (!empty($tipcomp_cod)) {
    $sql .= "and lc.tipcomp_cod = $tipcomp_cod";
}
$resultadoSuma = pg_query($conexion, $sqlSuma);
$datoSuma = pg_fetch_assoc($resultadoSuma);

?>

<body>
    <style>
        .grilla {
            width: 100%;
            font-size: 10px;
            border-collapse: collapse;
            
        }

        th,td {
            padding: 2px;
            border: 1px solid black;
            font-family: Arial, sans-serif; 
            font-size: 8px;
        }

        th {
            background-color: #d3d3d3;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        td {
            white-space: nowrap;
        }

        .titulo {
            font-family: Arial, sans-serif; 
            font-size: 8px; 
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
        <div style="text-align: center; font-weight: bold; font-size: 10px; margin-bottom: 12px; color: #000000ff;">
            Libro de Compras
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 16px; font-size: 10px; color: #333;">
            <div style="line-height: 1.4;">
                <div><strong>Razón Social:</strong> <?php echo $_SESSION['usuarios']['emp_razonsocial']?></div>
                <div><strong>RUC:</strong> 80012345-6</div>
            </div>
            <div style="line-height: 1.4;">
                <div><strong>Periodo:</strong> <?php echo $datoSuma['periodo']?></div>
                <div><strong>Fecha de Emisión:</strong> <?php echo $fechaActual?></div>
                <div><strong>Emitido por:</strong> <?php echo $usuario?></div>
            </div>
        </div>
    </div>

    <table class="grilla">
        <thead>
            <tr>
                <th style="width: 2%;" rowspan="3">N°</th>
                <th rowspan="3">FECHA</th>
                <th rowspan="3">RUC</th>
                <th style="width: 30%;" rowspan="3">PROVEEDOR</th>
                <th colspan="2">DOCUMENTO</th>
                <th rowspan="3">IMPORTE FACTURADO</th>
                <th colspan="4">TASA DEL IVA</th>
                <th rowspan="3">EXENTA</th>
            </tr>
            <tr>
                <th rowspan="2">TIPO</th>
                <th rowspan="2">N°</th>
                <th colspan="2">10%</th>
                <th colspan="2">5%</th>
            </tr>
            <tr>
                <th>GRAVADA</th>
                <th>CREDITO FISCAL</th>
                <th>GRAVADA</th>
                <th>CREDITO FISCAL</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datos as $fila) { ?>
                <tr>
                    <td style="text-align:right; width: 2%;">
                        <?php echo number_format($fila['id']);?>
                    </td>
                    <td style="text-align:right;">
                        <?php echo $fila['libcom_fecha']; ?>
                    </td>
                    <td>
                        <?php echo $fila['pro_ruc'] ?>
                    </td>
                    <td>
                        <?php echo $fila['pro_razonsocial'] ?>
                    </td>
                    <td>
                        <?php echo $fila['tipcomp_descri'] ?>
                    </td>
                    <td>
                        <?php echo $fila['libcom_nro_comprobante'] ?>
                    </td>
                    <td style="text-align:right;">
                        <?php echo number_format($fila['facturado'],0, ',', '.') ?>
                    </td>
                    <td style="text-align:right;">
                        <?php echo number_format($fila['grav10'], 0, '', '.') ?>
                    </td>
                    <td style="text-align:right;">
                        <?php echo number_format($fila['cf10'], 0, '', '.') ?>
                    </td>
                    <td style="text-align:right;">
                        <?php echo number_format($fila['grav5'], 0, '', '.') ?>
                    </td>
                    <td style="text-align:right;">
                        <?php echo number_format($fila['cf5'], 0, '', '.') ?>
                    </td>
                    <td style="text-align:right;">
                        <?php echo number_format($fila['libcom_exenta'], 0, '', '.') ?>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <th style="text-align:center;" colspan="6">
                    <strong>TOTALES</strong></th>
                </th>
                <th style="text-align:right;">
                    <?php echo number_format($datoSuma['sum_facturado'],0, ',', '.') ?>
                </th>
                <th style="text-align:right;">
                    <?php echo number_format($datoSuma['sum_grav10'], 0, '', '.') ?>
                </th>
                <th style="text-align:right;">
                    <?php echo number_format($datoSuma['sum_cf10'], 0, '', '.') ?>
                </th>
                <th style="text-align:right;">
                    <?php echo number_format($datoSuma['sum_grav5'], 0, '', '.') ?>
                </th>
                <th style="text-align:right;">
                    <?php echo number_format($datoSuma['sum_cf5'], 0, '', '.') ?>
                </th>
                <th style="text-align:right;">
                    <?php echo number_format($datoSuma['sum_exenta'], 0, '', '.') ?>
                </th>
            </tr>
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
$dompdf->stream('reporte_libro_compras.pdf', array("Attachment" => false));
?>