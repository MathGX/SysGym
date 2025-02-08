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
    $razonsocial = $_POST['emp_razonsocial'];
    $email = $_POST['emp_email'];
    $actividad = $_POST['emp_actividad'];
    $estado = $_POST['emp_estado'];

    //escapar los datos para que acepte comillas simples
    $emp_razonsocial = pg_escape_string($conexion, $razonsocial);
    $emp_email = pg_escape_string($conexion, $email);
    $emp_actividad = pg_escape_string($conexion, $actividad);
    $emp_estado = pg_escape_string($conexion, $estado);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_empresa(
        {$_POST['emp_cod']},
        '$emp_razonsocial',
        '{$_POST['emp_ruc']}',
        '{$_POST['emp_telefono']}',
        '$emp_email',
        '$emp_actividad',
        '$emp_estado',
        '{$_POST['emp_timbrado']}',
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
            "mensaje" => "ESTA EMPRESA YA EXISTE",
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
    $depCod = "select coalesce (max(emp_cod),0)+1 as codigo from empresa;";

    $codigo = pg_query($conexion, $depCod);
    $codigoDeposito = pg_fetch_assoc($codigo);
    echo json_encode($codigoDeposito);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select 
            e.emp_cod,
            e.emp_razonsocial,
            e.emp_ruc,
            e.emp_timbrado,
            e.emp_telefono,
            e.emp_email,
            e.emp_actividad,
            e.emp_estado
            from empresa e 
            order by e.emp_cod;";
    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>