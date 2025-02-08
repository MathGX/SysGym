<?php
//Establecemos la salida en formato json
header("Content-type: application/json; charset=utf-8");
//Establecemos el array de array asociativos

$tipcomp_cod = $_POST['tipcomp_cod'];
$notven_concepto = [];

if ($tipcomp_cod == "1") {
    $notven_concepto = [array('notven_concepto' => "DEVOLUCION"), 
                    array('notven_concepto' => "INTERCAMBIO")];
} else if ($tipcomp_cod == "2") {
    $notven_concepto = [array('notven_concepto' => "COSTO EXTRA")];
} else if ($tipcomp_cod == "3") {
    $notven_concepto = [array('notven_concepto' => "ENVIO")];
}
//Convertimos el array de array en un formato de json string
echo json_encode($notven_concepto);
?>