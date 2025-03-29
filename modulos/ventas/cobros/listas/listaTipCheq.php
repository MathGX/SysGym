<?php
//Establecemos la salida en formato json
header("Content-type: application/json; charset=utf-8");
//Establecemos el array de array asociativos
$cobrcheq_tipcheq = [array('cobrcheq_tipcheq' => "DIFERIDO"), 
                array('cobrcheq_tipcheq' => "A LA VISTA")];

// Obtener el valor enviado por POST
$tipo_cheque = $_POST['cobrcheq_tipcheq'];

// Inicializar una variable para almacenar las coincidencias
$resultados = [];

// buscar coincidencias
foreach ($cobrcheq_tipcheq as $condicion) {
        if (stripos($condicion['cobrcheq_tipcheq'], $tipo_cheque) !== false) { // Comparar sin distinguir mayúsculas/minúsculas
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