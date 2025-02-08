<?php
//Establecemos la salida en formato json
header("Content-type: application/json; charset=utf-8");
//Establecemos el array de array asociativos
$tabla = [array('tabla' => "PERSONAS"), 
        array('tabla' => "CARGOS"), 
        array('tabla' => "FUNCIONARIOS"), 
        array('tabla' => "DIAS"), 
        array('tabla' => "TIPOS DE EQUIPO"), 
        array('tabla' => "EQUIPOS"), 
        array('tabla' => "PARAMETROS DE MEDICION"), 
        array('tabla' => "UNIDADES DE MEDIDA"), 
        array('tabla' => "EJERCICIOS"),
        array('tabla' => "TIPOS DE RUTINA"),
        array('tabla' => "TIPOS DE PLAN ALIMENTICIO"),
        array('tabla' => "COMIDAS"),
        array('tabla' => "HORARIOS DE COMIDA"),];
//Convertimos el array de array en un formato de json string
echo json_encode($tabla);
?>