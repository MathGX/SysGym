<?php

header('Content-type: application/json; charset=utf-8');

/*require_once es como un import, si usamos más de una vez pasa por alto,  
es más recomendable y sí o sí se usa para llamar a otra clase, sin importar  
que las clases estén dentro de la misma carpeta*/

//importala clase conexion.php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$pedven_cod = $_POST["pedven_cod"];
$itm_descri = $_POST['itm_descri'];
$dep_cod = $_POST['dep_cod'];
$suc_cod = $_POST['suc_cod'];
$emp_cod = $_POST['emp_cod'];

//se realiza la consulta SQL a la base de datos con el filtro
$sql = "select
        pvd.itm_cod,
        pvd.tipitem_cod,
        i.tipimp_cod,
        i.tipitem_cod,
        i.itm_descri,
        pvd.pedvendet_cantidad as vendet_cantidad,
        um.uni_descri||' ('||um.uni_simbolo||')' as uni_descri,
        i.itm_precio as vendet_precio
from pedido_venta_det pvd
        join stock s on pvd.itm_cod = s.itm_cod and pvd.tipitem_cod = s.tipitem_cod 
                join items i on i.itm_cod = s.itm_cod and i.tipitem_cod = s.tipitem_cod
                        join tipo_item ti on ti.tipitem_cod = i.tipitem_cod
                        join unidad_medida um on um.uni_cod = i.uni_cod 
where pvd.pedven_cod = $pedven_cod 
        and s.dep_cod = $dep_cod 
        and s.suc_cod = $suc_cod 
        and s.emp_cod = $emp_cod 
        and i.itm_descri ilike '%$itm_descri%'
order by i.itm_descri;";
        
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