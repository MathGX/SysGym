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
        $presprov_estado = pg_escape_string($conexion, $_POST['presprov_estado']);
        $pro_razonsocial = pg_escape_string($conexion, $_POST['pro_razonsocial']);
        $suc_descri = pg_escape_string($conexion, $_POST['suc_descri']);
        $emp_razonsocial = pg_escape_string($conexion, $_POST['emp_razonsocial']);
        $usu_login = pg_escape_string($conexion, $_POST['usu_login']);
        
    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_presupuesto_prov_cab(
        {$_POST['presprov_cod']},
        '{$_POST['presprov_fecha']}',
        '{$_POST['presprov_fechavenci']}',
        '$presprov_estado',
        {$_POST['pro_cod']},
        {$_POST['tiprov_cod']},
        {$_POST['suc_cod']},
        {$_POST['emp_cod']},
        {$_POST['usu_cod']},
        {$_POST['pedcom_cod']},
        {$_POST['operacion_cab']},
        '{$_POST['pro_ruc']}',
        '$pro_razonsocial',
        '$suc_descri',
        '$emp_razonsocial',
        '$usu_login',
        '{$_POST['transaccion']}'
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "fecha") !== false) {
        $response = array(
            "mensaje" => "LA FECHA DE VENCIMIENTO NO PUEDE SER INFERIOR A LA FECHA DE EMISION",
            "tipo" => "error"
        );
    } else if (strpos($error, "pedido") !== false) {
        $response = array(
            "mensaje" => "EL PROVEEDOR SELECCIONADO YA CUENTA CON UN PRESUPUESTO PARA EL PEDIDO DESIGNADO",
            "tipo" => "error"
        );
    } else if (strpos($error, "err_cab") !== false) {
        $response = array(
            "mensaje" => "EL ESTADO DEL PRESUPUESTO IMPIDE QUE SEA ANULADO, SE ENCUENTRA ASOCIADO A UNA ORDEN",
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
    $presprovCod = "select coalesce (max(presprov_cod),0)+1 as codigo from presupuesto_prov_cab;";

    $codigo = pg_query($conexion, $presprovCod);
    $codigoPresprov = pg_fetch_assoc($codigo);
    echo json_encode($codigoPresprov);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select * from v_presupuesto_prov_cab;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>