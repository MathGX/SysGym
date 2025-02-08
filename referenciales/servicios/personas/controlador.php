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
    $nombres = $_POST['per_nombres'];
    $apellidos = $_POST['per_apellidos'];
    $email = $_POST['per_email'];
    $estado = $_POST['per_estado'];
    $tipo_documento = $_POST['tipdoc_descri'];

    //escapar los datos para que acepte comillas simples
    $per_nombres = pg_escape_string($conexion, $nombres);
    $per_apellidos = pg_escape_string($conexion, $apellidos);
    $per_email = pg_escape_string($conexion, $email);
    $per_estado = pg_escape_string($conexion, $estado);
    $tipdoc_descri = pg_escape_string($conexion, $tipo_documento);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_personas(
        {$_POST['per_cod']},
        '$per_nombres',
        '$per_apellidos',
        '{$_POST['per_nrodoc']}',
        '{$_POST['per_telefono']}',
        '$per_email',
        {$_POST['tipdoc_cod']},
        '$per_estado',
        {$_POST['operacion']},
        {$_POST['usu_cod']},
        '{$_POST['usu_login']}',
        '{$_POST['transaccion']}',
        '$tipdoc_descri'
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "1") !== false) {
        $response = array(
            "mensaje" => "ESTA PERSONA YA EXISTE",
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
    $perCod = "select coalesce (max(per_cod),0)+1 as codigo from personas;";

    $codigo = pg_query($conexion, $perCod);
    $codigoPer = pg_fetch_assoc($codigo);
    echo json_encode($codigoPer);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select 
            p.per_cod,
            p.per_nombres,
            p.per_apellidos,
            p.per_nrodoc,
            p.tipdoc_cod,
            td.tipdoc_descri,
            p.per_telefono,
            p.per_email,
            p.per_estado 
            from personas p 
            join tipo_documento td on td.tipdoc_cod = p.tipdoc_cod
            order by per_cod;";
    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>