<?php
//Establecemos el retorno del documento en formato json
header("Content-type: application/json; charset=utf-8");
//Solicitamos la clase de Conexion
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

// Se inluyen funciones de uso general
include "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/extension/importPHP.php"; 

//Consultamos si existe la variable operacion
if (isset($_POST['operacion_cab'])) {
    
    //Se determina el codigo de caja segun el perfil del usuario
    if ($_POST['perf_cod'] == 9) {
        $caj_cod = obtenerConfig($_POST['suc_cod'], $_POST['emp_cod'], 2);
    } else {
        $caj_cod = $_POST['caj_cod'];
    }
    
    //escapar los datos para que acepte comillas simples
    $ven_intefecha = pg_escape_string($conexion, $_POST['ven_intefecha']);
    $ven_estado = pg_escape_string($conexion, $_POST['ven_estado']);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_ventas_cab(
        {$_POST['ven_cod']},
        '{$_POST['ven_fecha']}',
        '{$_POST['ven_nrofac']}',
        '{$_POST['ven_tipfac']}',
        {$_POST['ven_cuotas']},
        {$_POST['ven_montocuota']},
        '$ven_intefecha',
        '$ven_estado',
        {$_POST['cli_cod']},
        {$_POST['usu_cod']},
        {$_POST['suc_cod']},
        {$_POST['emp_cod']},
        '{$_POST['ven_timbrado']}',
        {$_POST['tipcomp_cod']},
        '{$_POST['ven_timb_fec_venc']}',
        --pedven_cod,
        {$_POST['prpr_cod']},
        {$_POST['operacion_cab']},
        $caj_cod
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "err_fac") !== false) {
        $response = array(
            "mensaje" => "ESTE N° DE FACTURA YA ESTÁ CARGADO",
            "tipo" => "error"
        );
    } else if (strpos($error, "err_cab") !== false) {
        $response = array(
            "mensaje" => "EL ESTADO DE LA VENTA IMPIDE QUE SEA ANULADA, SE ENCUENTRA ASOCIADA A UNA NOTA  ",
            "tipo" => "error"
        );
    } else {
        $response = array(
            "mensaje" => pg_last_notice($conexion),
            "tipo" => "success"
        );
    }
    echo json_encode($response);

} else if (isset($_POST['consulCod']) == 1) {
    //Se obtiene el valor para asignar al codigo
    $venCod = "select coalesce (max(ven_cod),0)+1 as codigo from ventas_cab;";

    $codigo = pg_query($conexion, $venCod);
    $codigoVen = pg_fetch_assoc($codigo);
    echo json_encode($codigoVen);

} else if (isset($_POST['consulFactura']) == 1) {
    
    //Se determina el codigo de caja segun el perfil del usuario
    if ($_POST['perf_cod'] == 9) {
        $caj_cod = obtenerConfig($_POST['suc_cod'], $_POST['emp_cod'], 2);
    } else {
        $caj_cod = $_POST['caj_cod'];
    }

    //Se obtiene el siguiente nro de factura
    $factura = "select 
        f.tim_nro,
        f.tim_fec_venc,
        round(f.tim_com_nro_lim - (coalesce(max(f.tim_com_nro::integer),0)+1)) disponibles,
        lpad(cast(f.suc_cod as text), 3, '0')|| '-' || 
        lpad(cast(f.caj_cod as text), 3, '0')|| '-' ||
        lpad(cast((coalesce(max(cast(tim_com_nro as integer)),0)+1) as text), 7, '0') as factura 
    from timbrados f
    where f.suc_cod = {$_POST['suc_cod']} 
        and f.emp_cod = {$_POST['emp_cod']} 
        and f.caj_cod = $caj_cod
        and f.tipcomp_cod = {$_POST['tipcomp_cod']}
    group by f.tim_nro, f,tim_fec_venc, f.suc_cod, f.caj_cod, f.tim_com_nro, tim_com_nro_lim;";

    $nroFactura = pg_query($conexion, $factura);
    $nroFac = pg_fetch_assoc($nroFactura);

    echo json_encode($nroFac);
} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select * from v_venta_cab;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>