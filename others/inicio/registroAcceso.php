<?php

header('Content-type: application/json; charset=utf-8');

/*require_once es como un import, si usamos más de una vez pasa por alto,  
es más recomendable y sí o sí se usa para llamar a otra clase, sin importar  
que las clases estén dentro de la misma carpeta*/

//importar la clase conexion.php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

$objConexion = new Conexion();
$conexion = $objConexion->getConexion();


// Obtener los valores de la solicitud AJAX
$acc_usu = $_POST['acc_usu'];
$acc_obs = $_POST['acc_obs'];

// Generar la consulta SQL para insertar los datos en la tabla 'acceso'
$sql = "INSERT INTO acceso (acc_cod, acc_usu, acc_fecha, acc_hora, acc_obs) 
        VALUES ((SELECT COALESCE(MAX(acc_cod), 0) + 1 FROM acceso), '$acc_usu', current_date, current_time, '$acc_obs')";

// Ejecutar la consulta
$resultado = pg_query($conexion, $sql);

// Comprobar si la consulta se ejecutó correctamente
if ($resultado) {
    echo json_encode(array("status" => "success", "message" => "Data inserted successfully"));
} else {
    echo json_encode(array("status" => "error", "message" => "Error inserting data"));
}

// Cerrar la conexión a al base de datos
pg_close($conexion);




?>