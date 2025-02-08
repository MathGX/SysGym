<?php
//Establecemos el retorno del documento en formato json
header("Content-type: application/json; charset=utf-8");
//Solicitamos la clase de Conexion
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

//Consultamos si existe la variable operacion
if (isset($_POST['operacion'])) {

    //captura de datos desde el front-end
    $descripcion = $_POST['tipitem_descri'];
    $estado = $_POST['tipitem_estado'];

    //escapar los datos para que acepte comillas simples
    $tipitem_descri = pg_escape_string($conexion, $descripcion);
    $tipitem_estado = pg_escape_string($conexion, $estado);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_tipoItem(
        {$_POST['tipitem_cod']},
        '$tipitem_descri',
        '$tipitem_estado',
        {$_POST['operacion']},
        {$_POST['usu_cod']},
        '{$_POST['usu_login']}',
        '{$_POST['transaccion']}'
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "1") !== false) {
        $response = array(
            "mensaje" => "ESTE TIPO DE ITEM YA EXISTE",
            "tipo" => "error"
        );
    } else {
        $response = array(
            "mensaje" => pg_last_notice($conexion),
            "tipo" => "success"
        );
    }
    echo json_encode($response);


} else if (isset($_POST['consulCod']) == 1) {
    //Se obtiene el valor para asignar al codigo
    $tipItemCod = "select coalesce (max(tipitem_cod),0)+1 as codigo from tipo_item;";

    $codigo = pg_query($conexion, $tipItemCod);
    $codigoTipItem = pg_fetch_assoc($codigo);
    echo json_encode($codigoTipItem);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select 
            ti.tipitem_cod,
            ti.tipitem_descri,
            ti.tipitem_estado 
            from tipo_item ti
            order by tipitem_cod;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>