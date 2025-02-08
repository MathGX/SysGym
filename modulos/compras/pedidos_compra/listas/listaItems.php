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
        i.tipimp_cod,
        i.itm_descri,
        i.itm_costo as pedcomdet_precio,
        i.uni_cod,
        um.uni_descri||' ('||um.uni_simbolo||')' as uni_descri
        from items i 
        join tipo_item ti on ti.tipitem_cod = i.tipitem_cod
        join unidad_medida um on um.uni_cod = i.uni_cod 
        where itm_descri ilike '%$item%' and i.itm_estado ilike 'ACTIVO'
        order by i.itm_descri;";
        
//consultamos a la base de datos y guardamos el resultado
$resultado = pg_query($conexion, $sql);
//convertimos el resultado en un array asociativo
$datos = pg_fetch_all($resultado);

// Filtramos los resultados según las condiciones deseadas
$filtroItem = array_filter($datos, function($respItem) {
        return !($respItem['tipitem_cod'] == '1' && $respItem['itm_descri'] != 'FLETE');
});

// Si hay elementos filtrados, los devolvemos; de lo contrario, enviamos un mensaje
if (empty($filtroItem)) {
        echo json_encode(
                array(
                        "fila" => "No se encuentra el dato", 
                        "true" => true));
} else {
        echo json_encode(
                array_values($filtroItem)
        ); // Devuelve un array con los valores filtrados
}


?>