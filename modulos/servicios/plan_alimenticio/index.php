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
    <title>PLAN ALIMENTICIO</title>
    <!--Se icluyen los estilos CSS ingresando desde la carpeta raíz hacia el importCSS-->
    <?php include "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/extension/importCSS.php"; ?>
</head>

<body class="theme">
    <?php include "{$_SERVER['DOCUMENT_ROOT']}/SysGym/opciones.php"; ?>

    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">

            <div class="col-lg-12 tblcab">
                    <!-- formulario de PLANES ALIMENTICIOS cabecera -->
                    <div class="card">
                        <div class="header bg-indigo">
                            <h2>
                                FORMULARIO DE PLANES ALIMENTICIOS<small>Registro del plan alimenticio del cliente</small>
                            </h2>
                        </div>
                        <div class="body">
                            <input type="hidden" id="operacion_cab" value="0">
                            <input type="hidden" id="transaccion" value="">
                            <div class="row clearfix">

                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="text" id="alim_cod" class="form-control" disabled>
                                            <label class="form-label">Plan Nro.</label>
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
                                            <input type="text" id="alim_fecha" class="form-control" disabled>
                                            <label class="form-label">Fecha</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="hidden" id="tiplan_cod" value="0">
                                            <input type="text" class="form-control disabledno" id="tiplan_descri" disabled onkeyup="getTipPlan()">
                                            <label class="form-label">TIpo de plan</label>
                                            <div id="listaTipPlan" style="display: none;">
                                                <ul class="list-group" id="ulTipPlan" style="height:60px; overflow:auto;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-5">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="hidden" id="per_nrodoc" value="0">
                                            <input type="hidden" id="cli_cod" value="0">
                                            <input type="hidden" id="per_email" value="0">
                                            <input type="text" class="form-control disabledno" id="cliente" disabled onkeyup="getClientes()">
                                            <label class="form-label">C.I. - Cliente</label>
                                            <div id="listaClientes" style="display: none;">
                                                <ul class="list-group" id="ulClientes" style="height:60px; overflow:auto;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="hidden" id="prpr_cod" value="0">
                                            <input type="text" class="form-control" id="ins_cod" disabled>
                                            <label class="form-label">Inscripción Nro.</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="hidden" id="fun_cod" value="0">
                                            <input type="text" class="form-control disabledno" id="funcionario" disabled onkeyup="getFuncionarios()">
                                            <label class="form-label">Nutricionista</label>
                                            <div id="listaFuncionarios" style="display: none;">
                                                <ul class="list-group" id="ulFuncionarios" style="height:60px; overflow:auto;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="text" id="alim_estado" class="form-control" disabled>
                                            <label class="form-label">Estado</label>
                                        </div>
                                    </div>
                                </div>

                            <!-- botones del formulario de ciudades -->
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
                                <button type="button" style="width:12.5%;" class="btn bg-blue waves-effect btnOperacion1" onclick="docPlanAlim()">
                                    <i class="material-icons">print</i>
                                    <span>IMPRIMIR</span>
                                </button>
                                <button type="button" style="width:12.5%;" class="btn bg-blue waves-effect btnOperacion1" onclick="enviarDoc()">
                                    <i class="material-icons">email</i>
                                    <span>ENVIAR</span>
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
                        <!-- formulario de detalles de PLAN ALIMENTICIO -->
                        <div class="header bg-indigo">
                            <h2>
                                DETALLE DEL PLAN
                            </h2>
                        </div>
                        <div class="body">
                            <input type="hidden" id="operacion_det" value="0">
                            <div class="row clearfix">

                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="hidden" id="dia_cod" value="0">
                                            <input type="text" class="form-control disabledno" id="dia_descri" disabled onkeyup="getDias()">
                                            <label class="form-label">Dia</label>
                                            <div id="listaDias" style="display: none;">
                                                <ul class="list-group" id="ulDias" style="height:60px; overflow:auto;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="hidden" id="hrcom_cod" value="0">
                                            <input type="text" class="form-control disabledno" id="hrcom_descri" disabled onkeyup="getHorario()">
                                            <label class="form-label">Horario</label>
                                            <div id="listaHorario" style="display: none;">
                                                <ul class="list-group" id="ulHorario" style="height:60px; overflow:auto;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="hidden" id="comi_cod" value="0">
                                            <input type="text" class="form-control disabledno" id="comi_descri" disabled onkeyup="getComidas()">
                                            <label class="form-label">Comida</label>
                                            <div id="listaComidas" style="display: none;">
                                                <ul class="list-group" id="ulComidas" style="height:60px; overflow:auto;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="text" id="alimdet_proteina" class="form-control disabledno" disabled>
                                            <label class="form-label">Proteínas</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="text" id="alimdet_calorias" class="form-control disabledno" disabled>
                                            <label class="form-label">Calorias</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="text" id="alimdet_carbohidratos" class="form-control disabledno" disabled>
                                            <label class="form-label">Carbohidratos</label>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- botones del detalle de inscripciones -->
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

                            <!-- grilla del detalle de PLAN ALIMENTICIO -->
                            <div class="table-responsive">
                                <table class="table table-hover table-borderer table-striped">
                                    <thead>
                                        <tr>
                                            <th>DIA</th>
                                            <th>HORARIO</th>
                                            <th>COMIDA</th>
                                            <th style="text-align:right;">PROTEINAS</th>
                                            <th style="text-align:right;">CALORIAS</th>
                                            <th style="text-align:right;">CARBOHIDRATOS</th>
                                        </tr>
                                    </thead>
                                    <tbody id="grilla_det">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 tbl">
                    <!-- grilla del formulario INSCRIPCIONES-->
                    <div class="card">
                        <div class="header bg-indigo">
                            <h2>
                            PLANES REGISTRADOS <small>Listado de planes alimenticios</small>
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
                                            <th>CLIENTE</th>
                                            <th>TIPO DE PLAN</th>
                                            <th>NUTRICIONISTA</th>
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

            </div>
        </div>
    </section>

    <!--Se icluyen los métodos JS ingresando desde la carpeta raíz hacia el importJS-->
    <?php include "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/extension/importJS.php"; ?>

    <!-- Nuestro método Js -->
    <script src="metodos.js"></script>
</body>

</html>