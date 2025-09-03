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
    $marcve_descri = pg_escape_string($conexion, $_POST['marcve_descri']);
    $marcve_estado = pg_escape_string($conexion, $_POST['marcve_estado']);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_marca_vehiculo(
        {$_POST['marcve_cod']},
        '$marcve_descri',
        '$marcve_estado',
        {$_POST['operacion']},
        {$_POST['usu_cod']},
        '{$_POST['usu_login']}',
        '{$_POST['transaccion']}'
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "err_marca") !== false) {
        $response = array(
            "mensaje" => "ESTA MARCA DE VEHICULO YA SE ENCUENTRA REGISTRADA",
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
    $marcveCod = "select coalesce (max(marcve_cod),0)+1 as codigo from marca_vehiculo;";

    $codigo = pg_query($conexion, $marcveCod);
    $codigomarcve = pg_fetch_assoc($codigo);
    echo json_encode($codigomarcve);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select 
                marcve_cod,
                marcve_descri,
                marcve_estado 
            from marca_vehiculo 
            order by marcve_cod;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>