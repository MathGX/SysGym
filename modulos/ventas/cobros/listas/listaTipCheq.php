<?php
//Establecemos la salida en formato json
header("Content-type: application/json; charset=utf-8");
//Establecemos el array de array asociativos
$cobrcheq_tipcheq = [array('cobrcheq_tipcheq' => "DIFERIDO"), 
                array('cobrcheq_tipcheq' => "A LA VISTA")];
//Convertimos el array de array en un formato de json string
echo json_encode($cobrcheq_tipcheq);
?>