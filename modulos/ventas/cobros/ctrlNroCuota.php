<?php
session_start();

//Establecemos el retorno del documento en formato json
header("Content-type: application/json; charset=utf-8");
//Solicitamos la clase de Conexion
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

if ($_POST['case'] == 1) {
    //Consultamos si existe la variable operacion
    if ($_POST['operacion_cab'] == "1") {

        $ven_cod = $_POST['ven_cod'];
        $cobr_cod = $_POST['cobr_cod'];

        /* se consulta cobros detalle para seleccionar el nro de cuota:
        1- se selecciona el nro de cuota si el cod de venta es el mismo que se pasa por parametro 
        2- se selecciona el nro de cuota y se le suma uno si el cod de venta es menor al que se le pasa por parametro
        3- se seleccina 1 si los dos anteriores dan null */
        $sql = "select coalesce (
                    (select distinct cc.cobr_nrocuota from cobros_cab cc 
                        where ven_cod = $ven_cod and cc.cobr_cod = $cobr_cod and cc.cobr_estado = 'ACTIVO'),
                    (select cc.cobr_nrocuota + 1 from cobros_cab cc 
                        where ven_cod = $ven_cod and cc.cobr_cod < $cobr_cod and cc.cobr_estado = 'ACTIVO'
                        order by cc.cobr_cod desc limit 1),
                    1
                ) as cobr_nrocuota;";

        $resultado = pg_query($conexion, $sql);
        $datos = pg_fetch_assoc($resultado);
        echo json_encode($datos);

    }
/*----------------------------------Validacion de monto de cuota abonado------------------------------------------------*/

} else if ($_POST['case'] == "2") {
    
        $cobr_cod = $_POST['cobr_cod'];
        $ven_montocuota = $_POST['ven_montocuota'];
        
        //se selecciona la suma del monto de cobros detalle y el saldo de la cuenta por cobrar
        $sqlMonto = "select 
            case 
                when coalesce(sum(cd.cobrdet_monto),0) = $ven_montocuota then 'S'
                else 'N'
            end pagado_tot
        from cobros_det cd 
        where cd.cobr_cod = $cobr_cod;";
        
        //se convierte en array asociativo lo obtenido
        $respuesta = pg_query($conexion, $sqlMonto);
        $obtenido = pg_fetch_assoc($respuesta);
        //se envia la respuesta
        echo json_encode($obtenido);
}


?>