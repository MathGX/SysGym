<?php

header('Content-type: application/json; charset=utf-8');

/*require_once es como un import, si usamos más de una vez pasa por alto,  
es más recomendable y sí o sí se usa para llamar a otra clase, sin importar  
que las clases estén dentro de la misma carpeta*/

//importala clase conexion.php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$itm_descri = $_POST['itm_descri'];
$tipcomp_cod = $_POST['tipcomp_cod'];
$com_cod = $_POST['com_cod'];

//se realiza la consulta SQL a la base de datos con el filtro
if ($tipcomp_cod == "1" || $tipcomp_cod == "3") {
        $sql = "select 
        i.itm_cod,
        i.tipitem_cod,
        i.tipimp_cod,
        i.itm_descri,
        cd.dep_cod,
        d.dep_descri,
        cd.comdet_cantidad as notacomdet_cantidad,
        um.uni_descri||' ('||um.uni_simbolo||')' as uni_descri,
        cd.comdet_precio as notacomdet_precio
        from items i 
                join unidad_medida um on um.uni_cod = i.uni_cod 
	        join tipo_item ti on ti.tipitem_cod = i.tipitem_cod
	        join stock s on s.itm_cod = i.itm_cod and s.tipitem_cod = i.tipitem_cod 
	        	join compra_det cd on cd.itm_cod = s.itm_cod and cd.tipitem_cod = s.tipitem_cod
	        		join compra_cab cc on cc.com_cod = cd.com_cod 
	        		join depositos d on d.dep_cod = cd.dep_cod 
        where itm_descri ilike '%$itm_descri%' and cc.com_cod = $com_cod 
        order by i.itm_descri;";
} else if ($tipcomp_cod == "2") {
        $sql = "select 
        i.itm_cod,
        i.tipitem_cod,
        i.tipimp_cod,
        i.itm_descri,
        i.itm_costo as notacomdet_precio,
        i.uni_cod,
        um.uni_descri||' ('||um.uni_simbolo||')' as uni_descri
        from items i 
                join tipo_item ti on ti.tipitem_cod = i.tipitem_cod
                join unidad_medida um on um.uni_cod = i.uni_cod 
        where itm_descri ilike '%$itm_descri%' and i.itm_estado ilike 'ACTIVO'
        order by i.itm_descri;";
}
        
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