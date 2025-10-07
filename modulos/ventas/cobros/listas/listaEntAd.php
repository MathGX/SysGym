<?php

header('Content-type: application/json; charset=utf-8');

/*require_once es como un import, si usamos más de una vez pasa por alto,  
es más recomendable y sí o sí se usa para llamar a otra clase, sin importar  
que las clases estén dentro de la misma carpeta*/

//importala clase conexion.php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$ent_razonsocial = $_POST["ent_razonsocial_tarj"];

//se realiza la consulta SQL a la base de datos con el filtro
$sql = "select 
        ea.entahd_cod,
        ea.ent_cod as ent_cod_tarj,
        ee.ent_razonsocial as ent_razonsocial_tarj,
        ea.martarj_cod,
        mt.martarj_descri
        from entidad_adherida ea 
        join entidad_emisora ee on ee.ent_cod = ea.ent_cod 
        join marca_tarjeta mt on mt.martarj_cod = ea.martarj_cod 
        where ea.entahd_estado like 'ACTIVO' and ee.ent_razonsocial||' '||mt.martarj_descri ilike '%$ent_razonsocial%'
        order by ee.ent_razonsocial;";
        
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