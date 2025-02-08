<?php
//Establecemos la salida en formato json
header("Content-type: application/json; charset=utf-8");
//Establecemos el array de array asociativos
$com_tipfac = [array('com_tipfac' => "CONTADO"), 
                array('com_tipfac' => "CREDITO")];
//Convertimos el array de array en un formato de json string
echo json_encode($com_tipfac);
?>