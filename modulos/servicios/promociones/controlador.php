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

    //ESCAPAMOS LOS DATOS CAPTURADOS
    $promdes_estado = pg_escape_string($conexion, $_POST['promdes_estado']);
    $usu_login = pg_escape_string($conexion, $_POST['usu_login']);
    $suc_descri = pg_escape_string($conexion, $_POST['suc_descri']);
    $emp_razonsocial = pg_escape_string($conexion, $_POST['emp_razonsocial']);
    $promdes_descri = pg_escape_string($conexion, $_POST['promdes_descri']);
    $tiprom_descri = pg_escape_string($conexion, $_POST['tiprom_descri']);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_promociones_descuentos_cab(
        {$_POST['promdes_cod']},
        '{$_POST['promdes_fecha']}',
        '{$_POST['promdes_valor']}',
        '$promdes_descri',
        '$promdes_estado',
        {$_POST['suc_cod']},
        {$_POST['emp_cod']},
        {$_POST['usu_cod']},
        {$_POST['tiprom_cod']},
        {$_POST['operacion_cab']},
        '$suc_descri',
        '$emp_razonsocial',
        '$usu_login',
        '$tiprom_descri',
        '{$_POST['transaccion']}'
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "err_valor") !== false) {
        $response = array(
            "mensaje" => "EL VALOR INGRESADO YA SE ENCUENTRA REGISTRADO",
            "tipo" => "error"
        );
    } else if (strpos($error, "err_descri") !== false) {
        $response = array(
            "mensaje" => "LA DESCRIPCIÓN INGRESADA YA SE ENCUENTRA REGISTRADA",
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
    $promdesCod = "select coalesce (max(promdes_cod),0)+1 as codigo from promociones_descuentos_cab;";

    $codigo = pg_query($conexion, $promdesCod);
    $codigoPromdes = pg_fetch_assoc($codigo);
    echo json_encode($codigoPromdes);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select * from v_promociones_descuentos_cab;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>