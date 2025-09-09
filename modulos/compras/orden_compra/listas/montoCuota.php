<?php

header('Content-type: application/json; charset=utf-8');

/*require_once es como un import, si usamos más de una vez pasa por alto,  
es más recomendable y sí o sí se usa para llamar a otra clase, sin importar  
que las clases estén dentro de la misma carpeta*/

//importala clase conexion.php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$presprov_cod = $_POST['presprov_cod'];
$ordcom_cuota = $_POST['ordcom_cuota'];

//se realiza la consulta SQL a la base de datos con el filtro
$sql = "select round(sum(ppd.total)/$ordcom_cuota) ordcom_montocuota from v_presupuesto_prov_det ppd where ppd.presprov_cod = $presprov_cod;";
        
//consultamos a la base de datos y guardamos el resultado
$resultado = pg_query($conexion, $sql);
//convertimos el resultado en un array
$datos = pg_fetch_assoc($resultado);
// si datos no está vacío convertimoas el array asociativo en json y se envia al front
echo json_encode($datos);


?>