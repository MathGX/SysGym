<?php

header('Content-type: application/json; charset=utf-8');

/*require_once es como un import, si usamos más de una vez pasa por alto,  
es más recomendable y sí o sí se usa para llamar a otra clase, sin importar  
que las clases estén dentro de la misma carpeta*/

//importala clase conexion.php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$cobr_cod = $_POST['cobr_cod'];

$sql = "select
        fc.forcob_cod,
        fc.forcob_descri
        from forma_cobro fc
        where fc.forcob_estado like 'ACTIVO'
        and (not exists (
                select 1
                from cobros_det cd
                where cd.cobr_cod = coalesce($cobr_cod,0)
                and cd.forcob_cod = 2
        ) or fc.forcob_cod != 2)
        order by fc.forcob_descri;";

//consultamos a la base de datos y guardamos el resultado
$resultado = pg_query($conexion, $sql);
//convertimos el resultado en un array asociativo
$datos = pg_fetch_all($resultado);

//se consulta si el array asociativo está vacío, de ser así se envía un mensaje al front-end
if (empty($datos)) {
        echo json_encode(
                array(
                        "fila" => "No se encuentran formas de cobros registradas",
                        "true" => true
                )
        );
        // si datos no está vacío convertimos el array asociativo en json
} else {
        echo json_encode($datos);
}

?>