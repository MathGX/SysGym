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

    //escapar los datos para que acepte comillas simples
    $ordcom_intefecha = pg_escape_string($conexion, $_POST['ordcom_intefecha']);
    $ordcom_estado = pg_escape_string($conexion, $_POST['ordcom_estado']);
    $pro_razonsocial = pg_escape_string($conexion, $_POST['pro_razonsocial']);
    $suc_descri = pg_escape_string($conexion, $_POST['suc_descri']);
    $emp_razonsocial = pg_escape_string($conexion, $_POST['emp_razonsocial']);
    $usu_login = pg_escape_string($conexion, $_POST['usu_login']);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_orden_compra_cab(
        {$_POST['ordcom_cod']},
        '{$_POST['ordcom_fecha']}',
        '{$_POST['ordcom_condicionpago']}',
        {$_POST['ordcom_cuota']},
        '$ordcom_intefecha',
        '$ordcom_estado',
        {$_POST['pro_cod']},
        {$_POST['tiprov_cod']},
        {$_POST['suc_cod']},
        {$_POST['emp_cod']},
        {$_POST['usu_cod']},
        {$_POST['ordcom_montocuota']},
        {$_POST['presprov_cod']},
        {$_POST['pedcom_cod']},
        {$_POST['operacion_cab']},
        '$pro_razonsocial',
        '$suc_descri',
        '$emp_razonsocial',
        '$usu_login',
        '{$_POST['transaccion']}'
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "err_cab") !== false) {
        $response = array(
            "mensaje" => "EL ESTADO DE LA ORDEN IMPIDE QUE SEA ANULADO, SE ENCUENTRA ASOCIADA A UNA COMPRA",
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
    $ordcomCod = "select coalesce (max(ordcom_cod),0)+1 as codigo from orden_compra_cab;";

    $codigo = pg_query($conexion, $ordcomCod);
    $codigoOrdcom = pg_fetch_assoc($codigo);
    echo json_encode($codigoOrdcom);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select * from v_orden_compra_cab;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>