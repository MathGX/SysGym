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
    $estado = $_POST['perfperm_estado'];
    $perfil = $_POST['perf_descri'];
    $permiso = $_POST['permi_descri'];

    //escapar los datos para que acepte comillas simples
    $perfperm_estado = pg_escape_string($conexion, $estado);
    $perf_descri = pg_escape_string($conexion, $perfil);
    $permi_descri = pg_escape_string($conexion, $permiso);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_perfilesPermisos(
        {$_POST['perfperm_cod']},
        {$_POST['perf_cod']},
        {$_POST['permi_cod']},
        '$perfperm_estado',
        {$_POST['operacion']},
        {$_POST['usu_cod']},
        '{$_POST['usu_login']}',
        '{$_POST['transaccion']}',
        '$perf_descri',
        '$permi_descri'
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "1") !== false) {
        $response = array(
            "mensaje" => "ESTE PERFIL YA CUENTA CON EL PERMISO",
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
    $perfpermCod = "select coalesce (max(perfperm_cod),0)+1 as codigo from perfiles_permisos;";

    $codigo = pg_query($conexion, $perfpermCod);
    $codigoPerfperm = pg_fetch_assoc($codigo);
    echo json_encode($codigoPerfperm);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select 
            pp.perfperm_cod,
            pp.perf_cod,
            p.perf_descri,
            pp.permi_cod,
            p2.permi_descri,
            pp.perfperm_estado 
            from perfiles_permisos pp
            join perfiles p on p.perf_cod = pp.perf_cod 
            join permisos p2 on p2.permi_cod = pp.permi_cod 
            order by pp.perfperm_cod;";
    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>