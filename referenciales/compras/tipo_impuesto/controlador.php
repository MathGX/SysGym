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

    //escapar los datos para que acepte comillas simples
    $tipimp_descri = pg_escape_string($conexion, $_POST['tipimp_descri']);
    $tipimp_estado = pg_escape_string($conexion, $_POST['tipimp_estado']);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_tipoImpuesto(
        {$_POST['tipimp_cod']},
        '$tipimp_descri',
        '$tipimp_estado',
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
            "mensaje" => "ESTE TIPO DE IMPUESTO YA EXISTE",
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
    $tipImpCod = "select coalesce (max(tipimp_cod),0)+1 as codigo from tipo_impuesto;";

    $codigo = pg_query($conexion, $tipImpCod);
    $codigoTipImp = pg_fetch_assoc($codigo);
    echo json_encode($codigoTipImp);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select 
            ti.tipimp_cod,
            ti.tipimp_descri,
            ti.tipimp_estado 
            from tipo_impuesto ti
            order by tipimp_cod;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>