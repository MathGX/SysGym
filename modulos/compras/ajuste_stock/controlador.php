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

$ajuste = $_POST['ajus_tipoajuste'];
$estado = $_POST['ajus_estado'];
$sucursal = $_POST['suc_descri'];
$empresa = $_POST['emp_razonsocial'];
$usuario = $_POST['usu_login'];

//se escapan los datos capturados
$ajus_tipoajuste = pg_escape_string($conexion, $ajuste);
$ajus_estado = pg_escape_string($conexion, $estado);
$suc_descri = pg_escape_string($conexion, $sucursal);
$emp_razonsocial = pg_escape_string($conexion, $empresa);
$usu_login = pg_escape_string($conexion, $usuario);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_ajuste_stock_cab(
        {$_POST['ajus_cod']},
        '{$_POST['ajus_fecha']}',
        '$ajus_tipoajuste',
        '$ajus_estado',
        {$_POST['suc_cod']},
        {$_POST['emp_cod']},
        {$_POST['usu_cod']},
        {$_POST['operacion_cab']},
        '$suc_descri',
        '$emp_razonsocial',
        '$usu_login',
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
    $ajusCod = "select coalesce (max(ajus_cod),0)+1 as codigo from ajuste_stock_cab;";

    $codigo = pg_query($conexion, $ajusCod);
    $codigoajus = pg_fetch_assoc($codigo);
    echo json_encode($codigoajus);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select * from v_ajuste_stock_cab;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>