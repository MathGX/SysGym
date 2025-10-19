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
    $med_estado = pg_escape_string($conexion, $_POST['med_estado']);
    $usu_login = pg_escape_string($conexion, $_POST['usu_login']);
    $suc_descri = pg_escape_string($conexion, $_POST['suc_descri']);
    $emp_razonsocial = pg_escape_string($conexion, $_POST['emp_razonsocial']);
    $cliente = pg_escape_string($conexion, $_POST['cliente']);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_mediciones_cab(
        {$_POST['med_cod']},
        '{$_POST['med_fecha']}',
        '$med_estado',
        {$_POST['cli_cod']},
        {$_POST['usu_cod']},
        {$_POST['suc_cod']},
        {$_POST['emp_cod']},
        {$_POST['prpr_cod']},
        {$_POST['operacion_cab']},
        '{$_POST['per_nrodoc']}',
        '$cliente',
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
            "mensaje" => "ESTE CLIENTE YA FUE MEDIDO",
            "tipo" => "error"
        );
    } else if (strpos($error, "err_cab") !== false) {
        $response = array(
            "mensaje" => "EL ESTADO DE LA MEDICIÓN IMPIDE QUE SEA ANULADA, SE ENCUENTRA ASOCIADA A UNA EVOLUCIÓN",
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
    $medCod = "select coalesce (max(med_cod),0)+1 as codigo from mediciones_cab;";

    $codigo = pg_query($conexion, $medCod);
    $codigoMed = pg_fetch_assoc($codigo);
    echo json_encode($codigoMed);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select * from v_mediciones_cab;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>