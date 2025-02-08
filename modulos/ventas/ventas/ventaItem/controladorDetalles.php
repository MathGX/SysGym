<?php
//Establecemos el retorno del documento en formato json
header("Content-type: application/json; charset=utf-8");
//Solicitamos la clase de Conexion
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

//Consultamos si existe la variable operacion
if (isset($_POST['operacion_det'])) {

    $vendet_cantidad = str_replace(",", ".", $_POST['vendet_cantidad']);
    $vendet_precio = str_replace(",", ".", $_POST['vendet_precio']);
    $total = $vendet_precio * $vendet_cantidad;
    $case = $_POST['case'];

    if ($case == "detalle") {

        //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
        $sql = "select sp_venta_det(
            {$_POST['ven_cod']},
            {$_POST['itm_cod']},
            {$_POST['tipitem_cod']},
            {$_POST['dep_cod']},
            {$_POST['suc_cod']},
            {$_POST['emp_cod']},
            $vendet_cantidad,
            $vendet_precio,
            {$_POST['operacion_det']}
        );";

        pg_query($conexion, $sql);
        $error = pg_last_error($conexion);
        //Si ocurre un error lo capturamos y lo enviamos al front-end
        if (strpos($error, "1") !== false) {
            $response = array(
                "mensaje" => "ESTE ITEM YA ESTÁ CARGADO",
                "tipo" => "error"
            );
        } else {
            $response = array(
                "mensaje" => pg_last_notice($conexion),
                "tipo" => "success"
            );
        }
        echo json_encode($response);

    } else if ($case == "libro") {
        //establecemos los montos a pasar pra el sp_libro_ventas segun el tipo de impuesto
        $tipimp_cod = $_POST["tipimp_cod"];
        if ($tipimp_cod == "1") {
            $exenta = $total;
            $iva5 = 0;
            $iva10 = 0;
        } else if ($tipimp_cod == "2") {
            $exenta = 0;
            $iva5 = $total;
            $iva10 = 0;
        } else if ($tipimp_cod == "3") {
            $exenta = 0;
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
            '{$_POST['ven_nrofac']}',
            $exenta,
            $iva5,
            $iva10,
            {$_POST['tipcomp_cod']},
            {$_POST['operacion_det']}
        );";
        
        pg_query($conexion, $sqlLibro);

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
    }

} else if (isset($_POST['ven_cod'])){

    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select * from v_venta_det
            where ven_cod = {$_POST['ven_cod']};";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}

?>