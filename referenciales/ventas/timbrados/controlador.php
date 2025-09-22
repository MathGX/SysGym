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

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_timbrados(
        {$_POST['tim_cod']},
        {$_POST['suc_cod']},
        {$_POST['emp_cod']},
        {$_POST['caj_cod']},
        {$_POST['tipcomp_cod']},
        {$_POST['tim_nro']},
        '{$_POST['tim_fec_ini']}',
        '{$_POST['tim_fec_venc']}',
        '{$_POST['tim_com_nro']}',
        {$_POST['tim_com_nro_ini']},
        {$_POST['tim_com_nro_lim']},
        '{$_POST['tim_estado']}',
        {$_POST['usu_cod']},
        {$_POST['operacion']}
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "err_tim") !== false) {
        $response = array(
            "mensaje" => "EL NÚMERO DE TIMBRADO YA EXISTE",
            "tipo" => "error"
        );
    } else if (strpos($error, "err_rep") !== false) {
        $response = array(
            "mensaje" => "YA EXISTE UN TIMBRADO VIGENTE PARA LA CAJA Y TIPO DE COMPROBANTE SELECCIONADO",
            "tipo" => "error"
        );
    } else if (strpos($error, "err_fec") !== false) {
        $response = array(
            "mensaje" => "LA FECHA DE VENCIMIENTO DEL TIMBRADO NO PUEDE SER MENOR O IGUAL A SU FECHA DE EMISIÓN",
            "tipo" => "error"
        );
    } else if (strpos($error, "err_lim") !== false) {
        $response = array(
            "mensaje" => "EL NÚMERO LÍMITE DE COMPROBANTES NO PUEDE SER MENOR O IGUAL AL NÚMERO INICIAL",
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
    $timCod = "select coalesce (max(tim_cod),0)+1 as codigo from timbrados;";

    $codigo = pg_query($conexion, $timCod);
    $codigotim = pg_fetch_assoc($codigo);
    echo json_encode($codigotim);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select * from v_timbrados";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>