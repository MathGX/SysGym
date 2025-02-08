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
    $razonsocial = $_POST['pro_razonsocial'];
    $email = $_POST['pro_email'];
    $direccion = $_POST['pro_direccion'];
    $estado = $_POST['pro_estado'];
    $tipo_proveedor = $_POST['tiprov_descripcion'];

    //escapar los datos para que acepte comillas simples
    $pro_razonsocial = pg_escape_string($conexion, $razonsocial);
    $pro_email = pg_escape_string($conexion, $email);
    $pro_direccion = pg_escape_string($conexion, $direccion);
    $pro_estado = pg_escape_string($conexion, $estado);
    $tiprov_descripcion = pg_escape_string($conexion, $tipo_proveedor);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_proveedores(
        {$_POST['pro_cod']},
        {$_POST['tiprov_cod']},
        '$pro_razonsocial',
        '{$_POST['pro_ruc']}',
        '$pro_direccion',
        '{$_POST['pro_telefono']}',
        '$pro_email',
        '$pro_estado',
        '{$_POST['pro_timbrado']}',
        {$_POST['operacion']},
        {$_POST['usu_cod']},
        '{$_POST['usu_login']}',
        '{$_POST['transaccion']}',
        '$tiprov_descripcion'
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "1") !== false) {
        $response = array(
            "mensaje" => "ESTE PROVEEDOR YA EXISTE",
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
    $proCod = "select coalesce (max(pro_cod),0)+1 as codigo from proveedor;";

    $codigo = pg_query($conexion, $proCod);
    $codigoProveedor = pg_fetch_assoc($codigo);
    echo json_encode($codigoProveedor);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select 
            p.pro_cod,
            p.tiprov_cod, 
            tp.tiprov_descripcion,
            p.pro_razonsocial,
            p.pro_ruc,
            p.pro_timbrado,
            p.pro_direccion,
            p.pro_telefono,
            p.pro_email,
            p.pro_estado 
            from proveedor p 
            join tipo_proveedor tp on tp.tiprov_cod = p.tiprov_cod
            order by pro_cod;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>