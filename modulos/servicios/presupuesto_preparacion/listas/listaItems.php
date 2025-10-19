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
$sql = "select distinct
    cc.itm_cod,
    cc.tipitem_cod,
    i.tipimp_cod,
    i.itm_descri,
    i.itm_precio as prprdet_precio
from cup_serv_cab cc
	join items i on i.itm_cod = cc.itm_cod 
    join tipo_item ti on ti.tipitem_cod = i.tipitem_cod
    join cup_serv_det cd on cd.cup_cod = cc.cup_cod 
where i.itm_descri ilike '%%' 
    and i.itm_estado ilike 'ACTIVO'
    and cd.cupdet_hora_ini between '$ins_hora_ini' and ('$ins_hora_fin'::time - '01:00:00'::time)
    and cd.cupdet_cantidad > 0
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