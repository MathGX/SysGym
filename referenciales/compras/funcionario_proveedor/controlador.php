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

    //escapar los datos para que acepte comillas simples
    $funprov_nombres = pg_escape_string($conexion, $_POST['funprov_nombres']);
    $funprov_apellidos = pg_escape_string($conexion, $_POST['funprov_apellidos']);
    $funprov_estado = pg_escape_string($conexion, $_POST['funprov_estado'] );
    $pro_razonsocial = pg_escape_string($conexion, $_POST['pro_razonsocial']);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_funcionario_proveedor(
        {$_POST['funprov_cod']},
        '$funprov_nombres',
        '$funprov_apellidos',
        '{$_POST['funprov_nro_doc']}',
        {$_POST['pro_cod']},
        {$_POST['tiprov_cod']},
        '$funprov_estado',
        {$_POST['operacion']},
        {$_POST['usu_cod']},
        '{$_POST['usu_login']}',
        '{$_POST['transaccion']}',
        '$pro_razonsocial'
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "err_fun") !== false) {
        $response = array(
            "mensaje" => "ESTE FUNCIONARIO YA SE ENCUENTRA ASOCIADO A UN PROVEEDOR",
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
    $proCod = "select coalesce (max(funprov_cod),0)+1 as codigo from funcionario_proveedor;";

    $codigo = pg_query($conexion, $proCod);
    $codigoProveedor = pg_fetch_assoc($codigo);
    echo json_encode($codigoProveedor);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select 
                fp.funprov_cod,
                fp.funprov_nombres,
                fp.funprov_apellidos,
                fp.funprov_nombres||' '||fp.funprov_apellidos funcionario,
                fp.funprov_nro_doc,
                case when length(fp.funprov_nro_doc) <=0 then 'SIN NRO DOC' else fp.funprov_nro_doc end as funprov_nro_doc2,
                fp.funprov_estado,
                fp.pro_cod,
                fp.tiprov_cod,
                p.pro_razonsocial||' - '||tp.tiprov_descripcion as pro_razonsocial,
                p.pro_ruc
            from funcionario_proveedor fp 
                join proveedor p on p.pro_cod = fp.pro_cod and p.tiprov_cod = fp.tiprov_cod 
                    join tipo_proveedor tp on tp.tiprov_cod = p.tiprov_cod
            order by fp.funprov_cod desc;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>