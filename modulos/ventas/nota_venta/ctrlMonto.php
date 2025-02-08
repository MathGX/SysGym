<?php

//Establecemos el retorno del documento en formato json
header("Content-type: application/json; charset=utf-8");
//Solicitamos la clase de Conexion
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$ven_cod = $_POST["ven_cod"];
$notvendet_precio = $_POST["notvendet_precio"];
$notvendet_cantidad = $_POST["notvendet_cantidad"];
$operacion = $_POST['operacion_det'];

if ($_POST['tipitem_cod'] == "1") {
    $total = (float)$notvendet_precio;
} else {
    $total = (float)$notvendet_precio * (float)$notvendet_cantidad;
}

if($operacion == '1'){

    //se selecciona la columna cobrdet_monto de cobros detalle
    $sqlMonto = "select cuencob_montotal from cuentas_cobrar
    where ven_cod = $ven_cod";
    
    //se convierte en array asociativo lo obtenido
    $respuesta = pg_query($conexion, $sqlMonto);
    $obtenido = pg_fetch_assoc($respuesta);

    /*si el total del monto de nota venta detalle es mayor al saldo de la deuda
    da un mensaje de error, si no, se ejecutan las actualizaciones*/
    if ($total > (float)$obtenido['cuencob_montotal']) {
        $response2 = array(
            "mensaje" => "EL MONTO EXCEDE EL VALOR DE LO ADEUDADO",
            "tipo" => "error"
        );

        echo json_encode($response2);

    } else {
        $response2 = array(
            "mensaje" => "EL MONTO ES VALIDO",
            "tipo" => "success"
        );

        echo json_encode($response2);
    }

} else {

    $response2 = array(
        "mensaje" => "ELIMINACION DEL DETALLE",
        "tipo" => "success"
    );

    echo json_encode($response2);
}

?>