<?php
session_start();

//Establecemos el retorno del documento en formato json
header("Content-type: application/json; charset=utf-8");
//Solicitamos la clase de Conexion
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

//Consultamos si existe la variable operacion
if ((isset($_POST['operacion']))) {

    $estado = $_POST['apcier_estado'];

    //ESCAPAMOS LOS DATOS CAPTURADOS
    $apcier_estado = pg_escape_string($conexion, $estado);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    if ($_POST['operacion'] == 1) {
        $sql = "select sp_apertura_cierre(
            {$_POST['caj_cod']},
            {$_POST['suc_cod']},
            {$_POST['emp_cod']},
            {$_POST['usu_cod']},
            {$_POST['apcier_cod']},
            '{$_POST['apcier_fechahora_aper']}',
            null,
            {$_POST['apcier_monto_aper']},
            null,
            '$apcier_estado',
            {$_POST['operacion']}
        );";
    } else if ($_POST['operacion'] == 2) {
        $sql = "select sp_apertura_cierre(
            {$_POST['caj_cod']},
            {$_POST['suc_cod']},
            {$_POST['emp_cod']},
            {$_POST['usu_cod']},
            {$_POST['apcier_cod']},
            null,
            '{$_POST['apcier_fechahora_cierre']}',
            null,
            {$_POST['apcier_monto_cierre']},
            '$apcier_estado',
            {$_POST['operacion']}
        );";
    }

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "err_cab") !== false) {
        $response = array(
            "mensaje" => "ESTA CAJA YA ESTÁ ABIERTA",
            "tipo" => "error"
        );
    } else {
        $numApcier = ["codigo" => "{$_POST['apcier_cod']}",
            "caja"=> "{$_POST['caj_cod']}",
            "cajDescri"=> "{$_POST['caj_descri']}",
            "estado" => "$apcier_estado"];
    
        $_SESSION['numApcier'] = $numApcier;

        $response = array(
            "mensaje" => pg_last_notice($conexion),
            "tipo" => "success"
        );
    }

    echo json_encode($response);
    
} else {

    $sql = "select coalesce (max(apcier_cod),0)+1 as cod_apcier from apertura_cierre;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_assoc($resultado);
    echo json_encode($datos);
}


?>