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
    $conf_validacion = pg_escape_string($conexion, $_POST['conf_validacion']);
    $conf_descri = pg_escape_string($conexion, $_POST['conf_descri']);
    $conf_estado = pg_escape_string($conexion, $_POST['conf_estado']);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_configuraciones(
        {$_POST['conf_cod']},
        '$conf_validacion',
        '$conf_descri',
        '$conf_estado',
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
            "mensaje" => "ESTA CONFIGURACION YA SE FUE REGISTRADA",
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
    $confCod = "select coalesce (max(conf_cod),0)+1 as codigo from configuraciones;";

    $codigo = pg_query($conexion, $confCod);
    $codigoconf = pg_fetch_assoc($codigo);
    echo json_encode($codigoconf);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select 
            c.conf_cod,
            c.conf_validacion,
            c.conf_descri,
            c.conf_estado 
            from configuraciones c 
            order by c.conf_cod;";
    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>