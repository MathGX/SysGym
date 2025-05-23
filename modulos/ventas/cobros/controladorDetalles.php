<?php
//Establecemos el retorno del documento en formato json
header("Content-type: application/json; charset=utf-8");
//Solicitamos la clase de Conexion
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

//Consultamos si existe la variable operacion
if (isset($_POST['operacion_det'])) {

    switch ($_POST['forcob_cod']) {
        case 1: 
            $monto = (float)$_POST['cobrcheq_monto'];
            break;
        case 2:
            $monto = (float)$_POST['cobrdet_monto'];
            break;
        case 3: 
            $monto = (float)$_POST['cobrtarj_monto'];
            break;
    }

    $cobrdet_monto = str_replace(",", ".", $monto);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_cobros_det(
        {$_POST['ven_cod']},
        {$_POST['cobr_cod']},
        {$_POST['cobrdet_cod']},
        $cobrdet_monto,
        {$_POST['cobrdet_nrocuota']},
        {$_POST['forcob_cod']},
        coalesce('{$_POST['cobrcheq_num']}','0'),
        {$_POST['ent_cod']},
        {$_POST['usu_cod']},
        coalesce('{$_POST['cobrtarj_transaccion']}','0'),
        {$_POST['redpag_cod']},
        {$_POST['operacion_det']}
    );";

    pg_query($conexion, $sql);
        $error = pg_last_error($conexion);
        //Si ocurre un error lo capturamos y lo enviamos al front-end
        if (strpos($error, "tarjeta") !== false) {
            $response = array(
                "mensaje" => "EL NRO DE TRANSACCION YA EXISTE PARA LA RED DE PAGO SELECCIONADA",
                "tipo" => "error"
            );
        } else if (strpos($error, "cheque") !== false) {
            $response = array(
                "mensaje" => "EL N° DE CHEQUE YA FUE REGISTRADO EN OTRA OPERACION",
                "tipo" => "error"
            );
        } else if (strpos($error, "efectivo") !== false) {
            $response = array(
                "mensaje" => "YA FUE REALIZADO UN COBRO EN EFECTIVO PARA ESTA TRANSACCION",
                "tipo" => "error"
            );
        } else {
            $response = array(
                "mensaje" => pg_last_notice($conexion),
                "tipo" => "success"
            );
        }

    echo json_encode($response);

} else if (isset($_POST['cobr_cod'])){

    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select * from v_cobros_det
            where cobr_cod = {$_POST['cobr_cod']};";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}

?>