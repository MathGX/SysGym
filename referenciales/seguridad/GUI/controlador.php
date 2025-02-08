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
    $descripcion = $_POST['gui_descri'];
    $estado = $_POST['gui_estado'];
    $modulo = $_POST['mod_descri'];

    //escapar los datos para que acepte comillas simples
    $gui_descri = pg_escape_string($conexion, $descripcion);
    $gui_estado = pg_escape_string($conexion, $estado);
    $mod_descri = pg_escape_string($conexion, $modulo);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_gui(
        {$_POST['gui_cod']},
        {$_POST['mod_cod']},
        '$gui_descri',
        '$gui_estado',
        {$_POST['operacion']},
        {$_POST['usu_cod']},
        '{$_POST['usu_login']}',
        '{$_POST['transaccion']}',
        '$mod_descri'
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "1") !== false) {
        $response = array(
            "mensaje" => "ESTE GUI YA EXISTE",
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
    $guiCod = "select coalesce (max(gui_cod),0)+1 as codigo from gui;";

    $codigo = pg_query($conexion, $guiCod);
    $codigoGui = pg_fetch_assoc($codigo);
    echo json_encode($codigoGui);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select 
            g.gui_cod,
            g.mod_cod,
            m.mod_descri,
            g.gui_descri,
            g.gui_estado 
            from gui g 
            join modulos m on m.mod_cod = g.mod_cod
            order by gui_cod;";
    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>