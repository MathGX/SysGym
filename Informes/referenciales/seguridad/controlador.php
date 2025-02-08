<?php
//Establecemos la salida en formato json
header("Content-type: application/json; charset=utf-8");
//Establecemos el array de array asociativos
$tabla = [array('tabla' => "ACCESO"), 
        array('tabla' => "USUARIOS"), 
        array('tabla' => "ASIGNACIÓN DE PERMISOS"), 
        array('tabla' => "MODULOS"), 
        array('tabla' => "PERMISOS"), 
        array('tabla' => "PERFILES"), 
        array('tabla' => "GUI"), 
        array('tabla' => "PERMISO POR PERFIL"), 
        array('tabla' => "GUI POR PERFIL"),];
//Convertimos el array de array en un formato de json string
echo json_encode($tabla);
?>