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
    $suc_descri = pg_escape_string($conexion, $_POST['suc_descri']);
    $suc_direccion = pg_escape_string($conexion, $_POST['suc_direccion']);
    $suc_email = pg_escape_string($conexion, $_POST['suc_email']);
    $suc_estado = pg_escape_string($conexion, $_POST['suc_estado']);
    $emp_razonsocial = pg_escape_string($conexion, $_POST['emp_razonsocial']);
    $ciu_descripcion = pg_escape_string($conexion, $_POST['ciu_descripcion']);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_sucursales(
        {$_POST['suc_cod']},
        {$_POST['emp_cod']},
        '$suc_descri',
        '{$_POST['suc_telefono']}',
        '$suc_direccion',
        '$suc_estado',
        {$_POST['ciu_cod']},
        '$suc_email',
        {$_POST['operacion']},
        {$_POST['usu_cod']},
        '{$_POST['usu_login']}',
        '{$_POST['transaccion']}',
        '$emp_razonsocial',
        '$ciu_descripcion'
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "1") !== false) {
        $response = array(
            "mensaje" => "ESTA SUCURSAL YA EXISTE YA EXISTE",
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
    $sucCod = "select coalesce (max(suc_cod),0)+1 as codigo from sucursales;";

    $codigo = pg_query($conexion, $sucCod);
    $codigoSucursal = pg_fetch_assoc($codigo);
    echo json_encode($codigoSucursal);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select 
            s.suc_cod,
            s.emp_cod,
            e.emp_razonsocial,
            s.suc_descri,
            s.suc_telefono,
            s.suc_direccion,
            s.ciu_cod,
            c.ciu_descripcion,
            s.suc_email,
            s.suc_estado 
            from sucursales s
            join empresa e on e.emp_cod = s.emp_cod 
            join ciudad c on c.ciu_cod = s.ciu_cod
            order by suc_cod;";
    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>