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
    $descripcion = $_POST['forcob_descri'];
    $estado = $_POST['forcob_estado'];

    //escapar los datos para que acepte comillas simples
    $forcob_descri = pg_escape_string($conexion, $descripcion);
    $forcob_estado = pg_escape_string($conexion, $estado);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_formaCobro(
        {$_POST['forcob_cod']},
        '$forcob_descri',
        '$forcob_estado',
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
            "mensaje" => "ESTA FORMA DE COBRO YA EXISTE",
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
    $forcobCod = "select coalesce (max(forcob_cod),0)+1 as codigo from forma_cobro;";

    $codigo = pg_query($conexion, $forcobCod);
    $codigoforcob = pg_fetch_assoc($codigo);
    echo json_encode($codigoforcob);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select
            fc.forcob_cod,
            fc.forcob_descri,
            fc.forcob_estado 
            from forma_cobro fc 
            order by fc.forcob_cod;";
            
    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>