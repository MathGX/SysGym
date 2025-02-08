<?php

header('Content-type: application/json; charset=utf-8');

/*require_once es como un import, si usamos más de una vez pasa por alto,  
es más recomendable y sí o sí se usa para llamar a otra clase, sin importar  
que las clases estén dentro de la misma carpeta*/

//importala clase conexion.php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$ven_cod = $_POST['ven_cod'];
$cobr_cod = $_POST['cobr_cod'];
$forcob_descri = $_POST['forcob_descri'];

$consulta = "select 
        cd.forcob_cod, 
        cd.cobr_cod,
        cd.ven_cod 
        from cobros_det cd
        join forma_cobro fc on cd.forcob_cod = fc.forcob_cod
        where cd.ven_cod = $ven_cod and cd.cobr_cod = $cobr_cod;";

//consultamos a la base de datos y guardamos el resultado
$response = pg_query($conexion, $consulta);
//convertimos el resultado en un array asociativo
$result = pg_fetch_all($response);

$form = false;

foreach ($result as $forma) {
        if ($forma['forcob_cod'] == "2") {
                $form = true;
        }
}

if ($form == true) {
        //se realiza la consulta SQL a la base de datos con el filtro
        $sql = "select
        fc.forcob_cod,
        fc.forcob_descri
        from forma_cobro fc
        where fc.forcob_estado like 'ACTIVO' and fc.forcob_descri ilike '%$forcob_descri%' and fc.forcob_cod != 2
        order by fc.forcob_descri;";
} else {
        //se realiza la consulta SQL a la base de datos con el filtro
        $sql = "select
        fc.forcob_cod,
        fc.forcob_descri
        from forma_cobro fc
        where fc.forcob_estado like 'ACTIVO' and fc.forcob_descri ilike '%$forcob_descri%'
        order by fc.forcob_descri;";
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