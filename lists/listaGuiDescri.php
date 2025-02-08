<?php

header('Content-type: application/json; charset=utf-8');

/*require_once es como un import, si usamos más de una vez pasa por alto,  
es más recomendable y sí o sí se usa para llamar a otra clase, sin importar  
que las clases estén dentro de la misma carpeta*/

//iniciamos variables de sesión
session_start();

//importala clase conexion.php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$u = $_SESSION['usuarios'];
$perfil = $u['perf_cod'];
$busquedaMenu = $_POST['busquedaMenu'];

//se realiza la consulta SQL a la base de datos con el filtro
if ($u['perf_descri'] == 'ADMINISTRADOR') {
        $sqlAdmin = "select 
        g.guiDescri,
        g.url
        from v_gui_admin g
        where g.guiDescri != 'NER' and g.guiDescri ilike '%$busquedaMenu%'
        order by g.guiDescri;";
} else {
        $sqlAdmin = "select 
        m.guidescri, 
        m.url 
        from v_gui_mov m 
        where m.perf_cod = $perfil and m.guidescri ilike '%$busquedaMenu%'
        order by m.guidescri;";
}
//consultamos a la base de datos y guardamos el resultado
$respGui = pg_query($conexion, $sqlAdmin);
//convertimos el resultado en un array asociativo
$dateGui = pg_fetch_all($respGui);

//se consulta si el array asociativo está vacío, de ser así se envía un mensaje al front-end
if (empty($dateGui)) {
        echo json_encode(
                array(
                        "fila" => "No se encuentra el dato",
                        "true" => true
                )
        );
        // si datos no está vacío convertimoas el array asociativo en json
} else {
        echo json_encode($dateGui);
}

?>