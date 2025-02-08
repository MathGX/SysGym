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
    $descripcion = $_POST['equi_descri'];
    $estado = $_POST['equi_estado'];
    $tipo = $_POST['tipequi_descri'];

    //escapar los datos para que acepte comillas simples
    $equi_descri = pg_escape_string($conexion, $descripcion);
    $equi_estado = pg_escape_string($conexion, $estado);
    $tipequi_descri = pg_escape_string($conexion, $tipo);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_equipos(
        {$_POST['equi_cod']},
        {$_POST['tipequi_cod']},
        '$equi_descri',
        '$equi_estado',
        {$_POST['operacion']},
        {$_POST['usu_cod']},
        '{$_POST['usu_login']}',
        '{$_POST['transaccion']}',
        '$tipequi_descri'
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "1") !== false) {
        $response = array(
            "mensaje" => "ESTE EQUIPO YA EXISTE",
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
    $equiCod = "select coalesce (max(equi_cod),0)+1 as codigo from equipos;";

    $codigo = pg_query($conexion, $equiCod);
    $codigoEqui = pg_fetch_assoc($codigo);
    echo json_encode($codigoEqui);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select 
            e.equi_cod,
            e.tipequi_cod,
            te.tipequi_descri,
            e.equi_descri,
            e.equi_estado 
            from equipos e 
            join tipo_equipos te on te.tipequi_cod = e.tipequi_cod
            order by e.equi_cod;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>