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
        $sql = "select sp_ventas_det(
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
        if (strpos($error, "err_det") !== false) {
            $response = array(
                "mensaje" => "ESTE ITEM YA ESTÁ CARGADO",
                "tipo" => "error"
            );
        } else {
            //Se consulta la cantidad y minimo de item
            $sqlCant = "select 
                            s.sto_cantidad cant, 
                            i.itm_stock_min min,
                            i.itm_descri descri
                        from stock s
                            join items i on i.itm_cod = s.itm_cod
                        where s.itm_cod = {$_POST['itm_cod']}
                            and s.dep_cod = {$_POST['dep_cod']};";
            //se envia la consulta a la BD
            $cantidad = pg_query($conexion, $sqlCant);
            //Se convierte a array la respuesta de la consulta
            $stoCant = pg_fetch_assoc($cantidad);
            //Se verifica si se alcanzó el stock minimo permitido
            if ($stoCant['cant'] <= $stoCant['min'] && $_POST['tipitem_cod'] != 1) {
                $msj = pg_last_notice($conexion).", Y SE HA ALCANZADO LA CANTIDAD MINIMA DE ".$stoCant['descri']." EN STOCK";
            } else {
                $msj = pg_last_notice($conexion);
            }
            //Se envia el mensaje armado
            $response = array(
                "mensaje" => $msj,
                "tipo" => "success"
            );
        }
        echo json_encode($response);

    }/* else if ($case == "libro") {
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
    }*/
} else if (isset($_POST['validacion_det']) == 1) {
    //Se consulta si la venta esta asociada a una nota
    $venCod = "select 1 validar from nota_venta_cab ncc 
                where ncc.ven_cod = {$_POST['ven_cod']}
                    and ncc.notven_estado != 'ANULADO';";

    $codigo = pg_query($conexion, $venCod);
    $codigoven = pg_fetch_assoc($codigo);
    echo json_encode($codigoven);

} else if (isset($_POST['ven_cod'])){

    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select * from v_venta_det
            where ven_cod = {$_POST['ven_cod']};";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}

?>