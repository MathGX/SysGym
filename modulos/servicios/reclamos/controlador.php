<?php
//Establecemos el retorno del documento en formato json
header("Content-type: application/json; charset=utf-8");
//Solicitamos la clase de Conexion
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

//Consultamos si existe la variable operacion
if (isset($_POST['operacion_cab'])) {

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_asistencias(
        {$_POST['asis_cod']},
        '{$_POST['asis_fecha']}',
        '{$_POST['asis_horaentrada']}',
        '{$_POST['asis_horasalida']}',
        {$_POST['cli_cod']},
        {$_POST['usu_cod']},
        {$_POST['suc_cod']},
        {$_POST['emp_cod']},
        {$_POST['operacion_cab']}
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "1") !== false) {
        $response = array(
            "mensaje" => "LA ASISTENCIA DE  ESTE CLIENTE EN FECHA ".$_POST['asis_fecha']." YA FUE REGISTRADA",
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
    $asisCod = "select coalesce (max(asis_cod),0)+1 as codigo from asistencias;";

    $codigo = pg_query($conexion, $asisCod);
    $codigoAsis = pg_fetch_assoc($codigo);
    echo json_encode($codigoAsis);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select * from v_reclamos;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>