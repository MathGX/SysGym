<?php

header('Content-type: application/json; charset=utf-8');

/*require_once es como un import, si usamos más de una vez pasa por alto,  
es más recomendable y sí o sí se usa para llamar a otra clase, sin importar  
que las clases estén dentro de la misma carpeta*/

//importala clase conexion.php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$pro_razonsocial = $_POST['pro_razonsocial'];

//se realiza la consulta SQL a la base de datos con el filtro
$sql = "select 
                p.pro_cod,
                p.tiprov_cod,
                p.pro_razonsocial||' - RUC: '||p.pro_ruc as pro_razonsocial ,
                p.pro_email solpre_email
        from proveedor p
                join tipo_proveedor tp on tp.tiprov_cod = p.tiprov_cod 
        where p.pro_estado = 'ACTIVO' 
                and (p.pro_razonsocial ilike '%$pro_razonsocial%' 
                        or p.pro_ruc ilike '%$pro_razonsocial%' 
                        or p.pro_razonsocial||' - RUC: '||p.pro_ruc ilike '%$pro_razonsocial%')
        order by p.pro_razonsocial ;";
        
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