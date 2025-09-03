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
    $modve_descri = pg_escape_string($conexion, $_POST['modve_descri']);
    $modve_estado = pg_escape_string($conexion, $_POST['modve_estado']);
    $marcve_descri = pg_escape_string($conexion, $_POST['marcve_descri']);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_modelo_vehiculo(
        {$_POST['modve_cod']},
        '$modve_descri',
        {$_POST['marcve_cod']},
        '$modve_estado',
        {$_POST['operacion']},
        {$_POST['usu_cod']},
        '{$_POST['usu_login']}',
        '$marcve_descri',
        '{$_POST['transaccion']}'
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "err_modelo") !== false) {
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
    $modveCod = "select coalesce (max(modve_cod),0)+1 as codigo from modelo_vehiculo;";

    $codigo = pg_query($conexion, $modveCod);
    $codigomodve = pg_fetch_assoc($codigo);
    echo json_encode($codigomodve);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select 
                m.modve_cod,
                m.modve_descri,
                m.modve_estado,
                m.marcve_cod,
                mv.marcve_descri,
                mv.marcve_descri || ', MODELO ' || m.modve_descri as marca_modelo
            from modelo_vehiculo m
                join marca_vehiculo mv on mv.marcve_cod = m.marcve_cod
            order by modve_cod;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>