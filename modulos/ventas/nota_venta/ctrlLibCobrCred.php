<?php
//Establecemos el retorno del documento en formato json
header("Content-type: application/json; charset=utf-8");
//Solicitamos la clase de Conexion
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$ven_cod = $_POST["ven_cod"];
$ven_tipfac = $_POST['ven_tipfac'];
$tipcomp_cod = $_POST['tipcomp_cod'];

$imp = ['vacio',
'libven_excenta',
'libven_iva5',
'libven_iva10'];

if (empty($_POST['operacion_cab'])) {

    $ven_montocuota = $_POST["ven_montocuota"];
    $tipimp_cod = $_POST["tipimp_cod"];
    $notvendet_precio = $_POST["notvendet_precio"];
    $notvendet_cantidad = $_POST["notvendet_cantidad"];
    $notven_concepto = $_POST["notven_concepto"];   
    $itm_cod = $_POST["itm_cod"];

    if ($_POST['tipitem_cod'] == "1") {
        $total = (float)$notvendet_precio;
    } else {
        $total = (float)$notvendet_precio * (float)$notvendet_cantidad;
    }

    //Consultamos si existe la variable operacion
    if ($tipcomp_cod == "1") {
            
        //Si es una venta al contado
            if ($ven_tipfac  == "CONTADO") {

                //se selecciona la diferencia entre el total y saldo de la cuenta correspondiente a la venta
                $sqlDif = "select (cc.cuencob_montotal - cc.cuencob_saldo) as diferencia from cuentas_cobrar cc 
                join ventas_cab vc on vc.ven_cod = cc.ven_cod 
                where cc.ven_cod = $ven_cod";

                //se convierte a array para utilizar la diferencia obtenida
                $dif = pg_query($conexion, $sqlDif);
                $diferencia = pg_fetch_assoc ($dif);

                //se selecciona un registro de cobro detalle que tenga el mismo codigo venta que estamos trabajando
                $sqlCobrEx = "select cobrdet_cod from cobros_det 
                where ven_cod = $ven_cod
                limit 1;";

                //se convierte a array para utilizar lo obtenido
                $cobroExis = pg_query($conexion,$sqlCobrEx);
                $existeCobr = pg_fetch_assoc($cobroExis);
                
                /*si la diferencia entre el total y saldo de la cuenta es 0 y no existe
                un registro de cobro detalle que tenga la misma venta*/
                if (($diferencia['diferencia'] == "0") && (empty($existeCobr['cobrdet_cod']))) {

                    //segun la operacion se suman o se restan los montos en cuentas a cobrar, libro ventas y ventas
                    if ($_POST['operacion_det'] == "1") {

                        $sqlCuentas = "update cuentas_cobrar
                        set cuencob_montotal = cuencob_montotal - $total, 
                        cuencob_saldo = cuencob_saldo - $total,
                        cuencob_estado = 'ACTIVO'
                        where ven_cod = $ven_cod;
                        update ventas_cab
                        set ven_montocuota = ven_montocuota - $total,
                        ven_estado = 'ACTIVO'
                        where ven_cod = $ven_cod;
                        update ventas_det 
                        set vendet_cantidad = vendet_cantidad - $notvendet_cantidad
                        where ven_cod = $ven_cod and itm_cod = $itm_cod;
                        update libro_ventas
                        set $imp[$tipimp_cod] = $imp[$tipimp_cod] - $total,
                        libven_estado = 'ACTIVO'
                        where ven_cod = $ven_cod;";
                        
                    } else if ($_POST['operacion_det'] == "2") {

                        $sqlCuentas = "update cuentas_cobrar
                        set cuencob_montotal = cuencob_montotal + $total, 
                        cuencob_saldo = cuencob_saldo + $total,
                        cuencob_estado = 'ACTIVO'
                        where ven_cod = $ven_cod;
                        update ventas_cab
                        set ven_montocuota = ven_montocuota + $total,
                        ven_estado = 'ACTIVO'
                        where ven_cod = $ven_cod;
                        update ventas_det 
                        set vendet_cantidad = vendet_cantidad + $notvendet_cantidad
                        where ven_cod = $ven_cod and itm_cod = $itm_cod;
                        update libro_ventas
                        set $imp[$tipimp_cod] = $imp[$tipimp_cod] + $total,
                        libven_estado = 'ACTIVO'
                        where ven_cod = $ven_cod;";
                    }

                    //se envían los datos a la base de datos y se arroja un mensaje
                    pg_query($conexion, $sqlCuentas);
                    $response1 = array(
                        "mensaje" => "actualizado cuentas, libro y ventas",
                        "tipo" => "success"
                    );
                
                    echo json_encode($response1);

                /*si existe diferencia entre el total y saldo de la cuenta y un registro de cobro detalle que tenga la misma venta*/
                } else {

                    //segun la operacion se suman o se restan los montos en cuentas a cobrar, libro ventas y ventas y se actualian estados
                    if ($_POST['operacion_det'] == "1") {

                        $sqlCuentas = "update cuentas_cobrar
                        set cuencob_montotal = cuencob_montotal - $total,
                        cuencob_estado = 'CANCELADO'
                        where ven_cod = $ven_cod;
                        update ventas_cab
                        set ven_montocuota = ven_montocuota - $total,
                        ven_estado = 'CANCELADO'
                        where ven_cod = $ven_cod;
                        update ventas_det 
                        set vendet_cantidad = vendet_cantidad - $notvendet_cantidad
                        where ven_cod = $ven_cod and itm_cod = $itm_cod;
                        update libro_ventas
                        set $imp[$tipimp_cod] = $imp[$tipimp_cod] - $total,
                        libven_estado = 'ACTIVO'
                        where ven_cod = $ven_cod;
                        update cobros_cab
                        set cobr_estado = 'ACTIVO'
                        where cobr_cod in (select distinct cobr_cod from cobros_det
                            where ven_cod = $ven_cod);";

                    } else if ($_POST['operacion_det'] == "2") {

                        $sqlCuentas = "update cuentas_cobrar
                        set cuencob_montotal = cuencob_montotal + $total,
                        cuencob_estado = 'CANCELADO'
                        where ven_cod = $ven_cod;
                        update ventas_cab
                        set ven_montocuota = ven_montocuota + $total,
                        ven_estado = 'CANCELADO'
                        where ven_cod = $ven_cod;
                        update ventas_det 
                        set vendet_cantidad = vendet_cantidad + $notvendet_cantidad
                        where ven_cod = $ven_cod and itm_cod = $itm_cod;
                        update libro_ventas
                        set $imp[$tipimp_cod] = $imp[$tipimp_cod] + $total,
                        libven_estado = 'ACTIVO'
                        where ven_cod = $ven_cod;
                        update cobros_cab
                        set cobr_estado = 'ACTIVO'
                        where cobr_cod in (select distinct cobr_cod from cobros_det
                            where ven_cod = $ven_cod);";
                    }
                    
                    //se envían los datos a la base de datos y se arroja un mensaje
                    pg_query($conexion, $sqlCuentas);
                    $response2 = array(
                        "mensaje" => "actualizado cuentas, libro y ventas",
                        "tipo" => "success"
                    );
                    echo json_encode($response2);

                }

                //se consulta el monto total de la cuenta correspondiente a la venta
                $sqlActEst = "select cuencob_montotal from cuentas_cobrar
                where ven_cod = $ven_cod;";
                
                //se convierte a array para utilizar el monto obtenido
                $totalCuenta = pg_query($conexion, $sqlActEst);
                $cuencob_montotal = pg_fetch_assoc($totalCuenta);
                
                //si el monto llega a ser 0 se anula la venta y lo que conlleva la misma
                if ($cuencob_montotal['cuencob_montotal'] == "0") {
                    $sqlEstado = "update cuentas_cobrar 
                    set cuencob_estado = 'ANULADO'
                    where ven_cod = $ven_cod;
                    update libro_ventas 
                    set libven_estado = 'ANULADO'
                    where ven_cod = $ven_cod;
                    update ventas_cab 
                    set ven_estado = 'ANULADO'
                    where ven_cod = $ven_cod;
                    update cobros_cab
                    set cobr_estado = 'ANULADO'
                    where cobr_cod in (select distinct cobr_cod from cobros_det
                        where ven_cod = $ven_cod);";
                    
                    //se envían los datos a la base de datos y se arroja un mensaje
                    pg_query($conexion, $sqlEstado);
                    $actEstado = array(
                        "mensaje" => "estados actualizados",
                        "tipo" => "success"
                    );
                }
                
            } else if ($ven_tipfac  == "CREDITO") {

                //segun la operacion se suman o se restan los montos en cuentas a cobrar, libro ventas y ventas
                if ($_POST['operacion_det'] == "1") {

                    $sqlCuentas = "update cuentas_cobrar
                    set cuencob_montotal = cuencob_montotal - $total, 
                    cuencob_saldo = cuencob_saldo - $total,
                    cuencob_estado = 'ACTIVO'
                    where ven_cod = $ven_cod;
                    update ventas_cab
                    set ven_estado = 'ACTIVO'
                    where ven_cod = $ven_cod;
                    update ventas_det 
                    set vendet_cantidad = vendet_cantidad - $notvendet_cantidad
                    where ven_cod = $ven_cod and itm_cod = $itm_cod;
                    update libro_ventas
                    set $imp[$tipimp_cod] = $imp[$tipimp_cod] - $total,
                    libven_estado = 'ACTIVO'
                    where ven_cod = $ven_cod;
                    update cobros_cab
                    set cobr_estado = 'ACTIVO'
                    where cobr_cod in (select distinct cobr_cod from cobros_det
                        where ven_cod = $ven_cod);";

                    //se envían los datos a la base de datos y se arroja un mensaje
                    pg_query($conexion, $sqlCuentas);
                    $response3 = array(
                        "mensaje" => "actualizado cuentas, libro y ventas",
                        "tipo" => "success"
                    );
                    echo json_encode($response3);

                    //se selecciona el saldo de la cuenta
                    $sqlSaldo1 = "select cuencob_saldo from cuentas_cobrar
                    where ven_cod = $ven_cod";
                                        
                    //se convierte en array asociativo lo obtenido
                    $saldo1 = pg_query($conexion, $sqlSaldo1);
                    $cuencob_saldo1 = pg_fetch_assoc($saldo1);  
                        
                    //se guarda en una vaiable y se convierte a flotante el saldo obtenido
                    $montoSaldo = (float)$cuencob_saldo1['cuencob_saldo'];
                        
                    //si el saldo es 0 o menor, este se actualiza a 0 y se actualiza el estado de la cuenta y la venta
                    if ($montoSaldo <= 0) {
                        $saldo = "update cuentas_cobrar
                        set cuencob_saldo = cuencob_saldo - $montoSaldo,
                        cuencob_estado = 'CANCELADO'
                        where ven_cod = $ven_cod;
                        update ventas_cab 
                        set ven_estado = 'CANCELADO'
                        where ven_cod = $ven_cod;";

                        //se envían los datos a la base de datos 
                        pg_query($conexion, $saldo);
                    }

                    //se consulta el monto total de la cuenta correspondiente a la venta
                    $sqlActEst = "select cuencob_montotal from cuentas_cobrar
                    where ven_cod = $ven_cod;";

                    //se convierte a array para utilizar el monto obtenido
                    $totalCuenta = pg_query($conexion, $sqlActEst);
                    $cuencob_montotal = pg_fetch_assoc($totalCuenta);

                    //si el monto llega a ser 0 se anula la venta y lo que conlleva la misma
                    if ($cuencob_montotal['cuencob_montotal'] == "0") {
                        $sqlEstado = "update cuentas_cobrar 
                        set cuencob_estado = 'ANULADO'
                        where ven_cod = $ven_cod;
                        update libro_ventas 
                        set libven_estado = 'ANULADO'
                        where ven_cod = $ven_cod;
                        update ventas_cab 
                        set ven_estado = 'ANULADO'
                        where ven_cod = $ven_cod;
                        update cobros_cab
                        set cobr_estado = 'ANULADO'
                        where cobr_cod in (select distinct cobr_cod from cobros_det
                            where ven_cod = $ven_cod);";

                        //se envían los datos a la base de datos
                        pg_query($conexion, $sqlEstado);

                    }

                } else if ($_POST['operacion_det'] == "2") {

                    $sqlCuentas = "update cuentas_cobrar
                    set cuencob_montotal = cuencob_montotal + $total,
                    cuencob_estado = 'ACTIVO'
                    where ven_cod = $ven_cod;
                    update ventas_cab
                    set ven_estado = 'ACTIVO'
                    where ven_cod = $ven_cod;
                    update ventas_det 
                    set vendet_cantidad = vendet_cantidad + $notvendet_cantidad
                    where ven_cod = $ven_cod and itm_cod = $itm_cod;
                    update libro_ventas
                    set $imp[$tipimp_cod] = $imp[$tipimp_cod] + $total,
                    libven_estado = 'ACTIVO'
                    where ven_cod = $ven_cod;";

                    //se envían los datos a la base de datos y se arroja un mensaje
                    pg_query($conexion, $sqlCuentas);
                    $response4 = array(
                        "mensaje" => "actualizado cuentas, libro y ventas",
                        "tipo" => "success"
                    );
                    echo json_encode($response4);

                    //se consulta la suma de los montos cobrados según el código de venta
                    $sqlMontoCobro = "select sum(cobrdet_monto) as cobrdet_monto from cobros_det
                    where ven_cod = $ven_cod;"; 

                    //se convierte a array para utilizar el monto obtenido
                    $montoCobrado = pg_query($conexion, $sqlMontoCobro);
                    $cobrdet_monto = pg_fetch_assoc($montoCobrado);

                    //se consulta el monto total de la cuenta según el código de venta
                    $sqlCuenTot = "select cuencob_montotal from cuentas_cobrar
                    where ven_cod = $ven_cod;"; 

                    //se convierte a array para utilizar el monto obtenido
                    $cuentaTotal = pg_query($conexion, $sqlCuenTot);
                    $montoTotal = pg_fetch_assoc($cuentaTotal);

                    //se calcula la diferencia entre el monto total de la cuenta y el monto cobrado 
                    $diferencia = (float)$montoTotal['cuencob_montotal'] - (float)$cobrdet_monto['cobrdet_monto'];

                    //se actualiza el saldo de la cuenta con la diferencia obtenida
                    $sqlSaldo = "update cuentas_cobrar
                    set cuencob_saldo = $diferencia
                    where ven_cod = $ven_cod;";

                    //se ejecuta la consulta
                    pg_query($conexion, $sqlSaldo);

                    //se selecciona el saldo de la cuenta
                    $sqlSaldo2 = "select cuencob_saldo from cuentas_cobrar
                    where ven_cod = $ven_cod";
                                        
                    //se convierte en array asociativo lo obtenido
                    $saldo2 = pg_query($conexion, $sqlSaldo2);
                    $cuencob_saldo2 = pg_fetch_assoc($saldo2);

                    //si el saldo de la cuenta es 0 o menor, este se actualiza con 0 y se actualiza el estado de la cuenta y de venta a cancelado
                    if ($cuencob_saldo2['cuencob_saldo'] <= 0) {
                        $saldo = "update cuentas_cobrar
                        set cuencob_saldo = 0,
                        cuencob_estado = 'CANCELADO'
                        where ven_cod = $ven_cod;
                        update ventas_cab 
                        set ven_estado = 'CANCELADO'
                        where ven_cod = $ven_cod;";

                        //se ejecuta la consulta
                        pg_query($conexion, $saldo);
                    }

                }

                //selecionar el monto total de cuentas_cobrar
                $cuencob_montotal = "select cuencob_montotal from cuentas_cobrar
                where ven_cod = $ven_cod;";

                //convertir en array para usar el dato obtenido
                $monto = pg_query($conexion, $cuencob_montotal);
                $cuencobMonTotal = pg_fetch_assoc($monto);
                
                //Dividir el monto total por el monto cuota y redondear hacia arriba el resultado
                $ven_cuotas = ceil((float)$cuencobMonTotal['cuencob_montotal'] / (float)$ven_montocuota);

                //actualizar el numero de cuotas en ventas cabecera y cuentas cobrar
                $sqlCuotas = "update ventas_cab
                set ven_cuotas = $ven_cuotas
                where ven_cod = $ven_cod;
                update cuentas_cobrar
                set cuencob_cuotas = $ven_cuotas
                where ven_cod = $ven_cod;";

                //ejecutar la consulta y muestra un mensaje
                pg_query($conexion, $sqlCuotas);
                $response5 = array(
                    "mensaje" => "actualizado",
                    "tipo" => "success"
                );
                echo json_encode($response5);
                
            }
    }
// si se selecciona anular en la cabecera
} else if ($_POST['operacion_cab'] == '2') {

    $notven_cod = $_POST['notven_cod'];

    /*se seleccionan los montos y los respectivos impuestos segun el ítem 
    cuando los códigos de venta y nota venta sean los mismos que se pasan por parámetro*/
    $sqlNotDetalle = "select
    nvd.itm_cod,
    nvd.tipitem_cod,
    nvd.notvendet_cantidad,
    case 
        when nvd.notvendet_cantidad = 0 then 
            nvd.notvendet_precio
        else
            (nvd.notvendet_cantidad * nvd.notvendet_precio)
    end as monto,
    i.tipimp_cod 
    from nota_venta_det nvd 
    join nota_venta_cab nvc on nvc.notven_cod = nvd.notven_cod
    join items i on i.itm_cod = nvd.itm_cod and i.tipitem_cod = nvd.tipitem_cod
        join tipo_impuesto ti on i.tipimp_cod = ti.tipimp_cod 
    where nvc.ven_cod = $ven_cod and nvd.notven_cod = $notven_cod;";
        
    //se convierte a array para utilizar lo obtenido
    $detalleDatos = pg_query($conexion,$sqlNotDetalle);
    $montoDet = pg_fetch_all($detalleDatos);

    //se recorre el array para actualizar los montos de las cuentas y libro de ventas
    foreach ($montoDet as $resultado) {

        $monto = $resultado['monto'];
        $tipimp_cod = $resultado['tipimp_cod'];
        $itm_cod = $resultado['itm_cod'];
        $notvendet_cantidad = $resultado['notvendet_cantidad'];

        $sqlMontos = "update cuentas_cobrar
        set cuencob_montotal = cuencob_montotal + $monto
        where ven_cod = $ven_cod;
        update libro_ventas
        set $imp[$tipimp_cod] = $imp[$tipimp_cod] + $monto,
        libven_estado = 'ACTIVO'
        where ven_cod = $ven_cod;
        update ventas_det 
        set vendet_cantidad = vendet_cantidad + $notvendet_cantidad
        where ven_cod = $ven_cod and itm_cod = $itm_cod;";

        //se ejecuta la consulta y se devuelve el mensaje
        pg_query($conexion, $sqlMontos);
        $response6 = array(
            "mensaje" => "actualizado como al principio cuentas, libro y cantidad de items"
        );
        echo json_encode($response6);
    }
    
    //se verifica si existe un registro de cobro detalle que tenga la misma venta
    $sqlCobrEx = "select sum(cobrdet_monto) as cobrdet_monto from cobros_det
    where ven_cod = $ven_cod;";
    
    //se convierte a array para utilizar lo obtenido
    $cobroExis = pg_query($conexion,$sqlCobrEx);
    $existeCobr = pg_fetch_assoc($cobroExis);
    $cobr = $existeCobr['cobrdet_monto'];

    //selecionar el monto total de cuentas_cobrar
    $cuencob_montotal = "select cuencob_montotal from cuentas_cobrar
    where ven_cod = $ven_cod;";

    //convertir en array para usar el dato obtenido
    $monto = pg_query($conexion, $cuencob_montotal);
    $cuencobMonTotal = pg_fetch_assoc($monto);
    $tot = $cuencobMonTotal['cuencob_montotal'];

    $cuencob_saldo = (float)($tot - $cobr);

    $sqlSaldo2 = "update cuentas_cobrar
    set cuencob_saldo = $cuencob_saldo
    where ven_cod = $ven_cod;";

    //se ejecuta la consulta y se devuelve el mensaje
    pg_query($conexion, $sqlSaldo2);
    $response7 = array(
        "mensaje" => "actualizado como al principio saldo"
    );
    echo json_encode($response7);
    
    //se verifica si la venta es al contado
    if ($ven_tipfac == 'CONTADO') {

        //se recorre el array de los montos para actualizar cuentas y venta
        foreach ($montoDet as $resultado) {

            $monto = $resultado['monto'];

            //si no existe un registro de cobro detalle se actualiza el estado de la cuenta y la venta en ACTIVO
            if (empty($existeCobr['cobrdet_monto'])) {

                $sqlCuentas = "update cuentas_cobrar
                set cuencob_estado = 'ACTIVO'
                where ven_cod = $ven_cod;
                update ventas_cab
                set ven_montocuota = ven_montocuota + $monto,
                ven_estado = 'ACTIVO'
                where ven_cod = $ven_cod;";

            //si existe un registro de cobro detalle se actualiza el estado de la cuenta y la venta en CANCELADO
            } else {
                
                $sqlCuentas = "update cuentas_cobrar
                set cuencob_estado = 'CANCELADO'
                where ven_cod = $ven_cod;
                update ventas_cab
                set ven_montocuota = ven_montocuota + $monto,
                ven_estado = 'CANCELADO'
                where ven_cod = $ven_cod;";
                }
            
            //se ejecuta la consulta y se devuelve un mensaje
            pg_query($conexion, $sqlCuentas);
            $response8 = array(
                "mensaje" => "actualizado como al principio estados (contado)"
            );
            echo json_encode($response8);
        }
    
    // si la venta es de tipo credito
    } else if ($ven_tipfac == 'CREDITO') {

        //se actualizan los estados de la cuenta y la venta
        $sqlCuentas = "update cuentas_cobrar
        set cuencob_estado = 'ACTIVO'
        where ven_cod = $ven_cod;
        update ventas_cab
        set ven_estado = 'ACTIVO'
        where ven_cod = $ven_cod;";

        //se ejecuta la consulta y se devuelve un mensaje
        pg_query($conexion, $sqlCuentas);
        $response9 = array(
            "mensaje" => "actualizado como al principio estados (credito)"
        );
        echo json_encode($response9);

        //selecionar el monto cuota de ventas_cab
        $ven_montocuota = "select ven_montocuota from ventas_cab
        where ven_cod = $ven_cod;";

        //convertir en array para usar el dato obtenido
        $cuota = pg_query($conexion, $ven_montocuota);
        $venMontoCuota = pg_fetch_assoc($cuota);
        
        //se calcula el numero de cuotas al dividir el monto total de cuentas_cobrar entre el monto cuota de ventas_cab
        $ven_cuotas = ceil((float)$cuencobMonTotal['cuencob_montotal'] / (float)$venMontoCuota['ven_montocuota']);

        //actualizar el numero de cuotas en ventas cabecera y cuentas cobrar
        $sqlCuotas = "update ventas_cab
        set ven_cuotas = $ven_cuotas
        where ven_cod = $ven_cod;
        update cuentas_cobrar
        set cuencob_cuotas = $ven_cuotas
        where ven_cod = $ven_cod;";

        //se ejecuta la consulta y se devuelve un mensaje
        pg_query($conexion, $sqlCuotas);
        $response10 = array(
            "mensaje" => "actualizado como al principio cantidad de cuotas"
        );
        echo json_encode($response10);
    }
}
?>