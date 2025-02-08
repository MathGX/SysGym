<?php

header('Content-type: application/json; charset=utf-8');

/*require_once es como un import, si usamos más de una vez pasa por alto,  
es más recomendable y sí o sí se usa para llamar a otra clase, sin importar  
que las clases estén dentro de la misma carpeta*/

//importala clase conexion.php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$perfil = $_POST['perf_descri'];

//se realiza la consulta SQL a la base de datos con el filtro
$sql = "select 
pp.perfperm_cod,
pp.permi_cod,
p.permi_descri
from perfiles_permisos pp 
join permisos p on p.permi_cod = pp.permi_cod
join perfiles p2 on p2.perf_cod = pp.perf_cod 
where p2.perf_descri like '%$perfil%' and pp.perfperm_estado ilike 'ACTIVO'
order by p.permi_descri;";

//consultamos a la base de datos y guardamos el resultado
$resultado = pg_query($conexion, $sql);
//convertimos el resultado en un array asociativo
$datos = pg_fetch_all($resultado);
//convertimoas el array asociativo en json
echo json_encode($datos);


?>