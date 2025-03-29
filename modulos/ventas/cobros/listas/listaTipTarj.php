<?php
//Establecemos la salida en formato json
header("Content-type: application/json; charset=utf-8");
//Establecemos el array de array asociativos
$cobrtarj_tiptarj = [array('cobrtarj_tiptarj' => "CREDITO"), 
                array('cobrtarj_tiptarj' => "DEBITO")];
                
// Obtener el valor enviado por POST
$tipo_tarjeta = $_POST['cobrtarj_tiptarj'];

// Inicializar una variable para almacenar las coincidencias
$resultados = [];

// buscar coincidencias
foreach ($cobrtarj_tiptarj as $condicion) {
        if (stripos($condicion['cobrtarj_tiptarj'], $tipo_tarjeta) !== false) { // Comparar sin distinguir mayúsculas/minúsculas
                $resultados[] = $condicion; // Agregar coincidencias al array de resultados
        }
}

//se consulta si el array asociativo está vacío, de ser así se envía un mensaje al front-end
if (empty($resultados)) {
        echo json_encode(
                array(
                        "fila" => "No se encuentra el dato",
                        "true" => true
                )
        );
        // si datos no está vacío convertimoas el array asociativo en json
} else {
        echo json_encode($resultados);
}
?>