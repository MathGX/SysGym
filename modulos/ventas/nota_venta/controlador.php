<?php
//Establecemos el retorno del documento en formato json
header("Content-type: application/json; charset=utf-8");
//Solicitamos la clase de Conexion
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

//Consultamos si existe la variable operacion
if (isset($_POST['operacion_cab'])) {

    //captura de datos desde el front-end
    $concepto = $_POST['notven_concepto'];
    $estado = $_POST['notven_estado'];
        
    //escapar los datos para que acepte comillas simples
    $notven_concepto = pg_escape_string($conexion, $concepto);
    $notven_estado = pg_escape_string($conexion, $estado);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_nota_venta_cab(
        {$_POST['notven_cod']},
        '{$_POST['notven_fecha']}',
        '{$_POST['notven_nronota']}',
        '$notven_concepto',
        '$notven_estado',
        {$_POST['tipcomp_cod']},
        {$_POST['ven_cod']},
        {$_POST['suc_cod']},
        {$_POST['emp_cod']},
        {$_POST['usu_cod']},
        {$_POST['cli_cod']},
        {$_POST['operacion_cab']}
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "repe") !== false) {
        $response = array(
            "mensaje" => "ESTA NOTA YA ESTÁ CARGADA",
            "tipo" => "error"
        );
    } else {
        $response = array(
            "mensaje" => pg_last_notice($conexion),
            "tipo" => "success"
        );
    }
    echo json_encode($response);


} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select * from v_nota_venta_cab;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>