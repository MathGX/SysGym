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

    $prprdet_cantidad = str_replace(",", ".", $_POST['prprdet_cantidad']);
    $prprdet_precio = str_replace(",", ".", $_POST['prprdet_precio']);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_presupuesto_prep_det(
        {$_POST['itm_cod']},
        {$_POST['tipitem_cod']},
        {$_POST['prpr_cod']},
        $prprdet_precio,
        $prprdet_cantidad,
        {$_POST['prprdet_promdes_cod']},
        '{$_POST['cupdet_hora_ini']}',
        '{$_POST['cupdet_hora_fin']}',
        {$_POST['cup_cod']},
        {$_POST['operacion_det']}
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "err_rep") !== false) {
        $response = array(
            "mensaje" => "EL SERVICIO SELECCIONADO YA ESTÁ PRESUPUESTADO",
            "tipo" => "error"
        );
    } else if (strpos($error, "err_promo") !== false) {
        $response = array(
            "mensaje" => "PARA ELIMINAR LA PROMOCIÓN DEBE ELIMINAR EL SERVICIO ASOCIADO",
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
    //Se consulta si el presupuesto esta asociado a una venta
    $venCod = "select 1 validar from presupuesto_venta po 
                    join ventas_cab occ on occ.ven_cod = po.ven_cod 
                where po.prpr_cod = {$_POST['prpr_cod']}
                    and occ.ven_estado != 'ANULADO';";

    $codigo = pg_query($conexion, $venCod);
    $codigoVen = pg_fetch_assoc($codigo);
    echo json_encode($codigoVen);

} else if (isset($_POST['prpr_cod'])){

    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select * from v_presupuesto_prep_det
            where prpr_cod = {$_POST['prpr_cod']};";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}

?>