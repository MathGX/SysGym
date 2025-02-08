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

$accon_usu = $_POST['accon_usu'];
$case = $_POST['case'];

if ($case == "control") {

    $sql0 = "update acceso_control
    set accon_intentos = accon_intentos + 1
    where accon_cod = (select ac.accon_cod 
        from acceso_control ac 
        where ac.accon_usu = '$accon_usu'
        order by ac.accon_fecha desc, ac.accon_hora desc 
        limit 1);";

    //se ejecuta el codigo sql
    pg_query($conexion, $sql0);

    $sql = "select 
    ac.accon_clave,
    ac.accon_intentos 
    from acceso_control ac 
    where ac.accon_usu = '$accon_usu'
    order by ac.accon_fecha desc, ac.accon_hora desc 
    limit 1;";

    //consultamos a la base de datos y guardamos el resultado
    $resultado = pg_query($conexion, $sql);
    //convertimos el resultado en un array asociativo
    $datos = pg_fetch_assoc($resultado);
    //convertimoas el array asociativo en json
    echo json_encode($datos);

} else if ($case == "observacion"){
    $accon_obs = $_POST['accon_obs'];

    $sql = "update acceso_control
    set accon_obs = '$accon_obs'
    where accon_cod = (select ac.accon_cod 
        from acceso_control ac 
        where ac.accon_usu = '$accon_usu'
        order by ac.accon_fecha desc, ac.accon_hora desc 
        limit 1);";

    // Ejecutar la consulta
    $resultado = pg_query($conexion, $sql);

    // Comprobar si la consulta se ejecutó correctamente
    if ($resultado) {
        echo json_encode(array("status" => "success", "message" => "Observacion actualizada"));
    } else {
        echo json_encode(array("status" => "error", "message" => "Error al actualizar observacion"));
}

    
}


?>