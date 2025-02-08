<?php
//iniciamos variables de sesión
session_start();

$usu = $_SESSION['usuarios']['usu_cod'];

require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$sql = "select p.permi_descri,
apu.asigusu_estado 
from asignacion_permiso_usuarios apu
join permisos p on p.permi_cod = apu.permi_cod 
where apu.usu_cod = $usu;";

$resultado = pg_query($conexion, $sql);
$datos = pg_fetch_all($resultado);

$botNuevo = false;
$botAnular = false;
$botAgregar = false;
$botEliminar = false;

foreach ($datos as $btn){
    if ($btn["permi_descri"] == "NUEVO" && $btn["asigusu_estado"] == "ACTIVO") {
        $botNuevo = true;
    }
    if ($btn["permi_descri"] == "ANULAR" && $btn["asigusu_estado"] == "ACTIVO") {
        $botAnular = true;
    }
    if ($btn["permi_descri"] == "AGREGAR" && $btn["asigusu_estado"] == "ACTIVO") {
        $botAgregar = true;
    }
    if ($btn["permi_descri"] == "ELIMINAR" && $btn["asigusu_estado"] == "ACTIVO") {
        $botEliminar = true;
    }
}

$u = $_SESSION['usuarios'];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>COBROS</title>
    <!--Se icluyen los estilos CSS ingresando desde la carpeta raíz hacia el importCSS-->
    <?php include "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/extension/importCSS.php"; ?>
</head>

