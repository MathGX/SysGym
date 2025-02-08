<?php

header('Content-type: application/json; charset=utf-8');

/*require_once es como un import, si usamos más de una vez pasa por alto,  
es más recomendable y sí o sí se usa para llamar a otra clase, sin importar  
que las clases estén dentro de la misma carpeta*/

//importala clase conexion.php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$usu_cod1=$_POST['usu_cod1'];

//se realiza la consulta SQL a la base de datos con el filtro
$sql = "select 
        ac.apcier_cod,
        ac.caj_cod,
        c.caj_descri,
        u.usu_login as usu_login1,
        ac.apcier_fechahora_aper,
        ac.apcier_estado 
        from apertura_cierre ac 
        join caja c on c.caj_cod = ac.caj_cod 
        join usuarios u on u.usu_cod = ac.usu_cod 
        where ac.usu_cod = $usu_cod1 and ac.apcier_estado ilike 'ABIERTA';";

//consultamos a la base de datos y guardamos el resultado
$resultado = pg_query($conexion, $sql);
//convertimos el resultado en un array asociativo
$datos = pg_fetch_all($resultado);
//se consulta si el array asociativo está vacío, de ser así se envía un mensaje al front-end
if (empty($datos)) {
        echo json_encode(
                array(
                        "fila" => "No existe caja abierta por este usuario",
                        "true" => true
                )
        );
        // si datos no está vacío convertimoas el array asociativo en json
} else {
        echo json_encode($datos);
}

?>