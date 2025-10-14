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

    //captura de datos desde el front-end
    $concepto = $_POST['notven_concepto'];
    $estado = $_POST['notven_estado'];
        
    //escapar los datos para que acepte comillas simples
    $notven_concepto = pg_escape_string($conexion, $concepto);
    $notven_estado = pg_escape_string($conexion, $estado);
    
    //Se determina el codigo de caja segun el perfil del usuario
    if ($_POST['perf_cod'] == 2) {
        $caj_cod = obtenerConfig($_POST['suc_cod'], $_POST['emp_cod'], 3);
    } else {
        $caj_cod = $_POST['caj_cod'];
    }

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_nota_venta_cab(
        {$_POST['notven_cod']},
        '{$_POST['notven_fecha']}',
        '{$_POST['notven_timbrado']}',
        '{$_POST['notven_nronota']}',
        '$notven_concepto',
        {$_POST['notven_funcionario']},
        {$_POST['notven_chapa_vehi']},
        '$notven_estado',
        {$_POST['tipcomp_cod']},
        {$_POST['ven_cod']},
        {$_POST['suc_cod']},
        {$_POST['emp_cod']},
        {$_POST['usu_cod']},
        {$_POST['cli_cod']},
        '{$_POST['notven_timb_fec_venc']}',
        {$_POST['operacion_cab']},
        {$_POST['ven_cuotas']},
        $caj_cod
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "repe") !== false) {
        $response = array(
            "mensaje" => "ESTA NOTA YA ESTÁ CARGADA",
            "tipo" => "error"
        );
    } else if (strpos($error, "err_cuota") !== false) {
        $response = array(
            "mensaje" => "PARA COMPRAS AL CONTADO LA CANTIDAD DE CUOTAS DEBE SER 1",
            "tipo" => "error"
        );
    } else {
        $response = array(
            "mensaje" => pg_last_notice($conexion),
            "tipo" => "success"
        );
    }
    echo json_encode($response);

} else if (isset($_POST['consulComprob']) == 1) {
    
    //Se determina el codigo de caja segun el perfil del usuario
    if ($_POST['perf_cod'] == 2) {
        $caj_cod = obtenerConfig($_POST['suc_cod'], $_POST['emp_cod'], 3);
    } else {
        $caj_cod = $_POST['caj_cod'];
    }

    //Se obtiene el siguiente nro de nota
    $comprobante = "select 
        f.tim_nro,
        f.tim_fec_venc,
        round(f.tim_com_nro_lim - (coalesce(max(f.tim_com_nro::integer),0)+1)) disponibles,
        lpad(cast(f.suc_cod as text), 3, '0')|| '-' || 
        lpad(cast(f.caj_cod as text), 3, '0')|| '-' ||
        lpad(cast((coalesce(max(cast(tim_com_nro as integer)),0)+1) as text), 7, '0') as comprobante 
    from timbrados f
    where f.suc_cod = {$_POST['suc_cod']} 
        and f.emp_cod = {$_POST['emp_cod']} 
        and f.caj_cod = $caj_cod
        and f.tipcomp_cod = {$_POST['tipcomp_cod']}
    group by f.tim_nro, f,tim_fec_venc, f.suc_cod, f.caj_cod, f.tim_com_nro, tim_com_nro_lim;";

    $nrocomprobante = pg_query($conexion, $comprobante);
    $nroComp = pg_fetch_assoc($nrocomprobante);

    echo json_encode($nroComp);

} else if (isset($_POST['consulCod']) == 1) {
    //Se obtiene el valor para asignar al codigo
    $notVenCod = "select coalesce (max(notven_cod),0)+1 as codigo from nota_venta_cab;";

    $codigo = pg_query($conexion, $notVenCod);
    $codigoNotVen = pg_fetch_assoc($codigo);
    echo json_encode($codigoNotVen);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select * from v_nota_venta_cab;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>