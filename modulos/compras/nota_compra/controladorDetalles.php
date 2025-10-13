<?php
//Establecemos el retorno del documento en formato json
header("Content-type: application/json; charset=utf-8");
//Solicitamos la clase de Conexion
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();


if (isset($_POST['operacion_det'])) { //--> Consultamos si existe la variable operacion

    $notacomdet_cantidad = str_replace(",", ".", $_POST['notacomdet_cantidad']);
    $notacomdet_precio = str_replace(",", ".", $_POST['notacomdet_precio']);
    //establecemos el monto a pasar para los sp
    if ($_POST['tipitem_cod'] == "1") {
        $total = (float)$notacomdet_precio;
    } else {
        $total = $notacomdet_precio * $notacomdet_cantidad;
    }
    $case = $_POST['case'];
    $usu_login = pg_escape_string($conexion, $_POST['usu_login']);
    
    if ($case == "detalle") {

        //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
        $sql = "select sp_nota_compra_det(
            {$_POST['itm_cod']},
            {$_POST['tipitem_cod']},
            {$_POST['notacom_cod']},
            {$_POST['notacomdet_cantidad']},
            {$_POST['notacomdet_precio']},
            {$_POST['dep_cod']},
            {$_POST['suc_cod']},
            {$_POST['emp_cod']},
            {$_POST['operacion_det']},
            {$_POST['com_cod']},
            {$_POST['usu_cod']},
            '$usu_login'
        );";

        pg_query($conexion, $sql);
        $error = pg_last_error($conexion);
        //Si ocurre un error lo capturamos y lo enviamos al front-end
        if (strpos($error, "err_rep") !== false) {
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

    // } else if ($case == "libro") {
        
    //     $tipcomp_cod = $_POST['tipcomp_cod'];

    //     //Si es Nota de credito se pasa a negativo
    //     if ($tipcomp_cod == 1) {
    //         $total = $total * -1;
    //     }

    //     //establecemos los montos a pasar pra el sp_libro_compras segun el tipo de impuesto
    //     $tipimp_cod = $_POST["tipimp_cod"];
    //     if ($tipimp_cod == "1") {
    //         $exenta = $total;
    //         $iva5 = 0;
    //         $iva10 = 0;
    //     } else if ($tipimp_cod == "2") {
    //         $exenta = 0;
    //         $iva5 = $total;
    //         $iva10 = 0;
    //     } else if ($tipimp_cod == "3") {
    //         $exenta = 0;
    //         $iva5 = 0;
    //         $iva10 = $total;
    //     }
    //     //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    //     $sqlLibro = "select sp_libro_compras(
    //         {$_POST['com_cod']},
    //         '{$_POST['notacom_nronota']}',
    //         $exenta,
    //         $iva5,
    //         $iva10,
    //         $tipcomp_cod,
    //         {$_POST['operacion_det']},
    //         {$_POST['usu_cod']},
    //         '$usu_login'
    //     );";
        
    //     pg_query($conexion, $sqlLibro);

    // } else if ($case == "cuentas") {

    //     $tipcomp_cod = $_POST['tipcomp_cod'];
    //     $operacion = $_POST['operacion_det'];

    //     //Si es Nota de credito se pasa a negativo
    //     if ($tipcomp_cod == 1) {
    //         $total = $total * -1;
    //     }

    //     $sqlCuenta = "select sp_cuentas_pagar(
    //         {$_POST['com_cod']},
    //         $total,
    //         $total,
    //         $operacion,
    //         {$_POST['usu_cod']},
    //         '$usu_login'
    //     );";
        
    //     pg_query($conexion, $sqlCuenta);
    }

} else if (isset($_POST['notacom_cod'])) {

    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select * from v_nota_compra_det
            where notacom_cod = {$_POST['notacom_cod']};";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
    
} else if (isset($_POST['cantidad'])){

    //Si el post no recibe la operacion ni codigo se consulta cantidad en compra detalle
    $sql = "select cd.comdet_cantidad as cant 
    from compra_det cd 
    where cd.com_cod = {$_POST['com_cod']} 
        and cd.itm_cod = {$_POST['itm_cod']};";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_assoc($resultado);
    echo json_encode($datos);
}

?>