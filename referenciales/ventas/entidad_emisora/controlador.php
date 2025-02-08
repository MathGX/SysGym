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
    $razonsocial = $_POST['ent_razonsocial'];
    $email = $_POST['ent_email'];
    $estado = $_POST['ent_estado'];

    //escapar los datos para que acepte comillas simples
    $ent_razonsocial = pg_escape_string($conexion, $razonsocial);
    $ent_email = pg_escape_string($conexion, $email);
    $ent_estado = pg_escape_string($conexion, $estado);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_entidadEmisora(
        {$_POST['ent_cod']},
        '$ent_razonsocial',
        '{$_POST['ent_ruc']}',
        '{$_POST['ent_telf']}',
        '$ent_email',
        '$ent_estado',
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
            "mensaje" => "ESTA ENTIDAD YA EXISTE",
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
    $entCod = "select coalesce (max(ent_cod),0)+1 as codigo from entidad_emisora;";

    $codigo = pg_query($conexion, $entCod);
    $codigoEnt = pg_fetch_assoc($codigo);
    echo json_encode($codigoEnt);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select
            ee.ent_cod,
            ee.ent_razonsocial,
            ee.ent_ruc,
            ee.ent_telf,
            ee.ent_email,
            ee.ent_estado 
            from entidad_emisora ee 
            order by ee.ent_cod;";
    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>