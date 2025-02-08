<?php
//Establecemos la salida en formato json
header("Content-type: application/json; charset=utf-8");
//Establecemos el array de array asociativos
$tabla = [array('tabla' => "PEDIDOS DE COMPRA"), 
        array('tabla' => "PRESUPUESTO DE PORVEEDORES"), 
        array('tabla' => "ORDENES DE COMPRA"), 
        array('tabla' => "COMPRAS"),
        array('tabla' => "CUENTAS A PAGAR"),
        array('tabla' => "LIBRO DE COMPRAS"),
        array('tabla' => "STOCK"),
        array('tabla' => "AJUSTES DE INVENTARIO"),
        array('tabla' => "NOTAS DE COMPRA"),];
//Convertimos el array de array en un formato de json string
echo json_encode($tabla);
?>