<body class="theme">
    <?php include "{$_SERVER['DOCUMENT_ROOT']}/SysGym/opciones.php"; ?>

    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
            <?php if (($apertura['apcier_estado'] == 'ABIERTA') || ($u['perf_descri'] == 'ADMINISTRADOR')) { ?>

                <div class="col-lg-12">
                    <!-- formulario de COBROS cabecera -->
                    <div class="card">
                        <div class="header bg-indigo">
                            <h2>
                                FORMULARIO DE COBROS <small>Registro de cobros realizadas</small>
                            </h2>
                        </div>
                        <div class="body">
                            <input type="hidden" id="operacion_cab" value="0">
                            <div class="row clearfix">

                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="text" id="cobr_cod" class="form-control" disabled>
                                            <label class="form-label">Cobro Nro.</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="hidden" id="emp_cod" value="<?php echo $u['emp_cod']; ?> ">
                                            <input type="text" id="emp_razonsocial" class="form-control" value="<?php echo $u['emp_razonsocial']; ?> " disabled>
                                            <label class="form-label">Empresa</label> 
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="hidden" id="suc_cod" value="<?php echo $u['suc_cod']; ?> ">
                                            <input type="text" id="suc_descri" class="form-control" value="<?php echo $u['suc_descri']; ?> " disabled>
                                            <label class="form-label">Sucursal</label> 
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="hidden" id="usu_cod" value="<?php echo $u['usu_cod']; ?> ">
                                            <input type="text" id="usu_login" class="form-control" value="<?php echo $u['usu_login']; ?> " disabled>
                                            <label class="form-label">Usuario</label> 
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="text" id="cobr_fecha" class="form-control" disabled>
                                            <label class="form-label">Fecha</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="text" id="apcier_cod" class="form-control" value= "<?php echo $apertura['apcier_cod']?>" disabled>
                                            <input type="hidden" id="caj_cod" value= "<?php echo $apertura['caj_cod']?>">
                                            <input type="text" class="form-control" id="caj_descri" value= "<?php echo $apertura['caj_descri']?>" disabled>
                                            <label class="form-label">Caja</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="text" id="cobr_estado" class="form-control" disabled>
                                            <label class="form-label">Estado</label>
                                        </div>
                                    </div>
                                </div>

                            <!-- botones del formulario de cobros -->
                            </div>
                            <div class="icon-and-text-button-demo">
                                <?php if ($botNuevo == true) { ?>
                                    <button type="button" style="width:12.5%;" class="btn bg-indigo waves-effect btnOperacion1" onclick="nuevo()">
                                        <i class="material-icons">create_new_folder</i>
                                        <span>NUEVO</span>
                                    </button>
                                <?php } ?>
                                <?php if ($botAnular == true) { ?>
                                    <button type="button" style="width:12.5%;" class="btn bg-indigo waves-effect btnOperacion1" onclick="anular()">
                                        <i class="material-icons">highlight_off</i>
                                        <span>ANULAR</span>
                                    </button>
                                <?php } ?>
                                <button type="button" style="width:12.5%;" class="btn bg-blue waves-effect btnOperacion1" onclick="reporteCobro()">
                                    <i class="material-icons">print</i>
                                    <span>IMPRIMIR</span>
                                </button>
                                <button type="button" style="display:none;" class="btn bg-pink waves-effect btnOperacion2" onclick="controlVacio()">
                                    <i class="material-icons">save</i>
                                    <span>CONFIRMAR</span>
                                </button>
                                <button type="button" style="display:none;" class="btn bg-pink waves-effect btnOperacion2" onclick="cancelar()">
                                    <i class="material-icons">close</i>
                                    <span>CANCELAR</span>
                                </button>
                                <button type="button" style="width:12.5%;" class="btn bg-blue-grey waves-effect" onclick="salir()">
                                    <i class="material-icons">input</i>
                                    <span>SALIR</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 tbldet" style="display: none;">
                    <div class="card">
                    <!-- formulario de detalles de COBROS -->
                        <div class="header bg-indigo">
                            <h2>
                                DETALLE DEL COBRO
                            </h2>
                        </div>
                        
                        <div class="header">
                            <div class="row clearfix">

                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="text" class="form-control disabledno" id="per_nrodoc" disabled onkeyup="getVentas()">
                                            <label class="form-label">C.I.</label>
                                            <div id="listaVentas" style="display: none;">
                                                <ul class="list-group" id="ulVentas" style="height:60px; overflow:auto;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="text" class="form-control" id="cuencob_saldo" disabled>
                                            <label class="form-label">Saldo pendiente</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="text" id="cuencob_cuotas" class="form-control" disabled>
                                            <label class="form-label">Cuotas Totales</label>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                        <div class="body">

                            <input type="hidden" id="operacion_det" value="0">
                            <input type="hidden" id="cuencob_montotal" value="0">
                            <div class="row clearfix">

                                <div class="col-sm-2" style= "display: none">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="text" id="cobrdet_cod" class="form-control" disabled>
                                            <label class="form-label">Cobro detalle Nro.</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="text" id="ven_cod" class="form-control" disabled>
                                            <label class="form-label">Venta Nro.</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="text" id="ven_nrofac" class="form-control" disabled>
                                            <label class="form-label">Factura nro.</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="text" class="form-control" id="cliente" disabled>
                                            <label class="form-label">Cliente</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="text" id="cobrdet_nrocuota" class="form-control">
                                            <label class="form-label">Cuota nro.</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="number" id="ven_montocuota" class="form-control" disabled>
                                            <label class="form-label">Monto cuota</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="text" id="ven_intefecha" class="form-control" disabled>
                                            <label class="form-label">Intervalo de pago</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="hidden" id="forcob_cod" value="0">
                                            <input type="text" class="form-control disabledno" id="forcob_descri" disabled onkeyup="getForcob()">
                                            <label class="form-label">Forma de cobro</label>
                                            <div id="listaForcob" style="display: none;">
                                                <ul class="list-group" id="ulForcob" style="height:60px; overflow:auto"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3 abono">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="number" id="cobrdet_monto" class="form-control disabledno" disabled>
                                            <label class="form-label">Monto abonado</label>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- botones del detalle de COBROS -->
                            <div class="icon-and-text-button-demo">
                                <?php if ($botAgregar == true) { ?>
                                    <button type="button" style="width:12.5%;" class="btn bg-indigo waves-effect btnOperacion3" onclick="agregar()">
                                        <i class="material-icons">file_upload</i>
                                        <span>AGREGAR</span>
                                    </button>
                                <?php } ?>
                                <?php if ( $botEliminar == true) { ?>
                                    <button type="button" style="width:12.5%;" class="btn bg-indigo waves-effect btnOperacion3" onclick="eliminar()">
                                        <i class="material-icons">delete</i>
                                        <span>ELIMINAR</span>
                                    </button>
                                <?php } ?>
                                <button type="button" style="display:none;" class="btn bg-pink waves-effect btnOperacion4" onclick="controlVacio2()">
                                    <i class="material-icons">archive</i>
                                    <span>GRABAR</span>
                                </button>
                                <button type="button" style="display:none;" class="btn bg-pink waves-effect btnOperacion4" onclick="cancelar()">
                                    <i class="material-icons">close</i>
                                    <span>CANCELAR</span>
                                </button>
                            </div>

                        <!-- grilla del detalle de COBROS -->
                            <div class="table-responsive grilla_det1">
                                <table class="table table-hover table-borderer table-striped">
                                    <thead>
                                        <tr>
                                            <th>VENTA NRO.</th>
                                            <th>FACTURA</th>
                                            <th>CLIENTE</th>
                                            <th>CUOTA</th>
                                            <th>MONTO</th>
                                            <th>FORMA DE COBRO</th> 
                                        </tr>
                                    </thead>
                                    <tbody id="grilla_det">

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-6 tbltarj" style="display: none;">
                    <div class="card">
                    <!-- formulario de COBRO TARJETA -->
                        <div class="header bg-indigo">
                            <h2>
                                COBRO EN TARJETA
                            </h2>
                        </div>
                        <div class="body">
                            <input type="hidden" id="operacion_det" value="0">
                            <div class="row clearfix">

                            <div class="col-sm-2" style="display: none">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="text" id="cobrtarj_cod" class="form-control" disabled>
                                            <label class="form-label">Cobro Tarjeta Nro.</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="text" class="form-control disabledno" id="cobrtarj_num" disabled>
                                            <label class="form-label">Tarjeta Nro.</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="number" id="cobrtarj_monto" class="form-control disabledno" disabled>
                                            <label class="form-label">Monto Tarjeta</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="text" class="form-control disabledno" id="cobrtarj_tiptarj" disabled onclick="getTipTarj()">
                                            <label class="form-label">Tipo Tarj.</label>
                                            <div id="listaTipTarj" style="display: none;">
                                                <ul class="list-group" id="ulTipTarj" style="height:60px; overflow:auto"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-5">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="hidden" id="entahd_cod" value="0">
                                            <input type="hidden" id="ent_cod_tarj" value="0">
                                            <input type="text" class="form-control disabledno" id="ent_razonsocial_tarj" disabled onkeyup="getEntAd()">
                                            <label class="form-label">Entidad Tarj</label>
                                            <div id="listaEntAd" style="display: none;">
                                                <ul class="list-group" id="ulEntAd" style="height:60px; overflow:auto"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">                                            
                                            <input type="hidden" id="martarj_cod" value="0">
                                            <input type="text" id="martarj_descri" class="form-control disabledno" disabled>
                                            <label class="form-label">Marca de tarj.</label>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-6 tblcheq" style="display: none;">
                    <div class="card">
                    <!-- formulario de COBRO CHEQUE-->
                        <div class="header bg-indigo">
                            <h2>
                                COBRO EN CHEQUE
                            </h2>
                        </div>
                        <div class="body">
                            <input type="hidden" id="operacion_det" value="0">
                            <div class="row clearfix">

                                <div class="col-sm-2" style="display: none">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="text" id="cobrcheq_cod" class="form-control" disabled>
                                            <label class="form-label">Cobro Cheque Nro.</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="text" class="form-control disabledno" id="cobrcheq_num" disabled>
                                            <label class="form-label">Cheque Nro.</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="number" id="cobrcheq_monto" class="form-control disabledno" disabled>
                                            <label class="form-label">Monto Cheque</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="text" class="form-control disabledno" id="cobrcheq_tipcheq" disabled onclick="getTipCheq()">
                                            <label class="form-label">Tipo Cheque</label>
                                            <div id="listaTipCheq" style="display: none;">
                                                <ul class="list-group" id="ulTipCheq" style="height:60px; overflow:auto"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-5">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="hidden" id="ent_cod" value="0">
                                            <input type="text" class="form-control disabledno" id="ent_razonsocial" disabled onkeyup="getEntidad()">
                                            <label class="form-label">Entidad</label>
                                            <div id="listaEntidad" style="display: none;">
                                                <ul class="list-group" id="ulEntidad" style="height:60px; overflow:auto"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="date" id="cobrcheq_fechaven" class="form-control disabledno" disabled>
                                            <label class="form-label">Vencimiento</label>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-12 tblgrcab">
                    <!-- grilla del formulario DE COBROS-->
                    <div class="card">
                        <div class="header bg-indigo">
                            <h2>
                                COBROS REGISTRADOS <small>Listado de cobros realizadas</small>
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover table-borderer table-striped dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th>NRO.</th>
                                            <th>FECHA</th>
                                            <th>USUARIO</th>
                                            <th>SUCURSAL</th>
                                            <th>CAJA</th>
                                            <th>ESTADO</th>
                                        </tr>
                                    </thead>
                                    <tbody id="grilla_cab">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="col-sm-12 bg-pink" style=" width:100%; border-radius:10px; padding:10px;" >
                        <div class="form-line focus">
                            <div style="text-align: center;"><h3>LA CAJA SE ENCUNETRA CERRADA, DEBE ABRIRSE PRIMERAMENTE</h3></div>
                        </div>
                </div>
            <?php }?>

            </div>
        </div>
    </section>

    <!--Se icluyen los métodos JS ingresando desde la carpeta raíz hacia el importJS-->
    <?php include "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/extension/importJS.php"; ?>

    <!-- Nuestro método Js -->
    <script src="metodos.js"></script>
</body>

</html>