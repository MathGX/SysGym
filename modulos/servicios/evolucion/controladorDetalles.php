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

    $evodet_registro_act = str_replace(",", ".", $_POST['evodet_registro_act']);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_evolucion_det(
        {$_POST['evo_cod']},
        {$_POST['param_cod']},
        {$_POST['evodet_registro_ant']},
        $evodet_registro_act,
        {$_POST['operacion_det']},
        {$_POST['med_cod']}
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "1") !== false) {
        $response = array(
            "mensaje" => "ESTE PARÁMETRO YA ESTÁ REGISTRADO",
            "tipo" => "error"
        );
    } else if (strpos($error, "2") !== false) {
        $response = array(
            "mensaje" => "ESTE PARÁMETRO DEBE SER INSERTADO",
            "tipo" => "error"
        );
    } else {
        $response = array(
            "mensaje" => pg_last_notice($conexion),
            "tipo" => "success"
        );
    }
    echo json_encode($response);


} else if (isset($_POST['evo_cod'])){

    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select * from v_evolucion_det
            where evo_cod = {$_POST['evo_cod']};";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}

?>