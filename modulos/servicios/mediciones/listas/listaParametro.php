<?php

header('Content-type: application/json; charset=utf-8');

/*require_once es como un import, si usamos más de una vez pasa por alto,  
es más recomendable y sí o sí se usa para llamar a otra clase, sin importar  
que las clases estén dentro de la misma carpeta*/

//importala clase conexion.php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$param_descri = $_POST['param_descri'];

//se realiza la consulta SQL a la base de datos con el filtro
$sql = "select 
        pm.param_cod,
        pm.param_descri,
        um.uni_descri||' ('||um.uni_simbolo||')' as uni_descri,
        pm.param_formula
from parametros_medicion pm
        join unidad_medida um on um.uni_cod = pm.uni_cod 
where param_descri ilike '%$param_descri%' and param_estado != 'INACTIVO'
order by param_descri;";
        
//consultamos a la base de datos y guardamos el resultado
$resultado = pg_query($conexion, $sql);
//convertimos el resultado en un array asociativo
$datos = pg_fetch_all($resultado);
//se consulta si el array asociativo está vacío, de ser así se envía un mensaje al front-end
if (empty($datos)) {
        echo json_encode(
                array(
                        "fila" => "No se encuentra el dato",
                        "true" => true
                )
        );
        // si datos no está vacío convertimoas el array asociativo en json
} else {
        echo json_encode($datos);
}


?>