<?php
//Establecemos el retorno del documento en formato json
header("Content-type: application/json; charset=utf-8");
//Solicitamos la clase de Conexion
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

//Consultamos si existe la variable operacion
if (isset($_POST['operacion'])) {

    //captura de datos desde el front-end
    $descripcion = $_POST['itm_descri'];
    $estado = $_POST['itm_estado'];
    $unidad_medida = $_POST['uni_descri'];
    $tipo_item = $_POST['tipitem_descri'];
    $tipo_impuesto = $_POST['tipimp_descri'];

    //escapar los datos para que acepte comillas simples
    $itm_descri = pg_escape_string($conexion, $descripcion);
    $itm_estado = pg_escape_string($conexion, $estado);
    $uni_descri = pg_escape_string($conexion, $unidad_medida);
    $tipitem_descri = pg_escape_string($conexion, $tipo_item);
    $tipimp_descri = pg_escape_string($conexion, $tipo_impuesto);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_items(
        {$_POST['itm_cod']},
        {$_POST['tipitem_cod']},
        '$itm_descri',
        {$_POST['itm_costo']},
        {$_POST['itm_precio']},
        '$itm_estado',
        {$_POST['tipimp_cod']},
        {$_POST['uni_cod']},
        {$_POST['operacion']},
        {$_POST['usu_cod']},
        '{$_POST['usu_login']}',
        '{$_POST['transaccion']}',
        '$uni_descri',
        '$tipitem_descri',
        '$tipimp_descri'
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "1") !== false) {
        $response = array(
            "mensaje" => "ESTE ITEM YA EXISTE",
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
    $itmCod = "select coalesce (max(itm_cod),0)+1 as codigo from items;";

    $codigo = pg_query($conexion, $itmCod);
    $codigoItem = pg_fetch_assoc($codigo);
    echo json_encode($codigoItem);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select 
            i.itm_cod,
            i.tipitem_cod,
            ti.tipitem_descri,
            i.itm_descri,
            i.itm_costo,
            i.itm_precio,
            i.uni_cod,
            um.uni_descri,
            i.tipimp_cod,
            ti2.tipimp_descri,
            i.itm_estado
            from items i
            join tipo_item ti on ti.tipitem_cod = i.tipitem_cod
            join tipo_impuesto ti2 on ti2.tipimp_cod = i.tipimp_cod
            join unidad_medida um on um.uni_cod = i.uni_cod 
            order by i.itm_cod;";
    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>