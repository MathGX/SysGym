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
    $direccion = $_POST['cli_direccion'];
    $estado = $_POST['cli_estado'];
    $persona = $_POST['nombre'];
    $descripcion = $_POST['ciu_descripcion'];

    //escapar los datos para que acepte comillas simples
    $cli_direccion = pg_escape_string($conexion, $direccion);
    $cli_estado = pg_escape_string($conexion, $estado);
    $nombre = pg_escape_string($conexion, $persona);
    $ciu_descripcion = pg_escape_string($conexion, $descripcion);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_clientes(
        {$_POST['cli_cod']},
        '$cli_direccion',
        '$cli_estado',
        {$_POST['per_cod']},
        {$_POST['ciu_cod']},
        {$_POST['operacion']},
        {$_POST['usu_cod']},
        '{$_POST['usu_login']}',
        '{$_POST['transaccion']}',
        '$nombre',
        '$ciu_descripcion'
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "1") !== false) {
        $response = array(
            "mensaje" => "ESTE CLIENTE YA EXISTE",
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
    $cliCod = "select coalesce (max(cli_cod),0)+1 as codigo from clientes;";

    $codigo = pg_query($conexion, $cliCod);
    $codigocli = pg_fetch_assoc($codigo);
    echo json_encode($codigocli);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select 
            c.cli_cod,
            c.per_cod,
            p.per_nombres||' '||p.per_apellidos as persona,
            p.per_nrodoc,
            c.cli_direccion,
            c.ciu_cod,
            c2.ciu_descripcion,
            c.cli_estado 
            from clientes c
            join personas p on p.per_cod = c.per_cod
            join ciudad c2 on c2.ciu_cod = c.ciu_cod
            order by c.cli_cod;";
    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>