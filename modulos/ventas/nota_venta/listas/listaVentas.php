<?php

session_start();
$sesion = $_SESSION['usuarios'];

header('Content-type: application/json; charset=utf-8');

/*require_once es como un import, si usamos más de una vez pasa por alto,  
es más recomendable y sí o sí se usa para llamar a otra clase, sin importar  
que las clases estén dentro de la misma carpeta*/

//importala clase conexion.php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

include "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/extension/importPHP.php"; 

$per_nrodoc = $_POST['per_nrodoc'];
$validacion = obtenerConfig($sesion['suc_cod'], $sesion['emp_cod'], 1);

$sql = "select 
        p.per_nrodoc,
        cc.ven_cod,
        current_date - vc.ven_fecha as dias_emision,
        vc.ven_tipfac,
        vc.ven_montocuota,
        vc.ven_nrofac,
        vc.cli_cod,
        p.per_nombres||' '||p.per_apellidos as cliente
from cuentas_cobrar cc 
join ventas_cab vc on vc.ven_cod = cc.ven_cod
join sucursales s on s.suc_cod = vc.suc_cod and s.emp_cod = vc.emp_cod
        join empresa e on e.emp_cod = s.emp_cod
join clientes c on c.cli_cod = vc.cli_cod
        join personas p on p.per_cod = c.per_cod
where cc.cuencob_estado <> 'ANULADO' and p.per_nrodoc ilike '%$per_nrodoc%' and ((current_date - vc.ven_fecha) <= $validacion)
order by cc.ven_cod;";
//
//consultamos a la base de datos y guardamos el resultado
$resultado = pg_query($conexion, $sql);
//convertimos el resultado en un array asociativo
$datos = pg_fetch_all($resultado);
//se consulta si el array asociativo está vacío, de ser así se envía un mensaje al front-end
if (empty($datos)) {
        echo json_encode(
                array(
                        "fila" => "No se encuentra el dato o tiempo de intercambio y/o devolucion vencido",
                        "true" => true
                )
        );
        // si datos no está vacío convertimoas el array asociativo en json
} else {
        echo json_encode($datos);
}


?>