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
if ((isset($_POST['operacion_det']))) {

    if ($_POST['forcob_cod'] == '1') {
        
        $tipcheq = $_POST['cobrcheq_tipcheq'];
        //ESCAPAMOS LOS DATOS CAPTURADOS
        $cobrcheq_tipcheq = pg_escape_string($conexion, $tipcheq);

        //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post

        $sql = "select sp_cobro_cheque(
            {$_POST['cobrcheq_cod']},
            '{$_POST['cobrcheq_num']}',
            {$_POST['cobrcheq_monto']},
            '$cobrcheq_tipcheq',
            '{$_POST['cobrcheq_fechaven']}',
            {$_POST['ven_cod']},
            {$_POST['cobr_cod']},
            {$_POST['cobrdet_cod']},
            {$_POST['ent_cod']},
            {$_POST['operacion_det']}
        );";

        pg_query($conexion, $sql);
    
        $response = array(
            "mensaje" => pg_last_notice($conexion),
            "tipo" => "success"
        );

    } else if ($_POST['forcob_cod'] == '3') {
        
        $tiptarj = $_POST['cobrtarj_tiptarj'];

        //ESCAPAMOS LOS DATOS CAPTURADOS
        $cobrtarj_tiptarj = pg_escape_string($conexion, $tiptarj);
    
        //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    
        $sql = "select sp_cobro_tarjeta(
            {$_POST['cobrtarj_cod']},
            '{$_POST['cobrtarj_num']}',
            {$_POST['cobrtarj_monto']},
            '$cobrtarj_tiptarj',
            {$_POST['ven_cod']},
            {$_POST['cobr_cod']},
            {$_POST['cobrdet_cod']},
            {$_POST['martarj_cod']},
            {$_POST['ent_cod']},
            {$_POST['entahd_cod']},
            {$_POST['operacion_det']}
        );";
    
        pg_query($conexion, $sql);
    
        $response = array(
            "mensaje" => pg_last_notice($conexion),
            "tipo" => "success"
        );
    }

    echo json_encode($response);
    
} else {
    //Consultamos si existe la variable operacion

    $sql = "select coalesce (max(cobrdet_cod),0)+1 as cobrdet_cod from cobros_det;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_assoc($resultado);
    echo json_encode($datos);
}

?>