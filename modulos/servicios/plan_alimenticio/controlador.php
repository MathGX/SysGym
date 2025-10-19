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

    //ESCAPAMOS LOS DATOS CAPTURADOS
    $alim_estado = pg_escape_string($conexion, $_POST['alim_estado']);
    $tiplan_descri = pg_escape_string($conexion, $_POST['tiplan_descri']);
    $usu_login = pg_escape_string($conexion, $_POST['usu_login']);
    $suc_descri = pg_escape_string($conexion, $_POST['suc_descri']);
    $emp_razonsocial = pg_escape_string($conexion, $_POST['emp_razonsocial']);
    $cliente = pg_escape_string($conexion, $_POST['cliente']);
    $funcionario = pg_escape_string($conexion, $_POST['funcionario']);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_plan_alimenticio_cab(
        {$_POST['alim_cod']},
        '$alim_estado',
        {$_POST['tiplan_cod']},
        {$_POST['cli_cod']},
        {$_POST['fun_cod']},
        {$_POST['usu_cod']},
        {$_POST['suc_cod']},
        {$_POST['emp_cod']},
        '{$_POST['alim_fecha']}',
        {$_POST['prpr_cod']},
        {$_POST['operacion_cab']},
        '$tiplan_descri',
        '{$_POST['per_nrodoc']}',
        '$cliente',
        '$funcionario',
        '$usu_login',
        '$suc_descri',
        '$emp_razonsocial',
        '{$_POST['transaccion']}'
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "err_cli") !== false) {
        $response = array(
            "mensaje" => "ESTE CLIENTE YA POSEE UN PLAN",
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
    $alimCod = "select coalesce (max(alim_cod),0)+1 as codigo from plan_alimenticio_cab;";

    $codigo = pg_query($conexion, $alimCod);
    $codigoAlim = pg_fetch_assoc($codigo);
    echo json_encode($codigoAlim);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select * from v_plan_alimenticio_cab;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>