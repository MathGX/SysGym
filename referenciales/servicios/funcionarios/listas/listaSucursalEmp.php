<?php

header('Content-type: application/json; charset=utf-8');

/*require_once es como un import, si usamos más de una vez pasa por alto,  
es más recomendable y sí o sí se usa para llamar a otra clase, sin importar  
que las clases estén dentro de la misma carpeta*/

//importala clase conexion.php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$empre = $_POST['emp_razonsocial'];

//se realiza la consulta SQL a la base de datos con el filtro
$sql = "select 
        s.suc_cod,
        s.suc_descri
        from sucursales s
        join empresa e on e.emp_cod = s.emp_cod
        where e.emp_razonsocial like '%$empre%' and s.suc_estado ilike 'ACTIVO'
        order by suc_descri;";

//consultamos a la base de datos y guardamos el resultado
$resultado = pg_query($conexion, $sql);
//convertimos el resultado en un array asociativo
$datos = pg_fetch_all($resultado);
//convertimoas el array asociativo en json
echo json_encode($datos);


?>