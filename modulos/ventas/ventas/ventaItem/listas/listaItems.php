<?php

header('Content-type: application/json; charset=utf-8');

/*require_once es como un import, si usamos más de una vez pasa por alto,  
es más recomendable y sí o sí se usa para llamar a otra clase, sin importar  
que las clases estén dentro de la misma carpeta*/

//importala clase conexion.php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

// $pedven_cod = $_POST["pedven_cod"];
$itm_descri = pg_escape_string($conexion,$_POST['itm_descri']);
$dep_cod = $_POST['dep_cod'];
$suc_cod = $_POST['suc_cod'];
$emp_cod = $_POST['emp_cod'];

//se realiza la consulta SQL a la base de datos con el filtro
$sql = "select
        s.itm_cod,
        s.tipitem_cod,
        i.tipimp_cod,
        i.tipitem_cod,
        i.itm_descri,
        s.sto_cantidad as vendet_cantidad,
        um.uni_descri||' ('||um.uni_simbolo||')' as uni_descri,
        case
                when i.itm_precio < 1000 then round(i.itm_costo + (i.itm_costo*i.itm_precio/100))
                else i.itm_precio
        end as vendet_precio
from stock s 
        join items i on i.itm_cod = s.itm_cod and i.tipitem_cod = s.tipitem_cod
                join tipo_item ti on ti.tipitem_cod = i.tipitem_cod
                join unidad_medida um on um.uni_cod = i.uni_cod 
where s.dep_cod = $dep_cod 
        and s.suc_cod = $suc_cod 
        and s.emp_cod = $emp_cod 
        and i.itm_descri ilike '%$itm_descri%'
        and s.tipitem_cod != 5
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