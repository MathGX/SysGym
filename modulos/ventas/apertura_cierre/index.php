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

$botApertura = false;
$botCierre = false;

foreach ($datos as $btn){
    if ($btn["permi_descri"] == "APERTURA" && $btn["asigusu_estado"] == "ACTIVO") {
        $botApertura = true;
    }
    if ($btn["permi_descri"] == "CIERRE" && $btn["asigusu_estado"]) {
        $botCierre = true;
    }
}

$u = $_SESSION['usuarios'];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>APERTURA Y CIERRE DE CAJA</title>
    <!--Se icluyen los estilos CSS ingresando desde la carpeta raíz hacia el importCSS-->
    <?php include "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/extension/importCSS.php"; ?>
</head>

<body class="theme">
    <?php include "{$_SERVER['DOCUMENT_ROOT']}/SysGym/opciones.php"; ?>

    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">

                <div class="col-lg-12" style="display: block;">
                    <div class="card reapertura" style="display: none;">
                        <div class="header bg-indigo">
                            <h2>
                                REAPERTURA DE CAJA<small>Búsqueda de cajas abiertas</small>
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">

                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="hidden" id="usu_cod1" value="<?php echo $u['usu_cod'];?>">
                                            <input type="text" id="usu_login1" class="form-control" value="<?php echo $u['usu_login'];?>" disabled>
                                            <label class="form-label">Usuario - Reapertura</label> 
                                            <div id="listaAperturas" style="display: none;">
                                                <ul class="list-group" id="ulAperturas" style="height:60px; overflow:auto;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="icon-and-text-button-demo">
                                    <button type="button" style="width:12.5%;" class="btn bg-pink waves-effect btnOperacion2" onclick="controlVacioReabrir()">
                                        <i class="material-icons">sync</i>
                                        <span>REABRIR</span>
                                    </button>
                                    <button type="button" style="width:12.5%;" class="btn bg-pink waves-effect" onclick="cancelar()">
                                        <i class="material-icons">close</i>
                                        <span>CANCELAR</span>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- formulario de pedido apertura y cierre -->
                    <div class="card">
                        <div class="header bg-indigo">
                            <h2>
                                APERTURA Y CIERRE<small>Movimientos de caja</small>
                            </h2>
                        </div>
                        <div class="body cab">
                            <input type="hidden" id="operacion" value="0">
                            <div class="row clearfix">

                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="text" id="apcier_cod" class="form-control" value= "<?php echo $apertura['apcier_cod']?>" disabled>
                                            <label class="form-label">Apertura Nro.</label>
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
                                        <div class="form-line focus focused">
                                            <input type="hidden" id="caj_cod" value= "<?php echo $apertura['caj_cod']?>">
                                            <input type="text" class="form-control disabledno sinCarac" id="caj_descri" value= "<?php echo $apertura['caj_descri'] ?? null ?>" onkeyup="getCajas()" disabled>
                                            <label class="form-label">Caja</label>
                                            <div id="listaCajas" style="display: none;">
                                                <ul class="list-group" id="ulCajas" style="height:60px; overflow:auto;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="text" id="apcier_estado" value= "<?php echo $apertura['apcier_estado']?>" class="form-control" disabled>
                                            <label class="form-label">Estado</label>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class=" col-lg-1 icon-button-demo btnReabrir">
                                <button type="button" class="btn bg-purple btn-circle waves-effect waves-circle waves-float" onclick="reapertura()">
                                    <i class="material-icons">restore_page</i>
                                </button>
                            </div>
                            
                            <div class="icon-and-text-button-demo">
                                <?php if ($botApertura == true) { ?>
                                    <button type="button" style="width:12.5%;" id="btnApertura" class="btn bg-indigo waves-effect btnOperacion1" onclick="abrir()">
                                        <i class="material-icons">create_new_folder</i>
                                        <span>APERTURA</span>
                                    </button>
                                <?php } ?>
                                <?php if ($botCierre == true) { ?>
                                    <button type="button" style="width:12.5%;" id="btnCierre" class="btn bg-indigo waves-effect btnOperacion1" onclick="cerrar()">
                                        <i class="material-icons">highlight_off</i>
                                        <span>CIERRE</span>
                                    </button>
                                <?php } ?>
                                <button type="button" style="width:12.5%;" class="btn bg-blue-grey waves-effect" onclick="salir()">
                                    <i class="material-icons">input</i>
                                    <span>SALIR</span>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-12 aper" style="display: none;">
                    <div class="card">
                        <div class="header bg-indigo">
                            <h2>
                                APERTURA DE CAJA
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">

                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="datetime-local" id="apcier_fechahora_aper" class= "form-control" disabled>
                                            <label class="form-label">Fecha y hora de apertura</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="number" id="apcier_monto_aper" class= "form-control disabledno soloNum">
                                            <label class="form-label">Monto de apertura</label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- botones del formulario de ciudades -->
                            <div class="icon-and-text-button-demo">
                                <button type="button" style="display:none;" class="btn bg-pink waves-effect btnOperacion2" onclick="controlVacio()">
                                    <i class="material-icons">save</i>
                                    <span>CONFIRMAR</span>
                                </button>
                                <button type="button" style="display:none;" class="btn bg-pink waves-effect btnOperacion2" onclick="cancelar()">
                                    <i class="material-icons">close</i>
                                    <span>CANCELAR</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 cier" style="display: none;">
                    <div class="card">
                        <div class="header bg-indigo">
                            <h2>
                                CIERRE DE CAJA
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">

                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="datetime-local" id="apcier_fechahora_cierre" class= "form-control " disabled>
                                            <label class="form-label">Fecha y hora de cierre</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="number" id="apcier_monto_cierre" class= "form-control soloNum">
                                            <label class="form-label">Monto al cierre</label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- botones del formulario de ciudades -->
                            <div class="icon-and-text-button-demo">
                                <button type="button" style="width:12.5%;" style="display:none;" class="btn bg-pink waves-effect btnOperacion2" onclick="controlVacioCierre()">
                                    <i class="material-icons">save</i>
                                    <span>CONFIRMAR</span>
                                </button>
                                <button type="button" style="width:12.5%;" style="display:none;" class="btn bg-pink waves-effect btnOperacion2" onclick="cancelar()">
                                    <i class="material-icons">close</i>
                                    <span>CANCELAR</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 arqueo" style="display: none">
                    <div class="card">
                        <div class="header bg-indigo">
                            <h2>
                                ARQUEO DE CONTROL <small>Control del movimiento de caja</small>
                            </h2>
                        </div>

                        <div class="header" style="border-bottom: 1px black solid">
                            <div class="col-sm-2">
                                <div class="form-group form-float">
                                    <input type="checkbox" name="efectivo" id="efectivo">
                                    <label for="efectivo">Efectivo</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group form-float">
                                    <input type="checkbox" name="cheque" id="cheque">
                                    <label for="cheque">Cheque</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group form-float">
                                    <input type="checkbox" name="tarjeta" id="tarjeta">
                                    <label for="tarjeta">Tarjeta</label>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group form-float">
                                    <input type="checkbox" name="todos_medios" id="todos_medios">
                                    <label for="todos_medios">Todos los medios</label>
                                </div>
                            </div>
                            <br>

                        </div>

                        <div class="body">
                            <div class="row clearfix">

                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="hidden" id="per_cod" value="0">
                                            <input type="text" class="form-control disabledno ctrlVacioArq soloNum" id="per_nrodoc" onkeyup="getFuncionarios()">
                                            <label class="form-label">C.I.</label>
                                            <div id="listaFuncionarios" style="display: none;">
                                                <ul class="list-group" id="ulFuncionarios" style="height:60px; overflow:auto;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="hidden" id="fun_cod" value="0">
                                            <input type="text" class="form-control ctrlVacioArq" id="funcionarios" disabled>
                                            <label class="form-label">Funcionario</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-5">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="text" class="form-control disabledno ctrlVacioArq sinCarac" id="arq_obs">
                                            <label class="form-label">Observacion</label>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="icon-and-text-button-demo">
                                <button type="button" style="width:12.5%;" class="btn bg-indigo waves-effect" id="reporte" onclick="controlVacioArqueo()">
                                    <i class="material-icons">content_paste</i>
                                    <span>Generar Arqueo</span>
                                </button>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!--Se icluyen los métodos JS ingresando desde la carpeta raíz hacia el importJS-->
    <?php include "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/extension/importJS.php"; ?>

    <!-- Nuestro método Js -->
    <script src="metodos.js"></script>
</body>

</html>