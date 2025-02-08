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
    $estado = $_POST['entahd_estado'];
    $entidad_emisora = $_POST['ent_razonsocial'];
    $marca_tarjeta = $_POST['martarj_descri'];

    $entahd_estado = pg_escape_string($conexion, $estado);
    $ent_razonsocial = pg_escape_string($conexion, $entidad_emisora);
    $martarj_descri = pg_escape_string($conexion, $marca_tarjeta);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_entidadAdherida(
        {$_POST['entahd_cod']},
        {$_POST['martarj_cod']},
        {$_POST['ent_cod']},
        '$entahd_estado',
        {$_POST['operacion']},
        {$_POST['usu_cod']},
        '{$_POST['usu_login']}',
        '{$_POST['transaccion']}',
        '$ent_razonsocial',
        '$martarj_descri'
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "1") !== false) {
        $response = array(
            "mensaje" => "ESTA ENTIDAD ADHERIDA YA EXISTE",
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
    $entahdCod = "select coalesce (max(entahd_cod),0)+1 as codigo from entidad_adherida;";

    $codigo = pg_query($conexion, $entahdCod);
    $codigoEntahd = pg_fetch_assoc($codigo);
    echo json_encode($codigoEntahd);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select 
            ea.entahd_cod,
            ea.martarj_cod,
            mt.martarj_descri,
            ea.ent_cod,
            ee.ent_razonsocial,
            ea.entahd_estado 
            from entidad_adherida ea 
            join marca_tarjeta mt on mt.martarj_cod = ea.martarj_cod
            join entidad_emisora ee on ee.ent_cod = ea.ent_cod;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>