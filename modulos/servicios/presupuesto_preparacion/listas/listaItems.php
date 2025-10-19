<?php

header('Content-type: application/json; charset=utf-8');

/*require_once es como un import, si usamos más de una vez pasa por alto,  
es más recomendable y sí o sí se usa para llamar a otra clase, sin importar  
que las clases estén dentro de la misma carpeta*/

//importala clase conexion.php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$itm_descri = $_POST['itm_descri'];
$ins_hora_ini = $_POST['ins_hora_ini'];
$ins_hora_fin = $_POST['ins_hora_fin'];

//se realiza la consulta SQL a la base de datos con el filtro
$sql = "select * from obtener_servicios('$itm_descri', '$ins_hora_ini', '$ins_hora_fin');";
        
//consultamos a la base de datos y guardamos el resultado
$resultado = pg_query($conexion, $sql);
//convertimos el resultado en un array asociativo
$datos = pg_fetch_all($resultado);
//se consulta si el array asociativo está vacío, de ser así se envía un mensaje al front-end
if (empty($datos)) {
        echo json_encode(
                array(
                        "fila" => "Sin disponibilidad del servicio entre las $ins_hora_ini y $ins_hora_fin",
                        "true" => true
                )
        );
// si datos no está vacío convertimoas el array asociativo en json
} else {
        echo json_encode($datos);
}


?>