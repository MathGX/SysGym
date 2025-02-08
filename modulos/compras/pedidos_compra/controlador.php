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

    //Se escapan los datos para que se acepten comillas simples
    $pedcom_estado = pg_escape_string($conexion, $_POST['pedcom_estado']);
    $usu_login = pg_escape_string($conexion, $_POST['usu_login']);
    $suc_descri = pg_escape_string($conexion, $_POST['suc_descri']);
    $emp_razonsocial = pg_escape_string($conexion, $_POST['emp_razonsocial']);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_pedido_compra_cab(
        {$_POST['pedcom_cod']},
        {$_POST['usu_cod']},
        {$_POST['suc_cod']},
        {$_POST['emp_cod']},
        '$pedcom_estado',
        {$_POST['operacion_cab']},
        '$usu_login',
        '$suc_descri',
        '$emp_razonsocial',
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
    $pedcomCod = "select coalesce (max(pedcom_cod),0)+1 as codigo from pedido_compra_cab;";

    $codigo = pg_query($conexion, $pedcomCod);
    $codigoPedcom = pg_fetch_assoc($codigo);
    echo json_encode($codigoPedcom);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select * from v_pedido_compra_cab;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>