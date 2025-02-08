<?php
//Establecemos la salida en formato json
header("Content-type: application/json; charset=utf-8");
//Establecemos el array de array asociativos
$tabla = [array('tabla' => "CLIENTES"), 
        array('tabla' => "ENTIDADES FINANCIERAS"), 
        array('tabla' => "MARCAS DE TARJETA"), 
        array('tabla' => "ENTIDADES ADHERIDAS"), 
        array('tabla' => "CAJAS"), 
        array('tabla' => "FORMAS DE COBRO"), 
        array('tabla' => "TIPOS DE DOCUMENTO"), 
        array('tabla' => "TIPOS DE COMPROBANTE")];
//Convertimos el array de array en un formato de json string
echo json_encode($tabla);
?>