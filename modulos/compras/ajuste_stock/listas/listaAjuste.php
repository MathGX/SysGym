<?php
//Establecemos la salida en formato json
header("Content-type: application/json; charset=utf-8");
//Establecemos el array de array asociativos
$ajus_tipoajuste = [array('ajus_tipoajuste' => "POSITIVO"), 
                        array('ajus_tipoajuste' => "NEGATIVO")];

// Obtener el valor enviado por POST
$input_data = $_POST['ajus_tipoajuste'];

// Inicializar una variable para almacenar las coincidencias
$resultados = [];

// buscar coincidencias
foreach ($ajus_tipoajuste as $condicion) {
        if (stripos($condicion['ajus_tipoajuste'], $input_data) !== false) { // Comparar sin distinguir mayúsculas/minúsculas
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