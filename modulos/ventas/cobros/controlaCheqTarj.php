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

        //ESCAPAMOS LOS DATOS CAPTURADOS
        $cobrcheq_tipcheq = pg_escape_string($conexion,  $_POST['cobrcheq_tipcheq']);

        //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
        $sql = "select sp_cobro_cheque(
            {$_POST['cobrcheq_cod']},
            '{$_POST['cobrcheq_num']}',
            {$_POST['cobrcheq_monto']},
            '$cobrcheq_tipcheq',
            '{$_POST['cobrcheq_fecha_emi']}',
            '{$_POST['cobrcheq_fechaven']}',
            {$_POST['cobr_cod']},
            {$_POST['cobrdet_cod']},
            {$_POST['ent_cod']},
            {$_POST['operacion_det']},
            {$_POST['forcob_cod']}
        );";

    } else if ($_POST['forcob_cod'] == '3') {

        //ESCAPAMOS LOS DATOS CAPTURADOS
        $cobrtarj_tiptarj = pg_escape_string($conexion, $_POST['cobrtarj_tiptarj']);
    
        //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
        $sql = "select sp_cobro_tarjeta(
            {$_POST['cobrtarj_cod']},
            '{$_POST['cobrtarj_transaccion']}',
            {$_POST['cobrtarj_monto']},
            '$cobrtarj_tiptarj',
            {$_POST['cobr_cod']},
            {$_POST['cobrdet_cod']},
            {$_POST['martarj_cod']},
            {$_POST['ent_cod']},
            {$_POST['entahd_cod']},
            {$_POST['redpag_cod']},
            {$_POST['forcob_cod']},
            '{$_POST['cobrtarj_autorizacion']}',
            {$_POST['operacion_det']}
        );";
    }
    
    $cheque_tarjeta = pg_query($conexion, $sql);
    $cheque_tarjeta_result = pg_fetch_assoc($cheque_tarjeta);
    echo json_encode($cheque_tarjeta_result);
    
} else {
    //Si el post no recibe la operacion realizamos una consulta para generar el codigo de cobro detalle
    $cobrDetCod = "select coalesce (max(cobrdet_cod),0)+1 as cobrdet_cod from cobros_det;";

    $codigo = pg_query($conexion, $cobrDetCod);
    $codigoCobrDet = pg_fetch_assoc($codigo);
    echo json_encode($codigoCobrDet);
}

?>