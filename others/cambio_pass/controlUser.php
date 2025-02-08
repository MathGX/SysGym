<?php
//habilitamos las variables de sesión
session_start();

header('Content-type: application/json; charset=utf-8');

/*require_once es como un import, si usamos más de una vez pasa por alto,  
es más recomendable y sí o sí se usa para llamar a otra clase, sin importar  
que las clases estén dentro de la misma carpeta*/

//importala clase conexion.php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$case = $_POST['case'];

if ($case == "verificar") {

    $usu_login = $_POST['usu_login'];

    $sql = "select 
    u.usu_login,
    u.usu_estado,
    p.per_email 
    from usuarios u
    join funcionarios f on f.fun_cod = u.fun_cod 
        join personas p on p.per_cod =f.per_cod 
    where u.usu_login = '$usu_login';";

    //consultamos a la base de datos y guardamos el resultado
    $resultado = pg_query($conexion, $sql);
    //convertimos el resultado en un array asociativo
    $datos = pg_fetch_assoc($resultado);
    //convertimoas el array asociativo en json
    echo json_encode($datos);

} else if ($case == "control") {
    $actpas_usu = $_POST['actpas_usu'];

    $sql0 = "update actualizar_pass_user
    set actpas_intentos = actpas_intentos + 1
    where actpas_cod = (select ap.actpas_cod 
        from actualizar_pass_user ap 
        where ap.actpas_usu = '$actpas_usu'
        order by ap.actpas_fecha desc, ap.actpas_hora desc 
        limit 1);
";

    //se ejecuta el codigo sql
    pg_query($conexion, $sql0);

    $sql = "select 
    ac.actpas_clave,
    ac.actpas_intentos 
    from actualizar_pass_user ac 
    where ac.actpas_usu = '$actpas_usu'
    order by ac.actpas_fecha desc, ac.actpas_hora desc 
    limit 1;";

    //consultamos a la base de datos y guardamos el resultado
    $resultado = pg_query($conexion, $sql);
    //convertimos el resultado en un array asociativo
    $datos = pg_fetch_assoc($resultado);
    //convertimoas el array asociativo en json
    echo json_encode($datos);

} else if ($case == "observacion") {
    $actpas_usu = $_POST['actpas_usu'];
    $actpas_obs = $_POST['actpas_obs'];

    $sql = "update actualizar_pass_user
    set actpas_obs = '$actpas_obs'
    where actpas_cod = (select ac.actpas_cod 
        from actualizar_pass_user ac 
        where ac.actpas_usu = '$actpas_usu'
        order by ac.actpas_fecha desc, ac.actpas_hora desc 
        limit 1);";

    // Ejecutar la consulta
    $resultado = pg_query($conexion, $sql);

    // Comprobar si la consulta se ejecutó correctamente
    if ($resultado) {
        echo json_encode(array("status" => "success", "message" => "Observacion actualizada"));
    } else {
        echo json_encode(array("status" => "error", "message" => "Error al actualizar observacion"));
    }
} else if ($case == "actualizar") {
    $usu_login = $_POST['usu_login'];
    $usu_contrasena = pg_escape_string($conexion, $_POST['usu_contrasena']);
    $actpas_obs = $_POST['actpas_obs'];

    $sql = "update usuarios
    set usu_contrasena = md5('$usu_contrasena')
    where usu_login = '$usu_login'";

    // Ejecutar la consulta
    $resultado = pg_query($conexion, $sql);

    // Comprobar si la consulta se ejecutó correctamente
    if ($resultado) { 
        $sql = "update actualizar_pass_user
        set actpas_obs = '$actpas_obs'
        where actpas_cod = (select ac.actpas_cod 
            from actualizar_pass_user ac 
            where ac.actpas_usu = '$usu_login'
            order by ac.actpas_fecha desc, ac.actpas_hora desc 
            limit 1);";
            
        // Ejecutar la consulta
        $resultado = pg_query($conexion, $sql);
        
        echo json_encode(array("status" => "success", "message" => "Contraseña actualizada"));
    } else {
        echo json_encode(array("status" => "error", "message" => "Error al actualizar contraseña"));
    }
}


?>