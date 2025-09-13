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

    //se escapan los datos capturados
    $ajusdet_motivo = pg_escape_string($conexion, $_POST['ajusdet_motivo']);
    $ajus_tipoajuste = pg_escape_string($conexion, $_POST['ajus_tipoajuste']);

    $ajusdet_cantidad = str_replace(",", ".", $_POST['ajusdet_cantidad']);
    $ajusdet_precio = str_replace(",", ".", $_POST['ajusdet_precio']);


    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_ajuste_stock_det(
        {$_POST['itm_cod']},
        {$_POST['tipitem_cod']},
        {$_POST['dep_cod']},
        {$_POST['suc_cod']},
        {$_POST['emp_cod']},
        {$_POST['ajus_cod']},
        '$ajusdet_motivo',
        $ajusdet_cantidad,
        $ajusdet_precio,
        '$ajus_tipoajuste',
        {$_POST['operacion_det']}
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "err_det") !== false) {
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


} else if (isset($_POST['ajus_cod'])){

    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select * from v_ajuste_stock_det
            where ajus_cod = {$_POST['ajus_cod']};";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}

?>