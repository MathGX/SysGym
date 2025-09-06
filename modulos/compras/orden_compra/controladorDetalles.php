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

    $ordcomdet_cantidad = str_replace(",", ".", $_POST['ordcomdet_cantidad']);
    $ordcomdet_precio = str_replace(",", ".", $_POST['ordcomdet_precio']);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_orden_compra_det(
        {$_POST['itm_cod']},
        {$_POST['tipitem_cod']},
        {$_POST['ordcom_cod']},
        $ordcomdet_cantidad,
        $ordcomdet_precio,
        {$_POST['operacion_det']}
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "err_rep") !== false) {
        $response = array(
            "mensaje" => "ESTE ITEM YA ESTÁ CARGADO",
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
    //Se consulta si el pedido de compra esta asociado a un presupuesto
    $pedcomCod = "select 1 validar from compra_orden co 
                    join compra_cab cc on cc.com_cod = co.com_cod 
                where co.ordcom_cod = {$_POST['ordcom_cod']}
                    and cc.com_estado != 'ANULADO';";

    $codigo = pg_query($conexion, $pedcomCod);
    $codigoPedcom = pg_fetch_assoc($codigo);
    echo json_encode($codigoPedcom);

} else if (isset($_POST['ordcom_cod'])){

    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select * from v_orden_compra_det
            where ordcom_cod = {$_POST['ordcom_cod']};";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}

?>