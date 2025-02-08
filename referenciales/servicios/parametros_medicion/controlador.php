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
    $param_descri = pg_escape_string($conexion, $_POST['param_descri']);
    $param_formula = pg_escape_string($conexion, $_POST['param_formula']);
    $uni_descri = pg_escape_string($conexion, $_POST['uni_descri']);
    $param_estado = pg_escape_string($conexion, $_POST['param_estado']);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_parametrosMedicion(
        {$_POST['param_cod']},
        '$param_descri',
        '$param_estado',
        {$_POST['uni_cod']},
        '$param_formula',
        {$_POST['operacion']},
        {$_POST['usu_cod']},
        '{$_POST['usu_login']}',
        '$uni_descri',
        '{$_POST['transaccion']}'
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "1") !== false) {
        $response = array(
            "mensaje" => "ESTE PARÁMETRO DE MEDICIÓN YA EXISTE",
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
    $paramCod = "select coalesce (max(param_cod),0)+1 as codigo from parametros_medicion;";

    $codigo = pg_query($conexion, $paramCod);
    $codigoParam = pg_fetch_assoc($codigo);
    echo json_encode($codigoParam);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select
        pm.param_cod,
        pm.param_descri,
        pm.uni_cod,
        um.uni_descri,
        um.uni_simbolo,
        pm.param_formula,
        pm.param_estado 
        from parametros_medicion pm
            join unidad_medida um on um.uni_cod = pm.uni_cod 
        order by param_cod;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>