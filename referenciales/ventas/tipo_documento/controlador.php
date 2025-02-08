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
    $descripcion = $_POST['tipdoc_descri'];
    $estado = $_POST['tipdoc_estado'];

    //escapar los datos para que acepte comillas simples
    $tipdoc_descri = pg_escape_string($conexion, $descripcion);
    $tipdoc_estado = pg_escape_string($conexion, $estado);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_tipoDocumento(
        {$_POST['tipdoc_cod']},
        '$tipdoc_descri',
        '$tipdoc_estado',
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
            "mensaje" => "ESTE TIPO DE DOCUMENTO YA EXISTE",
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
    $tipDocCod = "select coalesce (max(tipdoc_cod),0)+1 as codigo from tipo_documento;";

    $codigo = pg_query($conexion, $tipDocCod);
    $codigoTipDoc = pg_fetch_assoc($codigo);
    echo json_encode($codigoTipDoc);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select 
            td.tipdoc_cod,
            td.tipdoc_descri,
            td.tipdoc_estado 
            from tipo_documento td 
            order by td.tipdoc_cod;";
    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>