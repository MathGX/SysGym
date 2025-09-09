<?php
// Establecemos el retorno del documento en formato json
header("Content-type: application/json; charset=utf-8");

// Conexion
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/vendor/autoload.php"; // PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
// Instancia de conexión
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

// Validamos archivo
if (!isset($_FILES['archExcel'])) {
    echo json_encode(
        ["tipo" => "error", 
        "mensaje" => "No se recibió el archivo"
    ]);
    exit;
}

$tmp = $_FILES['archExcel']['tmp_name'];

$spreadsheet = IOFactory::load($tmp);
$sheet = $spreadsheet->getActiveSheet();

//datos del proveedor
$sqlProv = "select 
                pro_cod,
                tiprov_cod
            from proveedor 
            where pro_ruc = '".$sheet->getCell("B3")->getValue()."'
            limit 1";
$resProv = pg_query($conexion, $sqlProv);
$datosProv = pg_fetch_assoc($resProv);

// ================= CABECERA =================
$fechaExcelEmision = $sheet->getCell("D3")->getValue();
if (is_numeric($fechaExcelEmision)) {
    $fechaEmision = Date::excelToDateTimeObject($fechaExcelEmision);
    $presprov_fecha = $fechaEmision->format('Y-m-d');
} else {
    $presprov_fecha = $_POST['presprov_fecha']; // Fallback al POST si no es numérico
}

// Obtener y convertir fecha de vencimiento
$fechaExcelVencimiento = $sheet->getCell("F3")->getValue();
if (is_numeric($fechaExcelVencimiento)) {
    $fechaVencimiento = Date::excelToDateTimeObject($fechaExcelVencimiento);
    $presprov_fechavenci = $fechaVencimiento->format('Y-m-d');
} else {
    $presprov_fechavenci = null; // o valor por defecto apropiado
}

$pedcom_cod      = (int)$sheet->getCell("B2")->getFormattedValue();
$pro_ruc         = $sheet->getCell("B3")->getValue();
$pro_razonsocial = pg_escape_string($conexion, $sheet->getCell("D2")->getValue());
$pro_cod         = $datosProv['pro_cod'];
$tiprov_cod      = $datosProv['tiprov_cod'];

//escapar los datos para que acepte comillas simples
$presprov_estado = pg_escape_string($conexion, $_POST['presprov_estado']);
$suc_descri = pg_escape_string($conexion, $_POST['suc_descri']);
$emp_razonsocial = pg_escape_string($conexion, $_POST['emp_razonsocial']);
$usu_login = pg_escape_string($conexion, $_POST['usu_login']);

$sqlCab = "select sp_presupuesto_prov_cab(
    {$_POST['presprov_cod']},
    '$presprov_fecha',
    '$presprov_fechavenci',
    '$presprov_estado',
    $pro_cod,
    $tiprov_cod,
    {$_POST['suc_cod']},
    {$_POST['emp_cod']},
    {$_POST['usu_cod']},
    $pedcom_cod,
    {$_POST['operacion_cab']},
    '$pro_ruc',
    '$pro_razonsocial',
    '$suc_descri',
    '$emp_razonsocial',
    '$usu_login',
    '{$_POST['transaccion']}'
);";

pg_query($conexion, $sqlCab);
$errorCab = pg_last_error($conexion);
//Si ocurre un error lo capturamos y lo enviamos al front-end
if (strpos($errorCab, "fecha") !== false) {
    $response = array(
        "mensaje" => "LA FECHA DE VENCIMIENTO NO PUEDE SER INFERIOR A LA FECHA DE EMISION",
        "tipo" => "error"
    );
} else if (strpos($errorCab, "pedido") !== false) {
    $response = array(
        "mensaje" => "EL PROVEEDOR SELECCIONADO YA CUENTA CON UN PRESUPUESTO PARA EL PEDIDO DESIGNADO",
        "tipo" => "error"
    );
} else if (strpos($errorCab, "err_cab") !== false) {
    $response = array(
        "mensaje" => "EL ESTADO DEL PRESUPUESTO IMPIDE QUE SEA ANULADO, SE ENCUENTRA ASOCIADO A UNA ORDEN",
        "tipo" => "error"
    );
} else {
    $mensajeFinal = pg_last_notice($conexion);

    // ================= DETALLE =================

    // Cabecera correcta, inicializar acumulador de errores del detalle
    $erroresDetalle = [];

    $fila = 6;
    while ($sheet->getCell("A".$fila)->getValue() != "") {

        $sqlTipo = "select tipitem_cod, itm_descri from items 
                where itm_cod = cast(".$sheet->getCell("A".$fila)->getValue()." as integer);";
        
        $resTipo = pg_query($conexion, $sqlTipo);
        $datosTipo = pg_fetch_assoc($resTipo);

        $itm_cod  = (int)$sheet->getCell("A".$fila)->getValue();
        $presprovdet_cantidad = str_replace(",", ".", $sheet->getCell("C".$fila)->getValue());
        $presprovdet_precio   = str_replace(",", ".", $sheet->getCell("D".$fila)->getValue());

        $sqlDet = "select sp_presupuesto_prov_det(
            $itm_cod,
            {$datosTipo['tipitem_cod']},
            {$_POST['presprov_cod']},
            $presprovdet_cantidad,
            $presprovdet_precio,
            {$_POST['operacion_cab']}
        );";

        pg_query($conexion, $sqlDet);
        $errorDet = pg_last_error($conexion);
        //Si ocurre un error lo capturamos y lo enviamos al front-end
        if (strpos($errorDet, "err_rep") !== false) {
            // Acumular error de item repetido
            $erroresDetalle[] = "EL ITEM " . $datosTipo['itm_descri'] . " ESTÁ DUPLICADO EN EL ARCHIVO";
        }

        $fila++;
    }

    // Preparar mensaje final
    if (count($erroresDetalle) > 0) {
        $mensajes = [];
        $mensajes[] = $mensajeFinal; // lo que venía de la cabecera / last_notice
        $mensajes[] = "ERRORES EN DETALLE:";
        foreach ($erroresDetalle as $err) {
            $mensajes[] = $err;
        }
        $responseTipo = "warning";
    } else {
        $mensajes = [$mensajeFinal];
        $responseTipo = "success";
    }

    $response = [
        "mensajes" => $mensajes,
        "tipo" => $responseTipo
    ];

}
echo json_encode($response);

?>
