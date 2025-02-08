<?php
//Establecemos la salida en formato json
header("Content-type: application/json; charset=utf-8");
//Establecemos el array de array asociativos

$tipcomp_cod = $_POST['tipcomp_cod'];
$notacom_concepto = [];

if ($tipcomp_cod == "1") {
    $notacom_concepto = [array('notacom_concepto' => "DEVOLUCION"), 
                    array('notacom_concepto' => "DESCUENTO")];
} else if ($tipcomp_cod == "2") {
    $notacom_concepto = [array('notacom_concepto' => "ITEM EXTRA")];
} else if ($tipcomp_cod == "3") {
    $notacom_concepto = [array('notacom_concepto' => "ENVIO")];
}
//Convertimos el array de array en un formato de json string
echo json_encode($notacom_concepto);
?>