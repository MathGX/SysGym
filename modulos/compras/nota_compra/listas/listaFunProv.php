<?php

header('Content-type: application/json; charset=utf-8');

/*require_once es como un import, si usamos más de una vez pasa por alto,  
es más recomendable y sí o sí se usa para llamar a otra clase, sin importar  
que las clases estén dentro de la misma carpeta*/

//importala clase conexion.php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$pro_cod = $_POST['pro_cod'];
$funprov_nro_doc = $_POST['funprov_nro_doc'];

//se realiza la consulta SQL a la base de datos con el filtro
$sql = "select
                fp.funprov_cod,
                fp.funprov_nombres,
                fp.funprov_apellidos,
                fp.funprov_nombres||' '||fp.funprov_apellidos||' - '||
                case 
                        when length(fp.funprov_nro_doc) > 0 then fp.funprov_nro_doc
                        else 'SIN CI REGISTRADA' 
                end funcionario,
                fp.funprov_nro_doc
        from funcionario_proveedor fp
        where fp.pro_cod = $pro_cod
                and (fp.funprov_nombres ilike '%$funprov_nro_doc%' 
                        or fp.funprov_apellidos ilike '%$funprov_nro_doc%' 
                        or fp.funprov_nro_doc ilike '%$funprov_nro_doc%')
                and fp.funprov_estado = 'ACTIVO'
        order by fp.funprov_nombres;";
        
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