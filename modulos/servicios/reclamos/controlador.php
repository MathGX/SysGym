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
    $sql = "select sp_reclamos(
        {$_POST['rec_cod']},
        '{$_POST['rec_fecha']}',
        '{$_POST['rec_descri']}',
        {$_POST['cli_cod']},
        '{$_POST['rec_estado']}',
        {$_POST['suc_cod']},
        {$_POST['emp_cod']},
        {$_POST['usu_cod']},
        {$_POST['motrec_cod']},
        {$_POST['operacion_cab']},
        '{$_POST['per_nrodoc']}',
        '{$_POST['cliente']}',
        '{$_POST['usu_login']}',
        '{$_POST['suc_descri']}',
        '{$_POST['emp_razonsocial']}',
        '{$_POST['motrec_descri']}',
        '{$_POST['transaccion']}'
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "err_rep") !== false) {
        $response = array(
            "mensaje" => "EN FECHA ".$_POST['rec_fecha']." EL CLIENTE SELECCIONADO YA REGISTRÓ UN RECLAMO POR LA MISMA CAUSA",
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
    $recCod = "select coalesce (max(rec_cod),0)+1 as codigo from reclamos;";

    $codigo = pg_query($conexion, $recCod);
    $codigorec = pg_fetch_assoc($codigo);
    echo json_encode($codigorec);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select * from v_reclamos;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>