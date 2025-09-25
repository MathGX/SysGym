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
    $ven_intefecha = pg_escape_string($conexion, $_POST['ven_intefecha']);
    $ven_estado = pg_escape_string($conexion, $_POST['ven_estado']);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_venta_cab(
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
        0,
        '0',
        {$_POST['operacion_cab']}
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "1") !== false) {
        $response = array(
            "mensaje" => "ESTE N° DE FACTURA YA ESTÁ CARGADO",
            "tipo" => "error"
        );
    } else {
        $response = array(
            "mensaje" => pg_last_notice($conexion),
            "tipo" => "success"
        );
    
        if ($_POST['operacion_cab'] == 1) {
            
            $fac_nro_new = (int)substr($_POST['ven_nrofac'],8,7);
    
            $upFac="update facturas set
            fac_nro = lpad(cast($fac_nro_new as text),7,'0')
            where suc_cod = {$_POST['suc_cod']} and caj_cod = {$_POST['caj_cod']}";
            
            pg_query($conexion, $upFac);
        }

    }
    echo json_encode($response);

} else if (isset($_POST['consulCod']) == 1) {
    //Se obtiene el valor para asignar al codigo
    $venCod = "select coalesce (max(ven_cod),0)+1 as codigo from ventas_cab;";

    $codigo = pg_query($conexion, $venCod);
    $codigoVen = pg_fetch_assoc($codigo);
    echo json_encode($codigoVen);

} else if (isset($_POST['consulFactura']) == 1) {

    //Se obtiene el valor para asignar al codigo
    $factura = "select 
        lpad(cast(f.suc_cod as text), 3, '0')|| '-' || 
        lpad(cast(f.caj_cod as text), 3, '0')|| '-' ||
        lpad(cast((coalesce(max(cast(fac_nro as integer)),0)+1) as text), 7, '0') as factura 
    from timbrados f
    where f.suc_cod = {$_POST['suc_cod']} 
        and f.emp_cod = {$_POST['emp_cod']} 
        and f.caj_cod = {$_POST['caj_cod']} 
        and f.tipcomp_cod = {$_POST['tipcomp_cod']}
    group by f.suc_cod, f.caj_cod;";

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