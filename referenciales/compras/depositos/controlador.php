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
    $dep_descri = pg_escape_string($conexion, $_POST['dep_descri']);
    $dep_estado = pg_escape_string($conexion, $_POST['dep_estado']);
    $ciu_descripcion = pg_escape_string($conexion, $_POST['ciu_descripcion']);
    $emp_razonsocial = pg_escape_string($conexion, $_POST['emp_razonsocial']);
    $suc_descri = pg_escape_string($conexion, $_POST['suc_descri']);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_depositos(
        {$_POST['dep_cod']},
        {$_POST['suc_cod']},
        {$_POST['emp_cod']},
        {$_POST['ciu_cod']},
        '$dep_descri',
        '$dep_estado',
        {$_POST['operacion']},
        {$_POST['usu_cod']},
        '{$_POST['usu_login']}',
        '{$_POST['transaccion']}',
        '$ciu_descripcion',
        '$emp_razonsocial',
        '$suc_descri'
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "1") !== false) {
        $response = array(
            "mensaje" => "ESTE DEPÓSITO YA EXISTE YA EXISTE",
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
    $depCod = "select coalesce (max(dep_cod),0)+1 as codigo from depositos;";

    $codigo = pg_query($conexion, $depCod);
    $codigoDeposito = pg_fetch_assoc($codigo);
    echo json_encode($codigoDeposito);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select 
            d.dep_cod,
            d.dep_descri,
            d.suc_cod,
            s.suc_descri,
            d.emp_cod,
            e.emp_razonsocial,
            d.ciu_cod,
            c.ciu_descripcion,
            d.dep_estado 
            from depositos d 
            join ciudad c on c.ciu_cod = d.ciu_cod
            join sucursales s on s.suc_cod = d.suc_cod and s.emp_cod = d.emp_cod 
            join empresa e on e.emp_cod = s.emp_cod
            order by d.dep_cod;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>