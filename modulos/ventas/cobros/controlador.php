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
    $estado = $_POST['cobr_estado'];

    //escapar los datos para que acepte comillas simples
    $cobr_estado = pg_escape_string($conexion, $estado);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_cobros_cab(
        {$_POST['cobr_cod']},
        '{$_POST['cobr_fecha']}',
        {$_POST['cobr_nrocuota']},
        '$cobr_estado',
        {$_POST['caj_cod']},
        {$_POST['suc_cod']},
        {$_POST['emp_cod']},
        {$_POST['usu_cod']},
        {$_POST['apcier_cod']},
        {$_POST['tipcomp_cod']},
        {$_POST['ven_cod']},
        '{$_POST['cobr_nrorec']}',
        {$_POST['operacion_cab']}
    );";

    pg_query($conexion, $sql);
    
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "err_reci") !== false) {
        $response = array(
            "mensaje" => "EL NRO DE RECIBO YA SE ENCUENTRA REGISTRADO",
            "tipo" => "error"
        );
    } else if (strpos($error, "err_cuota") !== false) {
        $response = array(
            "mensaje" => "PARA ANULAR ESTE COBRO, ANTES DEBE ANULAR EL DE LAS CUOTAS SUPERIORES",
            "tipo" => "error"
        );
    } else {
        $response = array(
            "mensaje" => pg_last_notice($conexion),
            "tipo" => "success"
        );
    }
    echo json_encode($response);
} else if (isset($_POST['consulNroComprob']) == 1) {
    
    //Se obtiene el siguiente nro de comprobante
    $comprobante = "select 
        round(f.tim_com_nro_lim - (coalesce(max(f.tim_com_nro::integer),0)+1)) disponibles,
        lpad(cast((coalesce(max(cast(tim_com_nro as integer)),0)+1) as text), 7, '0') as comprobante 
    from timbrados f
    where f.tipcomp_cod = {$_POST['tipcomp_cod']}
    group by f.tim_com_nro_lim";

    $nroComprob = pg_query($conexion, $comprobante);
    $nroCom = pg_fetch_assoc($nroComprob);

    echo json_encode($nroCom);

} else if (isset($_POST['consulCod']) == 1) {
    //Se obtiene el valor para asignar al codigo
    $cobrCod = "select coalesce (max(cobr_cod),0)+1 as codigo from cobros_cab;";

    $codigo = pg_query($conexion, $cobrCod);
    $codigoCobr = pg_fetch_assoc($codigo);
    echo json_encode($codigoCobr);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select * from v_cobros_cab;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>