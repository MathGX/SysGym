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
        $prpr_estado = pg_escape_string($conexion, $_POST['prpr_estado']);
        $cliente = pg_escape_string($conexion, $_POST['cliente']);
        $usu_login = pg_escape_string($conexion, $_POST['usu_login']);
        $suc_descri = pg_escape_string($conexion, $_POST['suc_descri']);
        $emp_razonsocial = pg_escape_string($conexion, $_POST['emp_razonsocial']);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_presupuesto_prep_cab(
        {$_POST['prpr_cod']},
        '{$_POST['prpr_fecha']}',
        '$prpr_estado',
        {$_POST['ins_cod']},
        {$_POST['cli_cod']},
        {$_POST['usu_cod']},
        {$_POST['suc_cod']},
        {$_POST['emp_cod']},
        '{$_POST['prpr_fechavenci']}',
        {$_POST['operacion_cab']},
        '{$_POST['per_nrodoc']}',
        '$cliente',
        '$suc_descri',
        '$emp_razonsocial',
        '$usu_login',
        '{$_POST['transaccion']}'
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "client") !== false) {
        $response = array(
            "mensaje" => "ESTE CLIENTE YA CUENTA CON PRESUPUESTO",
            "tipo" => "error"
        );
    } else if (strpos($error, "fecha") !== false) {
        $response = array(
            "mensaje" => "LA FECHA DE VENCIMIENTO NO PUEDE SER INFERIOR A LA FECHA DE EMISION",
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
    $prprCod = "select coalesce (max(prpr_cod),0)+1 as codigo from presupuesto_prep_cab;";

    $codigo = pg_query($conexion, $prprCod);
    $codigoPrpr = pg_fetch_assoc($codigo);
    echo json_encode($codigoPrpr);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select * from v_presupuesto_prep_cab;";

    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>