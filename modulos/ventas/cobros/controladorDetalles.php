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

    // switch ($_POST['forcob_cod']) {
    //     case 1: 
    //         $monto = (float)$_POST['cobrcheq_monto'];
    //         break;
    //     case 2:
    //         $monto = (float)$_POST['cobrdet_monto'];
    //         break;
    //     case 3: 
    //         $monto = (float)$_POST['cobrtarj_monto'];
    //         break;
    // }

    $cobrdet_monto = str_replace(",", ".", $_POST['cobrdet_monto']);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_cobros_det(
        {$_POST['cobr_cod']},
        {$_POST['forcob_cod']},
        {$_POST['cobrdet_cod']},
        $cobrdet_monto,
        {$_POST['operacion_det']},
        {$_POST['ven_cod']},
        '{$_POST['cobrcheq_num']}',
        {$_POST['ent_cod']},
        {$_POST['usu_cod']},
        '{$_POST['cobrtarj_transaccion']}',
        {$_POST['redpag_cod']}
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
    } else if (strpos($error, "mayor_saldo") !== false) {
        $response = array(
            "mensaje" => "EL MONTO EXCEDE EL SALDO DE GS.".number_format($_POST['pendiente'],0,',','.')." DE LA CUOTA",
            "tipo" => "error"
        );
    } else {
        $response = array(
            "mensaje" => pg_last_notice($conexion),
            "tipo" => "success"
        );
    }

    echo json_encode($response);

} else if (isset($_POST['validacion_det']) == 1) {
    //Se consulta si la venta esta asociada a una nota
    $venCod = "select 1 validar from cobros_cab
    where ven_cod = {$_POST['ven_cod']}
        and cobr_nrocuota > {$_POST['cobr_nrocuota']}
        and cobr_estado = 'ACTIVO';";

    $codigo = pg_query($conexion, $venCod);
    $codigoven = pg_fetch_assoc($codigo);
    echo json_encode($codigoven);

} else if (isset($_POST['cobr_cod'])){

    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select * from v_cobros_det
            where cobr_cod = {$_POST['cobr_cod']};";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}
?>