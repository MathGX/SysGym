<?php
// Establecemos la salida en formato json
header("Content-type: application/json; charset=utf-8");

// Establecemos el array de condiciones de pago
$com_tipfac = [
        array('com_tipfac' => "CONTADO"), 
        array('com_tipfac' => "CREDITO")
];

// Obtener el valor enviado por POST
$condi_pago = $_POST['com_tipfac'];

// Inicializar una variable para almacenar las coincidencias
$resultados = [];

// buscar coincidencias
foreach ($com_tipfac as $condicion) {
        if (stripos($condicion['com_tipfac'], $condi_pago) !== false) { // Comparar sin distinguir mayúsculas/minúsculas
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
