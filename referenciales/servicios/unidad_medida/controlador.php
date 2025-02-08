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
    $descripcion = $_POST['uni_descri'];
    $estado = $_POST['uni_estado'];
    $simbolo = $_POST['uni_simbolo'];

    //escapar los datos para que acepte comillas simples
    $uni_descri = pg_escape_string($conexion, $descripcion);
    $uni_estado = pg_escape_string($conexion, $estado);
    $uni_simbolo = pg_escape_string($conexion, $simbolo);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_unidadMedida(
        {$_POST['uni_cod']},
        '$uni_descri',
        '$uni_estado',
        '$uni_simbolo',
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
            "mensaje" => "ESTA MEDIDA YA EXISTE O ESTA REPITENDO EL SIMBOLO",
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
    $uniCod = "select coalesce (max(uni_cod),0)+1 as codigo from unidad_medida;";

    $codigo = pg_query($conexion, $uniCod);
    $codigoUni = pg_fetch_assoc($codigo);
    echo json_encode($codigoUni);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select
            um.uni_cod,
            um.uni_descri,
            um.uni_simbolo,um.uni_estado 
            from unidad_medida um 
            order by uni_cod;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>