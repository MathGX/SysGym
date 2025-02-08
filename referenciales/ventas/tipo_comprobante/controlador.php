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
    $descripcion = $_POST['tipcomp_descri'];
    $estado = $_POST['tipcomp_estado'];

    //escapar los datos para que acepte comillas simples
    $tipcomp_descri = pg_escape_string($conexion, $descripcion);
    $tipcomp_estado = pg_escape_string($conexion, $estado);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_tipoComprobantes(
        {$_POST['tipcomp_cod']},
        '$tipcomp_descri',
        '$tipcomp_estado',
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
            "mensaje" => "ESTE TIPO DE DOCUMENTO YA EXISTE",
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
    $tipCompCod = "select coalesce (max(tipcomp_cod),0)+1 as codigo from tipo_comprobante;";

    $codigo = pg_query($conexion, $tipCompCod);
    $codigoTipComp = pg_fetch_assoc($codigo);
    echo json_encode($codigoTipComp);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select 
            tc.tipcomp_cod,
            tc.tipcomp_descri,
            tc.tipcomp_estado 
            from tipo_comprobante tc
            order by tc.tipcomp_cod;";
    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>