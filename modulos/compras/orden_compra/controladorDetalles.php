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

        $response = array(
            "mensaje" => pg_last_notice($conexion),
            "tipo" => "success"
        );

    echo json_encode($response);


} else if (isset($_POST['ordcom_cod'])){

    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select * from v_orden_compra_det
            where ordcom_cod = {$_POST['ordcom_cod']};";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}

?>