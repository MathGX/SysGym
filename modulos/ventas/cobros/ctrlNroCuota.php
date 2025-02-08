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
    if ($_POST['operacion_det'] == "1") {

        $ven_cod = $_POST['ven_cod'];
        $cobr_cod = $_POST['cobr_cod'];

        /* se consulta cobros detalle para seleccionar el nro de cuota:
        1- se selecciona el nro de cuota si el cod de venta es el mismo que se pasa por parametro 
        2- se selecciona el nro de cuota y se le suma uno si el cod de venta es menor al que se le pasa por parametro
        3- se seleccina 1 si los dos anteriores dan null */
        $sql = "select coalesce (
                (select distinct cd.cobrdet_nrocuota from cobros_det cd 
                    join cobros_cab cc on cc.cobr_cod = cd.cobr_cod 	
                    where ven_cod = $ven_cod and cd.cobr_cod = $cobr_cod and cc.cobr_estado = 'ACTIVO'),
                (select cd.cobrdet_nrocuota + 1 from cobros_det cd
                    join cobros_cab cc on cc.cobr_cod = cd.cobr_cod
                    where ven_cod = $ven_cod and cd.cobr_cod < $cobr_cod and cc.cobr_estado = 'ACTIVO'
                    order by cd.cobr_cod desc limit 1),
                1
                ) as cobrdet_nrocuota;";

        $resultado = pg_query($conexion, $sql);
        $datos = pg_fetch_assoc($resultado);
        echo json_encode($datos);

    }
} else if ($_POST['case'] == "2") {

/*----------------------------------Validacion de monto de cuota------------------------------------------------*/

    if ($_POST['operacion_det'] == 1) {
    
        $ven_cod = $_POST['ven_cod'];
        $cobr_cod = $_POST['cobr_cod'];
        $ven_montocuota = $_POST['ven_montocuota'];
        $cuencob_montotal = $_POST['cuencob_montotal'];
        $montos = ((float)$_POST['cobrcheq_monto'] + (float)$_POST['cobrtarj_monto'] + (float)$_POST['cobrdet_monto']);
        
        //se selecciona la suma de la columna cobrdet_monto de cobros detalle
        $sqlMonto = "select sum(cd.cobrdet_monto) as cobrdet_monto from cobros_det cd 
        where cd.ven_cod = $ven_cod and cd.cobr_cod = $cobr_cod;";
        
        //se convierte en array asociativo lo obtenido
        $respuesta = pg_query($conexion, $sqlMonto);
        $obtenido = pg_fetch_assoc($respuesta);
    
        //a la suma de de la columna cobrdet_monto se le suma el monto según la forma de cobro
        $montoTotal = ((float)$obtenido['cobrdet_monto'] + $montos);
        
        //se selecciona el saldo de la cuenta por cobrar
        $sqlSaldo = "select cuencob_saldo from cuentas_cobrar 
        where ven_cod = $ven_cod";
        
        //se convierte en array asociativo lo obtenido
        $restante = pg_query($conexion, $sqlSaldo);
        $saldo = pg_fetch_assoc($restante);

        /*si la sumatoria de los montos de cobro detalle son mayores a lo que tienen que ser
        da un mensaje de error, si no, da un mensaje correcto*/
        if ($montoTotal > $ven_montocuota) {
            $response = array(
                "mensaje" => "EL MONTO EXCEDE EL VALOR DE LA CUOTA",
                "tipo" => "error"
            );
        }else if ($montos > $saldo['cuencob_saldo']) {
            $response = array(
                "mensaje" => "EL MONTO EXCEDE EL VALOR DEL SALDO",
                "tipo" => "error"
            );
        } else {
            $response = array(
                "mensaje" => "EL MONTO NO EXCEDE EL VALOR DE LA CUOTA",
                "tipo" => "success"
            );

            //se selecciona el total de la deuda ya cobrado
            $sqlTotal = "select sum(cd.cobrdet_monto) as cobrdet_monto from cobros_det cd 
            where cd.ven_cod = $ven_cod;";
            
            //se convierte en array asociativo lo obtenido
            $result = pg_query($conexion, $sqlTotal);
            $total = pg_fetch_assoc($result);

            //se 
            $totalAbonado = (float)$total['cobrdet_monto'] + $montos;

            //la sumatoria de los montos de cobro detalle son iguales al total de la deuda, se actualizan estados
            if ($totalAbonado == $cuencob_montotal) {
                $sqlEstado = "update cuentas_cobrar 
                set cuencob_estado = 'CANCELADO'
                where ven_cod = $ven_cod;
                update ventas_cab 
                set ven_estado = 'CANCELADO'
                where ven_cod = $ven_cod;";

                pg_query($conexion, $sqlEstado);
            }
        }
        
        echo json_encode($response);

    }
}


?>