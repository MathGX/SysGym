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
    $suconf_estado = pg_escape_string($conexion, $_POST['suconf_estado']);
    $conf_validacion = pg_escape_string($conexion, $_POST['conf_validacion']);
    $suc_descri = pg_escape_string($conexion, $_POST['suc_descri']);
    $emp_razonsocial = pg_escape_string($conexion, $_POST['emp_razonsocial']);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_suc_config(
        {$_POST['suconf_cod']},
        {$_POST['suc_cod']},
        {$_POST['emp_cod']},
        {$_POST['conf_cod']},
        '$suconf_estado',
        {$_POST['operacion']},
        {$_POST['usu_cod']},
        '{$_POST['usu_login']}',
        '{$_POST['transaccion']}',
        '$emp_razonsocial',
        '$suc_descri',
        '$conf_validacion'
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "1") !== false) {
        $response = array(
            "mensaje" => "ESTA SUCURSAL YA CUENTA CON LA CONFIGURACION",
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
    $suconfCod = "select coalesce (max(suconf_cod),0)+1 as codigo from suc_config;";

    $codigo = pg_query($conexion, $suconfCod);
    $codigoSuconf = pg_fetch_assoc($codigo);
    echo json_encode($codigoSuconf);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select 
        sc.suconf_cod,
        sc.emp_cod,
        e.emp_razonsocial,
        sc.suc_cod,
        s.suc_descri,
        sc.conf_cod,
        c.conf_descri,
        sc.suconf_estado 
    from suc_config sc 
        join sucursales s on s.suc_cod = sc.suc_cod and s.emp_cod = sc.emp_cod 
            join empresa e on e.emp_cod = s.emp_cod 
        join configuraciones c on c.conf_cod = sc.conf_cod 
    order by sc.suconf_cod;";
    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>