<?php

header('Content-type: application/json; charset=utf-8');

/*require_once es como un import, si usamos más de una vez pasa por alto,  
es más recomendable y sí o sí se usa para llamar a otra clase, sin importar  
que las clases estén dentro de la misma carpeta*/

//importala clase conexion.php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$prpr_cod = $_POST["prpr_cod"];
$itm_descri = pg_escape_string($conexion,$_POST['itm_descri']);
$suc_cod = $_POST['suc_cod'];
$emp_cod = $_POST['emp_cod'];

//se realiza la consulta SQL a la base de datos con el filtro
$sql = "select
        s.itm_cod,
        s.tipitem_cod,
        s.dep_cod,
        ppd.prprdet_cantidad  as vendet_cantidad,
        i.itm_descri,
        i.tipimp_cod,
        i.tipitem_cod,
        ppd.prprdet_precio as vendet_precio
from stock s
        join presupuesto_prep_det ppd on ppd.itm_cod = s.itm_cod and ppd.tipitem_cod = s.tipitem_cod 
                join items i on i.itm_cod = s.itm_cod and i.tipitem_cod = s.tipitem_cod
                        join tipo_item ti on ti.tipitem_cod = i.tipitem_cod
        where ppd.prpr_cod = $prpr_cod
                and s.suc_cod = $suc_cod
                and s.emp_cod = $emp_cod
                and i.itm_descri ilike '%$itm_descri%'
                and s.dep_cod = (select max(dep_cod) from stock where suc_cod = $suc_cod)
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