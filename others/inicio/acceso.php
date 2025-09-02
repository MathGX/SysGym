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

$usuario = $_POST['usu_login'];
$contrasena = $_POST['usu_contrasena'];

$sql = "select u.usu_cod, 
u.usu_login,
u.fun_cod,
p.per_nombres,
p.per_apellidos,
p.per_email,
u.perf_cod,
p2.perf_descri,
e.emp_cod,
e.emp_razonsocial,
e.emp_timbrado,
s.suc_cod,
s.suc_descri,
s.suc_direccion,
s.suc_telefono,
u.mod_cod,
m.mod_descri,
u.usu_estado
from usuarios u
join funcionarios f on f.fun_cod = u.fun_cod
join sucursales s on s.suc_cod = f.suc_cod and s.emp_cod = f.emp_cod 
join empresa e on e.emp_cod = s.emp_cod 
join modulos m on m.mod_cod = u.mod_cod 
join personas p on p.per_cod = f.per_cod 
join perfiles p2 on p2.perf_cod = u.perf_cod
where lower(usu_login) = lower('$usuario') and usu_contrasena = md5('$contrasena') ;";
//consultamos a la base de datos y guardamos el resultado
$resultado = pg_query($conexion, $sql);
//convertimos el resultado en un array asociativo
$datos = pg_fetch_assoc($resultado);
//si la consulta nos devuelve algo, guardamos el array en una variable de sesión
if (!(!$datos)) {
    $_SESSION['usuarios'] = $datos;
}
//convertimoas el array asociativo en json
echo json_encode($datos);



?>