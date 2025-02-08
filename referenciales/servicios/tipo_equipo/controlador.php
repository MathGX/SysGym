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
    $descripcion = $_POST['tipequi_descri'];
    $estado = $_POST['tipequi_estado'];

    //escapar los datos para que acepte comillas simples
    $tipequi_descri = pg_escape_string($conexion, $descripcion);
    $tipequi_estado = pg_escape_string($conexion, $estado);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_tipoEquipos(
        {$_POST['tipequi_cod']},
        '$tipequi_descri',
        '$tipequi_estado',
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
            "mensaje" => "ESTE TIPO DE EQUIPO YA EXISTE",
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
    $tipequiCod = "select coalesce (max(tipequi_cod),0)+1 as codigo from tipo_equipos;";

    $codigo = pg_query($conexion, $tipequiCod);
    $codigoTipEqui = pg_fetch_assoc($codigo);
    echo json_encode($codigoTipEqui);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select 
            te.tipequi_cod,
            te.tipequi_descri,
            te.tipequi_estado 
            from tipo_equipos te 
            order by te.tipequi_cod;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>