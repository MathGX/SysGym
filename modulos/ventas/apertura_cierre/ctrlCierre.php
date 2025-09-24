<?php
session_start();

//Establecemos el retorno del documento en formato json
header("Content-type: application/json; charset=utf-8");
//Solicitamos la clase de Conexion
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();



if ($_POST['caso'] == 'cierre') {

    $sqlCierre = "select
    coalesce(sum((case when cd.forcob_cod = 1 then cd.cobrdet_monto end)),0) as cheque,
    coalesce(sum((case when cd.forcob_cod = 2 then cd.cobrdet_monto end)),0) as efectivo,
    coalesce(sum(cd.cobrdet_monto),0) as cobrdet_monto
    from cobros_det cd 
    join cobros_cab cc on cc.cobr_cod = cd.cobr_cod 
        join apertura_cierre ac on ac.apcier_cod = cc.apcier_cod
    where cc.cobr_fecha between ac.apcier_fechahora_aper and '{$_POST['apcier_fechahora_cierre']}'
    and ac.apcier_cod = {$_POST['apcier_cod']}
    and cc.cobr_estado ilike 'ACTIVO';";

    $monto = pg_query($conexion, $sqlCierre);
    $montoCierre = pg_fetch_assoc($monto);
    echo json_encode ($montoCierre);

    if ($_POST['motivo'] == 'recaudacion') { 

        $sqlRecaudacion = "
        do $$
        begin
            insert into recaudaciones_depositar 
                (rec_cod,
                caj_cod,
                suc_cod,
                emp_cod,
                usu_cod,
                apcier_cod,
                rec_montoefec,
                rec_montocheq,
                rec_estado)
            values 
                ((select coalesce (max(rec_cod),0)+1 from recaudaciones_depositar),
                {$_POST['caj_cod']},
                {$_POST['suc_cod']},
                {$_POST['emp_cod']},
                {$_POST['usu_cod']},
                {$_POST['apcier_cod']},
                {$montoCierre['efectivo']},            
                {$montoCierre['cheque']},
                'ACTIVO');
        end
        $$;";
        
        pg_query($conexion, $sqlRecaudacion);
    }
    
} else if ($_POST['caso'] == 'arqueo') {
    $sqlArqueo = "
    do $$;
    begin
        insert into arqueo_control 
            (caj_cod,
            suc_cod,
            emp_cod,
            usu_cod,
            apcier_cod,
            arq_cod,
            arq_obs,
            fun_cod,
            arq_fecha)
        values 
            ({$_POST['caj_cod']},
            {$_POST['suc_cod']},
            {$_POST['emp_cod']},
            {$_POST['usu_cod']},
            {$_POST['apcier_cod']},
            (select coalesce (max(arq_cod),0)+1 from arqueo_control),            
            upper('{$_POST['arq_obs']}'),
            {$_POST['fun_cod']},
            current_date);
    end
    $$;";
    pg_query($conexion, $sqlArqueo);
    $response = array(
        "msj" => "ok"
    );
    echo json_encode($response);

} else if ($_POST['caso'] == 'reapertura') {

    if (isset($_POST['apcier_cod'])) {

        $estado = $_POST['apcier_estado'];

        //ESCAPAMOS LOS DATOS CAPTURADOS
        $apcier_estado = pg_escape_string($conexion, $estado);

        $numApcier = ["codigo" => "{$_POST['apcier_cod']}",
                    "caja"=> "{$_POST['caj_cod']}",
                    "cajDescri"=> "{$_POST['caj_descri']}",
                    "estado" => "$apcier_estado"];
            
        $_SESSION['numApcier'] = $numApcier;

        $response = array(
            "mensaje" => "CAJA REABIERTA",
            "tipo" => "success"
        );

        echo json_encode($response);

    } else {
        
        $response = array(
            "mensaje" => "NO EXISTE CODIGO",
            "tipo" => "error"
        );

        echo json_encode($response);
    }
}


        
?>