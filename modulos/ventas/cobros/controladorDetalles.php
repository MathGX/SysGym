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

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    if ($_POST['forcob_cod'] == '1') {
        $sql = "select sp_cobros_det(
            {$_POST['ven_cod']},
            {$_POST['cobr_cod']},
            {$_POST['cobrdet_cod']},
            {$_POST['cobrcheq_monto']},
            {$_POST['cobrdet_nrocuota']},
            {$_POST['forcob_cod']},
            '{$_POST['cobrcheq_num']}',
            {$_POST['ent_cod']},
            '{$_POST['cobrcheq_num']}',
            {$_POST['ent_cod']},
            {$_POST['operacion_det']}
        );";
    } else if ($_POST['forcob_cod'] == '3'){
        $sql = "select sp_cobros_det(
            {$_POST['ven_cod']},
            {$_POST['cobr_cod']},
            {$_POST['cobrdet_cod']},
            {$_POST['cobrtarj_monto']},
            {$_POST['cobrdet_nrocuota']},
            {$_POST['forcob_cod']},
            '{$_POST['cobrtarj_num']}',
            {$_POST['entahd_cod']},
            '{$_POST['cobrtarj_num']}',
            {$_POST['entahd_cod']},
            {$_POST['operacion_det']}
        );";
    } else {
        $sql = "select sp_cobros_det(
            {$_POST['ven_cod']},
            {$_POST['cobr_cod']},
            {$_POST['cobrdet_cod']},
            {$_POST['cobrdet_monto']},
            {$_POST['cobrdet_nrocuota']},
            {$_POST['forcob_cod']},
            '11111',
            1111,
            '11111',
            1111,
            {$_POST['operacion_det']}
        );";
    }

    pg_query($conexion, $sql);
        $error = pg_last_error($conexion);
        //Si ocurre un error lo capturamos y lo enviamos al front-end
        if (strpos($error, "tarjeta") !== false) {
            $response = array(
                "mensaje" => "EN ESTE REGISTRO YA SE COBRÓ CON ESTA TARJETA",
                "tipo" => "error"
            );
        } else if (strpos($error, "cheque") !== false) {
            $response = array(
                "mensaje" => "EL N° DE CHEQUE YA EXISTE",
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