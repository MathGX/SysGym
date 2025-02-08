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

if ($_POST['tipitem_cod'] == "1") {
    $total = (float)$notvendet_precio;
} else {
    $total = (float)$notvendet_precio * (float)$notvendet_cantidad;
}

//Consultamos si existe la variable operacion
if (($tipcomp_cod == "2") && ($ven_tipfac == "CONTADO")) {

    //segun la operacion se suman o se restan los montos en cuentas a cobrar, libro ventas y ventas
    if ($_POST['operacion_det'] == "1") {
        $sqlCuentas = "update cuentas_cobrar
        set cuencob_montotal = cuencob_montotal + $total, 
        cuencob_saldo = cuencob_saldo + $total
        where ven_cod = $ven_cod;
        update ventas_cab
        set ven_montocuota = ven_montocuota + $total
        where ven_cod = $ven_cod;
        update libro_ventas
        set $imp[$tipimp_cod] = $imp[$tipimp_cod] + $total
        where ven_cod = $ven_cod;";
    } else if ($_POST['operacion_det'] == "2") {
        $sqlCuentas = "update cuentas_cobrar
        set cuencob_montotal = cuencob_montotal - $total, 
        cuencob_saldo = cuencob_saldo - $total
        where ven_cod = $ven_cod;
        update ventas_cab
        set ven_montocuota = ven_montocuota - $total
        where ven_cod = $ven_cod;
        update libro_ventas
        set $imp[$tipimp_cod] = $imp[$tipimp_cod] - $total
        where ven_cod = $ven_cod;";
    }

    pg_query($conexion, $sqlCuentas);
    
    $response0 = array(
        "mensaje" => "actualizado",
        "tipo" => "success"
    );

    echo json_encode($response0);

    } else if (($tipcomp_cod == "2") && ($ven_tipfac == 'CREDITO')) {

        //segun la operacion se suman o se restan los montos en cuentas a cobrar, libro ventas y ventas
        if ($_POST['operacion_det'] == "1") {
            $sqlCuentas = "update cuentas_cobrar
            set cuencob_montotal = cuencob_montotal + $total, 
            cuencob_saldo = cuencob_saldo + $total
            where ven_cod = $ven_cod;
            update libro_ventas
            set $imp[$tipimp_cod] = $imp[$tipimp_cod] + $total
            where ven_cod = $ven_cod;";
        } else if ($_POST['operacion_det'] == "2") {
            $sqlCuentas = "update cuentas_cobrar
            set cuencob_montotal = cuencob_montotal - $total, 
            cuencob_saldo = cuencob_saldo - $total
            where ven_cod = $ven_cod;
            update libro_ventas
            set $imp[$tipimp_cod] = $imp[$tipimp_cod] - $total
            where ven_cod = $ven_cod;";
        }

        pg_query($conexion, $sqlCuentas);

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

        pg_query($conexion, $sqlCuotas);

        $response1 = array(
            "mensaje" => "actualizado",
            "tipo" => "success"
        );

        echo json_encode($response1);

    } 
// si se selecciona anular en la cabecera
} else if ($_POST['operacion_cab'] == '2') {

    $notven_cod = $_POST['notven_cod'];

    /*se seleccionan los montos y los respectivos impuestos segun el ítem 
    cuando los códigos de venta y nota venta sean los mismos que se pasan por parámetro*/
    $sqlNotDetalle = "select
    nvd.itm_cod,
    nvd.tipitem_cod,
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

        $sqlMontos = "update cuentas_cobrar
        set cuencob_montotal = cuencob_montotal - $monto, 
        cuencob_saldo = cuencob_saldo - $monto
        where ven_cod = $ven_cod;
        update libro_ventas
        set $imp[$tipimp_cod] = $imp[$tipimp_cod] - $monto,
        libven_estado = 'ACTIVO'
        where ven_cod = $ven_cod;";

        //se ejecuta la consulta y se devuelve el mensaje
        pg_query($conexion, $sqlMontos);
        $response2 = array(
            "mensaje" => "actualizado como al principio cuentas y libro ventas"
        );
        echo json_encode($response2);
    }

    //se verifica si la venta es al contado
    if ($ven_tipfac == 'CONTADO') {

        //se verifica si existe un registro de cobro detalle que tenga la misma venta
        $sqlCobrEx = "select sum(cobrdet_monto) as cobrdet_monto from cobros_det
        where ven_cod = $ven_cod;";
        
        //se convierte a array para utilizar lo obtenido
        $cobroExis = pg_query($conexion,$sqlCobrEx);
        $existeCobr = pg_fetch_assoc($cobroExis);

        //se recorre el array de los montos para actualizar cuentas y venta
        foreach ($montoDet as $resultado) {

            $monto = $resultado['monto'];

            //si no existe un registro de cobro detalle se actualiza el estado de la cuenta y la venta en ACTIVO
            if (empty($existeCobr['cobrdet_monto'])) {

                $sqlCuentas = "update cuentas_cobrar
                set cuencob_estado = 'ACTIVO'
                where ven_cod = $ven_cod;
                update ventas_cab
                set ven_montocuota = ven_montocuota - $monto,
                ven_estado = 'ACTIVO'
                where ven_cod = $ven_cod;";

            //si existe un registro de cobro detalle se actualiza el estado de la cuenta y la venta en CANCELADO
            } else {
                
                $sqlCuentas = "update cuentas_cobrar
                set cuencob_estado = 'CANCELADO'
                where ven_cod = $ven_cod;
                update ventas_cab
                set ven_montocuota = ven_montocuota - $monto,
                ven_estado = 'CANCELADO'
                where ven_cod = $ven_cod;";
                }
            
            //se ejecuta la consulta y se devuelve un mensaje
            pg_query($conexion, $sqlCuentas);
            $response3 = array(
                "mensaje" => "actualizado como al principio estados (contado)"
            );
            echo json_encode($response3);
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
        $response4 = array(
            "mensaje" => "actualizado como al principio estados (credito)"
        );
        echo json_encode($response4);
        
        //selecionar el monto total de cuentas_cobrar
        $cuencob_montotal = "select cuencob_montotal from cuentas_cobrar
        where ven_cod = $ven_cod;";

        //convertir en array para usar el dato obtenido
        $monto = pg_query($conexion, $cuencob_montotal);
        $cuencobMonTotal = pg_fetch_assoc($monto);

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
        $response5 = array(
            "mensaje" => "actualizado como al principio cantidad de cuotas"
        );
        echo json_encode($response5);
    }
}

?>