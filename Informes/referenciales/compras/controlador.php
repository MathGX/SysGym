<?php
//Establecemos la salida en formato json
header("Content-type: application/json; charset=utf-8");
//Establecemos el array de array asociativos
$tabla = [array('tabla' => "EMPRESAS"), 
        array('tabla' => "SUCURSALES"), 
        array('tabla' => "DEPOSITOS"), 
        array('tabla' => "CIUDADES"), 
        array('tabla' => "TIPOS DE IMPUESTO"), 
        array('tabla' => "TIPOS DE ITEM"), 
        array('tabla' => "ITEMS"), 
        array('tabla' => "TIPOS DE PROVEEDOR"), 
        array('tabla' => "PROVEEDORES"),];
//Convertimos el array de array en un formato de json string
echo json_encode($tabla);
?>