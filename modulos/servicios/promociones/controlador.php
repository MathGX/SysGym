<?php
//Establecemos el retorno del documento en formato json
header("Content-type: application/json; charset=utf-8");
//Solicitamos la clase de Conexion
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

//Consultamos si existe la variable operacion
if (isset($_POST['operacion_cab'])) {

    //ESCAPAMOS LOS DATOS CAPTURADOS
    $pedven_estado = pg_escape_string($conexion, $_POST['pedven_estado']);
    $usu_login = pg_escape_string($conexion, $_POST['usu_login']);
    $suc_descri = pg_escape_string($conexion, $_POST['suc_descri']);
    $emp_razonsocial = pg_escape_string($conexion, $_POST['emp_razonsocial']);
    $cliente = pg_escape_string($conexion, $_POST['cliente']);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_pedido_venta_cab(
        {$_POST['pedven_cod']},
        '$pedven_estado',
        {$_POST['usu_cod']},
        {$_POST['suc_cod']},
        {$_POST['emp_cod']},
        {$_POST['cli_cod']},
        {$_POST['operacion_cab']},
        '$usu_login',
        '$suc_descri',
        '$emp_razonsocial',
        '{$_POST['per_nrodoc']}',
        '$cliente',
        '{$_POST['transaccion']}'
    );";

    pg_query($conexion, $sql);
        
    $response = array(
        "mensaje" => pg_last_notice($conexion),
        "tipo" => "success"
    );

    echo json_encode($response);

} else if (isset($_POST['consulCod']) == 1) {
    //Se obtiene el valor para asignar al codigo
    $pedvenCod = "select coalesce (max(pedven_cod),0)+1 as codigo from pedido_venta_cab;";

    $codigo = pg_query($conexion, $pedvenCod);
    $codigoPedven = pg_fetch_assoc($codigo);
    echo json_encode($codigoPedven);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select * from --v_pedido_venta_cab;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>