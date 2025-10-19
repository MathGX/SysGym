<?php

header('Content-type: application/json; charset=utf-8');

/*require_once es como un import, si usamos más de una vez pasa por alto,  
es más recomendable y sí o sí se usa para llamar a otra clase, sin importar  
que las clases estén dentro de la misma carpeta*/

//importala clase conexion.php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$item = $_POST['itm_descri'];

//se realiza la consulta SQL a la base de datos con el filtro
$sql = "select 
        i.itm_cod,
        i.tipitem_cod,
        i.itm_descri
from items i 
        join tipo_item ti on ti.tipitem_cod = i.tipitem_cod
where itm_descri ilike upper('%$item%')
        and i.itm_cod != 3
        and i.tipitem_cod = 1
        and i.itm_estado = 'ACTIVO'
order by i.itm_descri;";

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