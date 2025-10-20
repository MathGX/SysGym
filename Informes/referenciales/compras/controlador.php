<?php
//Establecemos la salida en formato json
header("Content-type: application/json; charset=utf-8");
//Establecemos el array de array asociativos
$tabla = [
                ['tabla' => "SUCURSALES"],
                ['tabla' => "ITEMS"],
                ['tabla' => "PROVEEDORES"],
                ['tabla' => "CIUDADES"],
                ['tabla' => "DEPOSITOS"],
                ['tabla' => "TIPOS DE IMPUESTO"],
                ['tabla' => "TIPOS DE ITEM"],
                ['tabla' => "TIPOS DE PROVEEDOR"],
        ];
        
// Obtener el valor enviado por POST
$entidad = $_POST['tabla'];

// Inicializar una variable para almacenar las coincidencias
$resultados = [];

// buscar coincidencias
foreach ($tabla as $referenical) {
        if (stripos($referenical['tabla'], $entidad) !== false) { // Comparar sin distinguir mayúsculas/minúsculas
                $resultados[] = $referenical; // Agregar coincidencias al array de resultados
        }
}

//se consulta si el array asociativo está vacío, de ser así se envía un mensaje al front-end
if (empty($resultados)) {
        echo json_encode(
                array(
                        "fila" => "No se encuentra la referencial",
                        "true" => true
                )
        );
        // si datos no está vacío convertimoas el array asociativo en json
} else {
        echo json_encode($resultados);
}
?>