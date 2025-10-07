<?php

header('Content-type: application/json; charset=utf-8');

/*require_once es como un import, si usamos más de una vez pasa por alto,  
es más recomendable y sí o sí se usa para llamar a otra clase, sin importar  
que las clases estén dentro de la misma carpeta*/

//importala clase conexion.php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$per_nrodoc = $_POST['per_nrodoc'];

//se realiza la consulta SQL a la base de datos con el filtro
$sql = "select 
        p.per_nrodoc,
        cc.cuencob_monto,
        cc.cuencob_saldo,
        cc.cuencob_cuotas,
        cc.ven_cod,
        vc.ven_nrofac,
        p.per_nombres||' '||p.per_apellidos as cliente,
        vc.ven_montocuota,
        vc.ven_intefecha,
        vc.ven_montocuota-coalesce(sum(cd.cobrdet_monto),0) pendiente
from cuentas_cobrar cc 
        join ventas_cab vc on vc.ven_cod = cc.ven_cod
        join sucursales s on s.suc_cod = vc.suc_cod and s.emp_cod = vc.emp_cod
                join empresa e on e.emp_cod = s.emp_cod
        join clientes c on c.cli_cod = vc.cli_cod
                join personas p on p.per_cod = c.per_cod
        left join cobros_cab cc2 on cc2.ven_cod = cc.ven_cod 
                left join cobros_det cd on cd.cobr_cod = cc2.cobr_cod
where cc.cuencob_estado = 'ACTIVO' and p.per_nrodoc ilike '%$per_nrodoc%'
group by 1,2,3,4,5,6,7,8,9
order by cc.ven_cod;";
        
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