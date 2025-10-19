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
    <title>CUPO DE SERVICIOS</title>
    <!--Se icluyen los estilos CSS ingresando desde la carpeta raíz hacia el importCSS-->
    <?php include "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/extension/importCSS.php"; ?>
</head>

<body class="theme">
    <?php include "{$_SERVER['DOCUMENT_ROOT']}/SysGym/opciones.php"; ?>

    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">

            <div class="col-lg-12 tblcab">
                    <!-- formulario de CUPO DE SERVICIOS cabecera -->
                    <div class="card">
                        <div class="header bg-indigo">
                            <h2>
                                FORMULARIO DE CUPO DE SERVICIOS<small>Registro de cupo de los servicios prestados</small>
                            </h2>
                        </div>
                        <div class="body">
                            <input type="hidden" id="transaccion" value="">
                            <input type="hidden" id="operacion_cab" value="0">
                            <div class="row clearfix">

                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="text" id="cup_cod" class="form-control" disabled>
                                            <label class="form-label">Cupo Nro.</label>
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
                                            <input type="date" id="cup_fecha" class="form-control" disabled>
                                            <label class="form-label">Fecha</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="hidden" id="itm_cod" value="0">
                                            <input type="hidden" id="tipitem_cod" value="0">
                                            <input type="text" class="form-control disabledno sinCarac" id="itm_descri" disabled onkeyup="getItems()">
                                            <label class="form-label">Servicio</label>
                                            <div id="listaItems" style="display: none;">
                                                <ul class="list-group" id="ulItems" style="height:60px; overflow:auto;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="text" id="cup_estado" class="form-control" disabled>
                                            <label class="form-label">Estado</label>
                                        </div>
                                    </div>
                                </div>

                            <!-- botones del formulario de ciudades -->
                            </div>
                            <div class="icon-and-text-button-demo">
                                <?php if ($botNuevo == true) { ?>
                                    <button type="button" class="btn bg-indigo waves-effect btnOperacion1" style="width:12.5%;" onclick="nuevo()">
                                        <i class="material-icons">create_new_folder</i>
                                        <span>NUEVO</span>
                                    </button>
                                <?php } ?>
                                <?php if ($botAnular == true) { ?>
                                    <button type="button" class="btn bg-indigo waves-effect btnOperacion1" style="width:12.5%;" onclick="anular()">
                                        <i class="material-icons">highlight_off</i>
                                        <span>ANULAR</span>
                                    </button>
                                <?php } ?>
                                <button type="button" class="btn bg-pink waves-effect btnOperacion2" style="width:12.5%; display:none;" onclick="controlVacio()">
                                    <i class="material-icons">save</i>
                                    <span>CONFIRMAR</span>
                                </button>
                                <button type="button" class="btn bg-red waves-effect btnOperacion2" style="width:12.5%; display:none;" onclick="cancelar()">
                                    <i class="material-icons">close</i>
                                    <span>CANCELAR</span>
                                </button>
                                <button type="button" class="btn bg-blue-grey waves-effect" style="width:12.5%;" onclick="salir()">
                                    <i class="material-icons">input</i>
                                    <span>SALIR</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- formulario de detalles de CUPO DE SERVICIOS -->
                <div class="col-lg-12 tbldet" style="display: none;">
                    <div class="card">
                        <div class="header bg-indigo">
                            <h2>
                                DETALLE DEL CUPO DE SERVICIO
                            </h2>
                        </div>
                        <div class="body">
                            <input type="hidden" id="operacion_det" value="0">
                            <div class="row clearfix">
                                
                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="time" id="cupdet_hora_ini" class="form-control disabledno2" disabled>
                                            <label class="form-label">Desde</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="time" id="cupdet_hora_fin" class="form-control disabledno2" disabled>
                                            <label class="form-label">Hasta</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="text" id="cupdet_cantidad" class="form-control disabledno2 soloNum" disabled>
                                            <label class="form-label">Cant. Disponible</label>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- botones del detalle de cupo de servicios -->
                            <div class="icon-and-text-button-demo">
                                <?php if ($botAgregar == true) { ?>
                                    <button type="button" class="btn bg-indigo waves-effect btnOperacion3" style="width:12.5%;" onclick="agregar()">
                                        <i class="material-icons">file_upload</i>
                                        <span>AGREGAR</span>
                                    </button>
                                <?php } ?>
                                <?php if ( $botEliminar == true) { ?>
                                    <button type="button" class="btn bg-indigo waves-effect btnOperacion3" style="width:12.5%;" onclick="eliminar()">
                                        <i class="material-icons">delete</i>
                                        <span>ELIMINAR</span>
                                    </button>
                                <?php } ?>
                                <button type="button" style="width:12.5%; display:none;" class="btn bg-pink waves-effect btnOperacion4" onclick="controlVacio2()">
                                    <i class="material-icons">archive</i>
                                    <span>GRABAR</span>
                                </button>
                                <button type="button" style="width:12.5%; display:none;" class="btn bg-red waves-effect btnOperacion4" onclick="cancelar()">
                                    <i class="material-icons">close</i>
                                    <span>CANCELAR</span>
                                </button>
                            </div>

                            <!-- grilla del detalle de cupo de servicios -->
                            <div class="table-responsive">
                                <table class="table table-hover table-borderer table-striped">
                                    <thead>
                                        <tr>
                                            <th>DESDE</th>
                                            <th>HASTA</th>
                                            <th>CANT. DISPONIBLE</th>
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
                    <!-- grilla del formulario CUPO DE SERVICIOS-->
                    <div class="card">
                        <div class="header bg-indigo">
                            <h2>
                                CUPO DE SERVICIOS REGISTRADOS <small>Listado de cupo de servicios</small>
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
                                            <th>SERVICIO</th>
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