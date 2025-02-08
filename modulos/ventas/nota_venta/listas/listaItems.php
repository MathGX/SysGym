<?php

header('Content-type: application/json; charset=utf-8');

/*require_once es como un import, si usamos más de una vez pasa por alto,  
es más recomendable y sí o sí se usa para llamar a otra clase, sin importar  
que las clases estén dentro de la misma carpeta*/

//importala clase conexion.php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$item = $_POST['itm_descri'];
$tipcomp_cod = $_POST['tipcomp_cod'];
$ven_cod = $_POST['ven_cod'];

//se realiza la consulta SQL a la base de datos con el filtro
if ($tipcomp_cod == "1" || $tipcomp_cod == "3") {
        $sql ="select 
        i.*,
        ti.tipitem_descri,
        vd.vendet_cantidad as notvendet_cantidad,
        i.itm_precio as notvendet_precio
        from items i 
        join tipo_item ti on ti.tipitem_cod = i.tipitem_cod
        join stock s on s.itm_cod = i.itm_cod and s.tipitem_cod = i.tipitem_cod 
        	join ventas_det vd on vd.itm_cod = s.itm_cod and vd.tipitem_cod = s.tipitem_cod
        		join ventas_cab vc on vc.ven_cod = vd.ven_cod 
        where itm_descri ilike '%$item%' and vc.ven_cod = $ven_cod and vc.ven_estado <> '%ANULADO%'
        order by i.itm_descri;";
} else if ($tipcomp_cod == "2") {
        $sql = "select 
        i.*,
        ti.tipitem_descri,
        i.itm_precio as notvendet_precio
        from items i 
        join tipo_item ti on ti.tipitem_cod = i.tipitem_cod
        where itm_descri ilike '%$item%'
        order by i.itm_descri;";
}


        
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