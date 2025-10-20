<?php
//Establecemos el retorno del documento en formato json
header("Content-type: application/json; charset=utf-8");
//Solicitamos la clase de Conexion
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

//Consultamos si existe la variable operacion
if (isset($_POST['operacion_cab'])) {

    //escapar los datos para que acepte comillas simples
    $notacom_concepto = pg_escape_string($conexion, $_POST['notacom_concepto']);
    $notacom_estado = pg_escape_string($conexion, $_POST['notacom_estado']);
    $pro_razonsocial = pg_escape_string($conexion, $_POST['pro_razonsocial']);
    $usu_login = pg_escape_string($conexion, $_POST['usu_login']);
    $suc_descri = pg_escape_string($conexion, $_POST['suc_descri']);
    $emp_razonsocial = pg_escape_string($conexion, $_POST['emp_razonsocial']);
    //funcionario_proveedor
    $funprov_nombres = pg_escape_string($conexion, $_POST['funprov_nombres']);
    $funprov_apellidos = pg_escape_string($conexion, $_POST['funprov_apellidos']);
    //marca_vehiculo
    $marcve_descri = pg_escape_string($conexion, $_POST['marcve_descri']);
    //modelo_vehiculo
    $modve_descri = pg_escape_string($conexion, $_POST['modve_descri']);


    //Obtener el nuevo codigo de funcionario_proveedor
    $sqlCodFun = "select coalesce(max(funprov_cod),0)+1 funprov_cod from funcionario_proveedor;";
    $resFun = pg_query($conexion, $sqlCodFun);
    $cod_fun = pg_fetch_assoc($resFun);

    //Obtener el nuevo codigo de marca_vehiculo
    $sqlCodMarca = "select coalesce(max(marcve_cod),0)+1 marcve_cod from marca_vehiculo;";
    $resMarca = pg_query($conexion, $sqlCodMarca);
    $cod_marca = pg_fetch_assoc($resMarca);

    //Obtener el nuevo codigo de modelo_vehiculo
    $sqlCodMode = "select coalesce(max(modve_cod),0)+1 modve_cod from modelo_vehiculo;";
    $resMode = pg_query($conexion, $sqlCodMode);
    $cod_mode = pg_fetch_assoc($resMode);

    //Obtener el nuevo codigo de chapa_vehiculo
    $sqlCodChapa = "select coalesce(max(chapve_cod),0)+1 chapve_cod from chapa_vehiculo;";
    $resChapa = pg_query($conexion, $sqlCodChapa);
    $cod_chapa = pg_fetch_assoc($resChapa);

    //Asignarle el valor a funprov_cod
    if ($_POST['funprov_cod'] == 0 && $_POST['tipcomp_cod'] == 3) {
        $funprov_cod = $cod_fun['funprov_cod'];
        //Insertar datos en funcionario_proveedor
        $sqlFun = "select sp_abm_funcionario_proveedor (
                        $funprov_cod, 
                        '$funprov_nombres', 
                        '$funprov_apellidos',
                        '{$_POST['funprov_nro_doc']}',
                        {$_POST['pro_cod']},
                        {$_POST['tiprov_cod']},
                        '$notacom_estado', -->funprov_estado
                        {$_POST['operacion_cab']},
                        {$_POST['usu_cod']},
                        '$usu_login',
                        '{$_POST['transaccion']}',
                        '$pro_razonsocial'
                    );";
        pg_query($conexion, $sqlFun);
        //echo json_encode(pg_last_notice($conexion));
    } else {
        $funprov_cod = $_POST['funprov_cod'];
    }
    
    //Asignarle el valor a marcve_cod
    if ($_POST['marcve_cod'] == 0 && $_POST['tipcomp_cod'] == 3) {
        $marcve_cod = $cod_marca['marcve_cod'];
        //Insertar datos en marca_vehiculo
        $sqlMarca = "select sp_abm_marca_vehiculo (
                        $marcve_cod, 
                        '$marcve_descri', 
                        '$notacom_estado', -->marcve_estado
                        {$_POST['operacion_cab']},
                        {$_POST['usu_cod']},
                        '$usu_login',
                        '{$_POST['transaccion']}'
                    );";
        pg_query($conexion, $sqlMarca);
        //echo json_encode(pg_last_notice($conexion));
    } else {
        $marcve_cod = $_POST['marcve_cod'];
    }
    
    //Asignarle el valor a modve_cod
    if ($_POST['modve_cod'] == 0 && $_POST['tipcomp_cod'] == 3) {
        $modve_cod = $cod_mode['modve_cod'];
        //Insertar datos en modelo_vehiculo
        $sqlMode = "select sp_abm_modelo_vehiculo (
                        $modve_cod, 
                        '$modve_descri', 
                        $marcve_cod, 
                        '$notacom_estado', -->modve_estado
                        {$_POST['operacion_cab']},
                        {$_POST['usu_cod']},
                        '$usu_login',
                        '$marcve_descri', 
                        '{$_POST['transaccion']}'
                    );";
        pg_query($conexion, $sqlMode);
        //echo json_encode(pg_last_notice($conexion));
    } else {
        $modve_cod = $_POST['modve_cod'];
    }
    
    //Asignarle el valor a chapve_cod
    if ($_POST['chapve_cod'] == 0 && $_POST['tipcomp_cod'] == 3) {
        $chapve_cod = $cod_chapa['chapve_cod'];
        //Insertar datos en chapa_vehiculo
        $sqlChapa = "select sp_abm_chapa_vehiculo (
                        $chapve_cod, 
                        '{$_POST['chapve_chapa']}',
                        '$notacom_estado', -->chapve_estado
                        $modve_cod, 
                        {$_POST['operacion_cab']},
                        {$_POST['usu_cod']},
                        '$usu_login',
                        '$modve_descri', 
                        $marcve_cod, 
                        '$marcve_descri', 
                        '{$_POST['transaccion']}'
                    );";
        pg_query($conexion, $sqlChapa);
        //echo json_encode(pg_last_notice($conexion));
    } else {
        $chapve_cod = $_POST['chapve_cod'];
    }

    //Se eliminan espacios en blanco
    $nronota = preg_replace('/\D/', '', $_POST['notacom_nronota']);
    //separar el nro de nota por guiones
    $notacom_nronota = substr($nronota, 0, 3).'-'.substr($nronota, 3, 3).'-'.substr($nronota, 6);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_nota_compra_cab(
        {$_POST['notacom_cod']},
        '{$_POST['notacom_fecha']}',
        '$notacom_nronota',
        '$notacom_concepto',
        '$notacom_estado',
        {$_POST['com_cod']},
        {$_POST['suc_cod']},
        {$_POST['emp_cod']},
        {$_POST['usu_cod']},
        {$_POST['tipcomp_cod']},
        {$_POST['pro_cod']},
        {$_POST['tiprov_cod']},
        '{$_POST['notacom_timbrado']}',
        '{$_POST['notacom_timb_fec_venc']}',
        $funprov_cod, --> notacom_funcionario
        $chapve_cod, 
        {$_POST['operacion_cab']},
        '$pro_razonsocial',
        '$usu_login',
        '$suc_descri',
        '$emp_razonsocial',
        '{$_POST['transaccion']}',
        {$_POST['com_cuotas']}
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "err_cab") !== false) {
        $response = array(
            "mensaje" => "ESTA NOTA YA ESTÁ CARGADA",
            "tipo" => "error"
        );
    } else if (strpos($error, "err_cuota") !== false) {
        $response = array(
            "mensaje" => "PARA COMPRAS AL CONTADO LA CANTIDAD DE CUOTAS DEBE SER 1",
            "tipo" => "error"
        );
    } else if (strpos($error, "err_fectim") !== false) {
        $response = array(
            "mensaje" => "LA FECHA DE VENCIMIENTO DEL TIMBRADO NO PUEDE SER MENOR A LA FECHA DE LA COMPRA",
            "tipo" => "error"
        );
    } else{
        $response = array(
            "mensaje" => pg_last_notice($conexion),
            "tipo" => "success"
        );
    }
    echo json_encode($response);

} else if (isset($_POST['consulCod']) == 1) {
    //Se obtiene el valor para asignar al codigo
    $notaComCod = "select coalesce (max(notacom_cod),0)+1 as codigo from nota_compra_cab;";

    $codigo = pg_query($conexion, $notaComCod);
    $codigoNotaCom = pg_fetch_assoc($codigo);
    echo json_encode($codigoNotaCom);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select * from v_nota_compra_cab;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>