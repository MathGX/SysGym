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
    $login = $_POST['usu_login'];
    $contrasena = $_POST['usu_contrasena'];
    $estado = $_POST['usu_estado'];
    $perfil = $_POST['perf_descri'];
    $modulo = $_POST['mod_descri'];
    $fun = $_POST['funcionarios'];

    //escapar los datos para que acepte comillas simples
    $usu_login = pg_escape_string($conexion, $login);
    $usu_contrasena = pg_escape_string($conexion, $contrasena);
    $usu_estado = pg_escape_string($conexion, $estado);
    $perf_descri = pg_escape_string($conexion, $perfil);
    $mod_descri = pg_escape_string($conexion, $modulo);
    $funcionarios = pg_escape_string($conexion, $fun);


    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_usuarios(
        {$_POST['usu_cod']},
        '$usu_login',
        '$usu_contrasena',
        '$usu_estado',
        {$_POST['perf_cod']},
        {$_POST['mod_cod']},
        {$_POST['fun_cod']},
        {$_POST['operacion']},
        {$_POST['usu_cod_reg']},
        '{$_POST['usu_login_reg']}',
        '{$_POST['transaccion']}',
        '$perf_descri',
        '$mod_descri',
        '$funcionarios'
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "1") !== false) {
        $response = array(
            "mensaje" => "ESTE USUARIO Y EXISTE",
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
    $usuCod = "select coalesce (max(usu_cod),0)+1 as codigo from usuarios;";

    $codigo = pg_query($conexion, $usuCod);
    $codigousu = pg_fetch_assoc($codigo);
    echo json_encode($codigousu);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select 
            u.usu_cod,
            u.usu_login,
            u.usu_contrasena,
            u.usu_fechacrea,
            u.usu_estado,
            u.perf_cod,
            p.perf_descri,
            u.mod_cod,
            m.mod_descri,
            u.fun_cod,
            p2.per_nrodoc,
            p2.per_nombres||' '||p2.per_apellidos as funcionarios
            from usuarios u 
            join perfiles p on p.perf_cod = u.perf_cod
            join modulos m on m.mod_cod = u.mod_cod 
            join funcionarios f on f.fun_cod = u.fun_cod 
            join personas p2 on p2.per_cod = f.per_cod 
            order by u.usu_cod;";
    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>