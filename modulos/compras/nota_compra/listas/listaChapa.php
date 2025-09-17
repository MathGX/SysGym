<?php

header('Content-type: application/json; charset=utf-8');

/*require_once es como un import, si usamos más de una vez pasa por alto,  
es más recomendable y sí o sí se usa para llamar a otra clase, sin importar  
que las clases estén dentro de la misma carpeta*/

//importala clase conexion.php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$chapve_chapa = $_POST['chapve_chapa'];

//se realiza la consulta SQL a la base de datos con el filtro
$sql = "select
                cv.chapve_cod,
                cv.chapve_chapa,
                cv.modve_cod,
                mv.modve_descri,
                mv.marcve_cod,
                mv2.marcve_descri,
                'VEHICULO '||cv.chapve_chapa||' - '||mv2.marcve_descri||' MODELO '||mv.modve_descri vehiculo
        from chapa_vehiculo cv
                join modelo_vehiculo mv on mv.modve_cod = cv.modve_cod
                        join marca_vehiculo mv2 on mv2.marcve_cod = mv.marcve_cod
        where cv.chapve_chapa ilike '%$chapve_chapa%'
                and cv.chapve_estado = 'ACTIVO'
        order by cv.chapve_chapa;";
        
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