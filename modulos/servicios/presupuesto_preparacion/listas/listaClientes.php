<?php

header('Content-type: application/json; charset=utf-8');

/*require_once es como un import, si usamos más de una vez pasa por alto,  
es más recomendable y sí o sí se usa para llamar a otra clase, sin importar  
que las clases estén dentro de la misma carpeta*/

//importala clase conexion.php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$cliente = $_POST['cliente'];

//se realiza la consulta SQL a la base de datos con el filtro
$sql = "select 
ic.ins_cod,
ic.cli_cod,
p.per_nrodoc,
p.per_email,
p.per_nombres||' '||p.per_apellidos AS cliente
from inscripciones_cab ic 
	join clientes c on ic.cli_cod = c.cli_cod 
		join personas p on p.per_cod = c.per_cod
where (p.per_nrodoc ilike '%$cliente%' or p.per_nombres||' '||p.per_apellidos ilike '%$cliente%')
        and ic.ins_estado = 'ACTIVO'
order by p.per_nombres;";

//consultamos a la base de datos y guardamos el resultado
$resultado = pg_query($conexion, $sql);
//convertimos el resultado en un array asociativo
$datos = pg_fetch_all($resultado);
//se consulta si el array asociativo está vacío, de ser así se envía un mensaje al front-end
if (empty($datos)) {
        echo json_encode(
                array(
                        "fila" => "No se encuentra el dato",
                        "true" => true
                )
        );
        // si datos no está vacío convertimoas el array asociativo en json
} else {
        echo json_encode($datos);
}

?>