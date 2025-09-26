<?php
// Establecemos la salida en formato json
header("Content-type: application/json; charset=utf-8");

// Establecemos el array de condiciones de pago
$informe = [
        ['informe' => "PRESUPUESTOS DEL PROVEEDOR"],
        ['informe' => "LIBRO DE COMPRAS"],
        ['informe' => "CUENTAS A PAGAR"]
];

// Obtener el valor enviado por POST
$reporte = $_POST['informe'];

// Inicializar una variable para almacenar las coincidencias
$resultados = [];

// buscar coincidencias
foreach ($informe as $condicion) {
        if (stripos($condicion['informe'], $reporte) !== false) { // Comparar sin distinguir mayúsculas/minúsculas
                $resultados[] = $condicion; // Agregar coincidencias al array de resultados
        }
}

//se consulta si el array asociativo está vacío, de ser así se envía un mensaje al front-end
if (empty($resultados)) {
        echo json_encode(
                array(
                        "fila" => "No se encuentra el reporte",
                        "true" => true
                )
        );
        // si datos no está vacío convertimoas el array asociativo en json
} else {
        echo json_encode($resultados);
}
?>
