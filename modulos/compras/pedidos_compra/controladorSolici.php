<?php
//Establecemos el retorno del documento en formato json
header("Content-type: application/json; charset=utf-8");
//Solicitamos la clase de Conexion
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

//Si se trata de alguna operacion de cabecera de solicitud de presupuesto
if ($_POST['cab_det'] === "cab") {   
    //Consultamos si existe la variable operacion
    if (isset($_POST['operacion_cab'])) {

        //Se escapan los datos para que se acepten comillas simples
        $solpre_email = pg_escape_string($conexion, $_POST['solpre_email']);

        //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
        $sql = "select sp_solicitud_presup_cab(
            {$_POST['solpre_cod']},
            {$_POST['pedcom_cod']},
            {$_POST['pro_cod']},
            {$_POST['tiprov_cod']},
            '$solpre_email',
            {$_POST['usu_cod']},
            {$_POST['suc_cod']},
            {$_POST['emp_cod']},
            {$_POST['operacion_cab']}
        );";

        pg_query($conexion, $sql);
        $error = pg_last_error($conexion);
        //Si ocurre un error lo capturamos y lo enviamos al front-end
        if (strpos($error, "err_prove") !== false) {
            $response = array(
                "mensaje" => "YA FUE ENVIADA UNA SOLICITUD AL PROVEEDOR SELECCIONADO",
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
        $solpreCod = "select coalesce (max(solpre_cod),0)+1 as codigo from solicitud_presup_cab;";

        $codigo = pg_query($conexion, $solpreCod);
        $codigosolpre = pg_fetch_assoc($codigo);
        echo json_encode($codigosolpre);

    } else {
        //Si el post no recibe la operacion realizamos una consulta
        $sql = "select 
                    spc.solpre_cod,
                    spc.solpre_fecha,
                    u.usu_login as usu_login_sol,
                    s.suc_descri as suc_descri_sol,
                    spc.pedcom_cod as pedcom_cod_sol,
                    spc.pro_cod,
                    spc.tiprov_cod,
                    spc.solpre_email,
                    p.pro_ruc,
                    p.pro_razonsocial||' - RUC: '||p.pro_ruc as pro_razonsocial
                from solicitud_presup_cab spc
                    join proveedor p on p.pro_cod = spc.pro_cod and p.tiprov_cod = spc.tiprov_cod
                    join pedido_compra_cab pcc on pcc.pedcom_cod = spc.pedcom_cod 
                    join usuarios u on u.usu_cod = spc.usu_cod 
                    join sucursales s on s.suc_cod = spc.suc_cod and s.emp_cod = spc.emp_cod 
                where pcc.pedcom_estado = 'ACTIVO'
                order by spc.solpre_cod;";

        $resultado = pg_query($conexion, $sql);
        $datos = pg_fetch_all($resultado);
        echo json_encode($datos);
    }

//Si se trata de alguna operacion de detalle de solicitud de presupuesto
} else if (isset($_POST['cab_det']) == 'det') {   

    //Consultamos si existe la variable operacion
    if (isset($_POST['operacion_det'])) {

        $solpredet_cantidad = str_replace(",", ".", $_POST['solpredet_cantidad']);

        //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
        $sql = "select sp_solicitud_presup_det(
            {$_POST['solpre_cod']},
            {$_POST['pedcom_cod']},
            {$_POST['pro_cod']},
            {$_POST['tiprov_cod']},
            {$_POST['itm_cod']},
            {$_POST['tipitem_cod']},
            $solpredet_cantidad,
            {$_POST['operacion_det']}
        );";

        pg_query($conexion, $sql);
        $error = pg_last_error($conexion);
        //Si ocurre un error lo capturamos y lo enviamos al front-end
        if (strpos($error, "err_dup") !== false) {
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

    } else if (isset($_POST['validacion_det']) == 1) {
        //Se consulta si el pedido de compra esta asociado a un presupuesto
        $pedcomCod = "select 1 validar from pedido_presupuesto pp
                        join presupuesto_prov_cab ppc on pp.presprov_cod = ppc.presprov_cod
                    where pp.pedcom_cod = {$_POST['pedcom_cod']}
                        and ppc.presprov_estado != 'ANULADO';";

        $codigo = pg_query($conexion, $pedcomCod);
        $codigoPedcom = pg_fetch_assoc($codigo);
        echo json_encode($codigoPedcom);

    } else if (isset($_POST['solpre_cod'])){

        //Si el post no recibe la operacion realizamos una consulta
        $sql = "select 
                    spd.itm_cod as itm_cod_sol,
                    i.itm_descri as itm_descri_sol,
                    i.tipitem_cod as tipitem_cod_sol,
                    spd.solpredet_cantidad 
                from solicitud_presup_det spd 
                    join items i on i.itm_cod = spd.itm_cod
                where spd.solpre_cod = {$_POST['solpre_cod']};";

        $resultado = pg_query($conexion, $sql);
        $datos = pg_fetch_all($resultado);
        echo json_encode($datos);
    }

}
?>