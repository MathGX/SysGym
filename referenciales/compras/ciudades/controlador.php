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
    $descripcion = $_POST['ciu_descripcion'];
    $estado = $_POST['ciu_estado'];

    //escapar los datos para que acepte comillas simples
    $ciu_descripcion = pg_escape_string($conexion, $descripcion);
    $ciu_estado = pg_escape_string($conexion, $estado);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_ciudades(
        {$_POST['ciu_cod']},
        '$ciu_descripcion',
        '$ciu_estado',
        {$_POST['operacion']},
        {$_POST['usu_cod']},
        '{$_POST['usu_login']}',
        '{$_POST['transaccion']}'
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "1") !== false) {
        $response = array(
            "mensaje" => "ESTA CIUDAD YA EXISTE",
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
    $ciuCod = "select coalesce (max(ciu_cod),0)+1 as codigo from ciudad;";

    $codigo = pg_query($conexion, $ciuCod);
    $codigoCiudad = pg_fetch_assoc($codigo);
    echo json_encode($codigoCiudad);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select
            ciu_cod,
            ciu_descripcion,
            ciu_estado
            from ciudad 
            order by ciu_cod;";
    
    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}

?>