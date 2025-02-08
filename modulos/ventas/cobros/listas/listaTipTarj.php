<?php
//Establecemos la salida en formato json
header("Content-type: application/json; charset=utf-8");
//Establecemos el array de array asociativos
$cobrtarj_tiptarj = [array('cobrtarj_tiptarj' => "CREDITO"), 
                array('cobrtarj_tiptarj' => "DEBITO")];
//Convertimos el array de array en un formato de json string
echo json_encode($cobrtarj_tiptarj);
?>