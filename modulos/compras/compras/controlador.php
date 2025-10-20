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

    //captura de datos desde el front-end
    $factura = $_POST['com_nrofac'];

    //escapar los datos para que acepte comillas simples
    $com_intefecha = pg_escape_string($conexion, $_POST['com_intefecha']);
    $com_estado = pg_escape_string($conexion, $_POST['com_estado']);
    $pro_razonsocial = pg_escape_string($conexion, $_POST['pro_razonsocial']);
    $usu_login = pg_escape_string($conexion, $_POST['usu_login']);
    $suc_descri = pg_escape_string($conexion, $_POST['suc_descri']);
    $emp_razonsocial = pg_escape_string($conexion, $_POST['emp_razonsocial']);

    //Se eliminan espacios en blanco
    $factura = preg_replace('/\D/', '', $factura);
    //separar el nro de factura por guiones
    $com_nrofac = substr($factura, 0, 3).'-'.substr($factura, 3, 3).'-'.substr($factura, 6);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_compra_cab(
        {$_POST['com_cod']},
        '{$_POST['com_fecha']}',
        '$com_nrofac',
        '{$_POST['com_tipfac']}',
        {$_POST['com_cuotas']},
        '$com_intefecha',
        '$com_estado',
        {$_POST['pro_cod']},
        {$_POST['tiprov_cod']},
        {$_POST['usu_cod']},
        {$_POST['suc_cod']},
        {$_POST['emp_cod']},
        {$_POST['ordcom_cod']},
        {$_POST['com_montocuota']},
        '{$_POST['com_timbrado']}',
        {$_POST['tipcomp_cod']},
        '{$_POST['com_timb_fec_venc']}',
        {$_POST['operacion_cab']},
        '$pro_razonsocial',
        '$usu_login',
        '$suc_descri',
        '$emp_razonsocial',
        '{$_POST['transaccion']}'
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "err_rep") !== false) {
        $response = array(
            "mensaje" => "ESTE N° DE FACTURA YA ESTÁ CARGADO",
            "tipo" => "error"
        );
    } else if (strpos($error, "err_cab") !== false) {
        $response = array(
            "mensaje" => "EL ESTADO DE LA COMPRA IMPIDE QUE SEA ANULADA, SE ENCUENTRA ASOCIADA A UNA NOTA ",
            "tipo" => "error"
        );
    } else if (strpos($error, "err_fectim") !== false) {
        $response = array(
            "mensaje" => "LA FECHA DE VENCIMIENTO DEL TIMBRADO NO PUEDE SER MENOR A LA FECHA DE LA COMPRA",
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
    $comCod = "select coalesce (max(com_cod),0)+1 as codigo from compra_cab;";

    $codigo = pg_query($conexion, $comCod);
    $codigoCom = pg_fetch_assoc($codigo);
    echo json_encode($codigoCom);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select * from v_compra_cab;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>