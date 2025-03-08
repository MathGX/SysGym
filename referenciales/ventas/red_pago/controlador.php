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
    $descripcion = $_POST['redpag_descri'];
    $estado = $_POST['redpag_estado'];

    //escapar los datos para que acepte comillas simples
    $redpag_descri = pg_escape_string($conexion, $descripcion);
    $redpag_estado = pg_escape_string($conexion, $estado);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_red_pago(
        {$_POST['redpag_cod']},
        '$redpag_descri',
        '$redpag_estado',
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
            "mensaje" => "ESTA RED DE PAGO YA SE ENCUENTRA REGISTRADA",
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
    $redpagCod = "select coalesce (max(redpag_cod),0)+1 as codigo from red_pago;";

    $codigo = pg_query($conexion, $redpagCod);
    $codigoredpag = pg_fetch_assoc($codigo);
    echo json_encode($codigoredpag);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select 
            rp.redpag_cod,
            rp.redpag_descri,
            rp.redpag_estado 
            from red_pago rp 
            order by rp.redpag_cod;";
    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>