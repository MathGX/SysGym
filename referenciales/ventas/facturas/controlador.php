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

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_facturas(
        {$_POST['suc_cod']},
        {$_POST['emp_cod']},
        {$_POST['caj_cod']},
        '{$_POST['fac_nro']}'
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "1") !== false) {
        $response = array(
            "mensaje" => "LA CAJA DE ESTA SUCURSAL YA TIENE UN REGISTRO DE FACTURA",
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
    $sql = "select * from v_facturas";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>