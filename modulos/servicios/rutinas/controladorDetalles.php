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

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_rutinas_det(
        {$_POST['rut_cod']},
        {$_POST['ejer_cod']},
        {$_POST['rutdet_series']},
        {$_POST['rutdet_repeticiones']},
        {$_POST['dia_cod']},
        {$_POST['equi_cod']},
        {$_POST['tipequi_cod']},
        {$_POST['operacion_det']}
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "err_det") !== false) {
        $response = array(
            "mensaje" => "EL EJERCICIO SELECCIONADO YA FUE ESTABLECIDO EN EL DÍA SELECCIONADO",
            "tipo" => "error"
        );
    } else {
        $response = array(
            "mensaje" => pg_last_notice($conexion),
            "tipo" => "success"
        );
    }
    echo json_encode($response);


} else if (isset($_POST['rut_cod'])){

    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select * from v_rutinas_det
            where rut_cod = {$_POST['rut_cod']};";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}

?>