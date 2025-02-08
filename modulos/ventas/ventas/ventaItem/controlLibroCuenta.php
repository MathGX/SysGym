<?php
//Establecemos el retorno del documento en formato json
header("Content-type: application/json; charset=utf-8");
//Solicitamos la clase de Conexion
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$vendet_precio = $_POST["vendet_precio"];
$vendet_cantidad = $_POST["vendet_cantidad"];
$total = ((float)$vendet_precio * (float)$vendet_cantidad);
$case = $_POST['case'];

//Consultamos si existe la variable operacion
if (isset($_POST['ven_cod'])) {

    if ($case == "libro") {

        //establecemos los montos a pasar pra el sp_libro_ventas segun el tipo de impuesto
        $tipimp_cod = $_POST["tipimp_cod"];
        if ($tipimp_cod == "1") {
            $excenta = $total;
            $iva5 = 0;
            $iva10 = 0;
        } else if ($tipimp_cod == "2") {
            $excenta = 0;
            $iva5 = $total;
            $iva10 = 0;
        } else if ($tipimp_cod == "3") {
            $excenta = 0;
            $iva5 = 0;
            if ($_POST['tipitem_cod'] == "1") {
                $iva10 = (float)$vendet_precio;
            } else {
                $iva10 = $total;
            }
        }

        //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
        $sqlLibro = "select sp_libro_ventas(
            {$_POST['ven_cod']},
            $excenta,
            $iva5,
            $iva10,
            {$_POST['operacion_det']}
        );";
        
        pg_query($conexion, $sqlLibro);

        $response = array(
            "mensaje" => "actualizado libro ventas",
        );
        echo json_encode($response);

    } else if ($case == "cuentas") {

        //establecemos el monto a pasar para el sp_cuentas_cobrar
        if ($_POST['tipitem_cod'] == "1") {
            $totalCuenta = (float)$vendet_precio;
        } else {
            $totalCuenta = $total;
        }

        //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
        $sqlCuenta = "select sp_cuentas_cobrar(
            {$_POST['ven_cod']},
            $totalCuenta,
            $totalCuenta,
            {$_POST['operacion_det']}
        );";

        pg_query($conexion, $sqlCuenta);

        $response = array(
            "mensaje" => "actualizado cuentas a cobrar",
        );
        echo json_encode($response);
    }

}

?>