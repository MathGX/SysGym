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
where apu.usu_cod = $usu
order by p.permi_cod;";

$resultado = pg_query($conexion, $sql);
$datos = pg_fetch_all($resultado);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>timbrados</title>
    <!--Se icluyen los estilos CSS ingresando desde la carpeta raíz hacia el importCSS-->
    <?php include "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/extension/importCSS.php"; ?>
</head>

<body class="theme">
    <?php include "{$_SERVER['DOCUMENT_ROOT']}/SysGym/opciones.php"; ?>

    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">

                <div class="col-lg-12">
                    <!-- formulario de timbrados -->
                    <div class="card">
                        <div class="header bg-indigo">
                            <h2>
                                FORMULARIO DE TIMBRADOS<small>Registrar datos de documentos contables para cada sucursal</small>
                            </h2>
                        </div>
                        <div class="body">
                            <input type="hidden" id="operacion" value="0">
                            <div class="row clearfix">

                                <div class="col-md-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="text" id="tim_cod" class="form-control" disabled>
                                            <label class="form-label">Código</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="hidden" id="emp_cod" value="<?php echo $u['emp_cod'];?>">
                                            <input type="hidden" id="usu_cod" value="<?php echo $u['usu_cod'];?>">
                                            <input type="hidden" id="usu_login" value="<?php echo $u['usu_login'];?>">
                                            <input type="text" id="emp_razonsocial" class="form-control" value="<?php echo $u['emp_razonsocial']; ?> " disabled>
                                            <label class="form-label">Empresa</label> 
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="hidden" id="suc_cod" value="0">
                                            <input type="text" id="suc_descri" class="form-control disabledno soloTxt" disabled onkeyup="getSucursales()">
                                            <label class="form-label">Sucursal</label>
                                            <div id="listaSucursales" style="display:none;">
                                                <ul class="list-group" id="ulSucursales" style="height:60px; overflow:auto;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="hidden" id="caj_cod" value= "0">
                                            <input type="text" id="caj_descri" class="form-control disabledno sinCarac" disabled onkeyup="getCajas()">
                                            <label class="form-label">Caja</label>
                                            <div id="listaCajas" style="display: none;">
                                                <ul class="list-group" id="ulCajas" style="height:60px; overflow:auto;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="hidden" id="tipcomp_cod" value= "0">
                                            <input type="text" id="tipcomp_descri" class="form-control disabledno soloTxt" disabled onkeyup="getTipComp()">
                                            <label class="form-label">Tipo de comprobante</label>
                                            <div id="listaTipComp" style="display: none;">
                                                <ul class="list-group" id="ulTipComp" style="height:60px; overflow:auto;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="text" id="tim_nro" class="form-control disabledno soloNum" disabled>
                                            <label class="form-label">Timbrado</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="date" id="tim_fec_ini" class="form-control disabledno" disabled>
                                            <label class="form-label">Emisión</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="date" id="tim_fec_venc" class="form-control disabledno" disabled>
                                            <label class="form-label">Vencimiento</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="text" id="tim_com_nro" class="form-control disabledno soloNum" disabled>
                                            <label class="form-label">Comprobante Nro.</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="text" id="tim_com_nro_ini" class="form-control disabledno soloNum" disabled>
                                            <label class="form-label">Inicio</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="text" id="tim_com_nro_lim" class="form-control disabledno soloNum" disabled>
                                            <label class="form-label">Límite</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="text" id="tim_estado" class="form-control" disabled>
                                            <label class="form-label">Estado</label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            
                            <!-- botones del formulario de timbrados -->
                            <div class="icon-and-text-button-demo">
                                <?php foreach ($datos as $key => $boton) { ?>
                                    <?php if (($boton['permi_descri'] == 'AGREGAR') && ($boton['asigusu_estado'] == 'ACTIVO')) { ?>
                                        <button type="button" class="btn bg-indigo waves-effect btnOperacion1" onclick="agregar()">
                                            <i class="material-icons">file_upload</i>
                                            <span>AGREGAR</span>
                                        </button>
                                    <?php } ?>  
                                    <?php if (($boton['permi_descri'] == 'MODIFICAR') && ($boton['asigusu_estado'] == 'ACTIVO')) { ?>
                                        <button type="button" class="btn bg-indigo waves-effect btnOperacion1" onclick="modificar()">
                                            <i class="material-icons">edit</i>
                                            <span>MODIFICAR</span>
                                        </button>
                                    <?php } ?>
                                    <?php if (($boton['permi_descri'] == 'BORRAR') && ($boton['asigusu_estado'] == 'ACTIVO')) { ?>
                                        <button type="button" class="btn bg-indigo waves-effect btnOperacion1" onclick="eliminar()">
                                            <i class="material-icons">delete_sweep</i>
                                            <span>BORRAR</span>
                                        </button>
                                    <?php } 
                                } ?>
                                <button type="button" style=display:none class="btn bg-pink waves-effect btnOperacion2" onclick="controlVacio()">
                                    <i class="material-icons">archive</i>
                                    <span>CONFIRMAR</span>
                                </button>
                                <button type="button" style=display:none class="btn bg-red waves-effect btnOperacion2" onclick="cancelar()">
                                    <i class="material-icons">close</i>
                                    <span>CANCELAR</span>
                                </button>
                                <button type="button" class="btn bg-blue-grey waves-effect" onclick="salir()">
                                    <i class="material-icons">input</i>
                                    <span>SALIR</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 tbl" style="display: block;">
                    <!-- grilla del formulario timbrados -->
                    <div class="card">
                        <div class="header bg-indigo">
                            <h2>
                                LISTADO DE TIMBRADOS<small>Listado de últimos documentos fiscales emitidos por caja y sucursal</small>
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover table-borderer table-striped dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th>CODIGO</th>
                                            <th>SUCURSAL</th>
                                            <th>CAJA</th>
                                            <th>TIPO DE COMPROBANTE</th>
                                            <th>TIMBRADO</th>
                                            <th>VIGENCIA DEL TIMBRADO</th>
                                            <th>COMPROBANTE</th>
                                            <th>RANGO DE COMPROBANTE</th>
                                            <th>ESTADO</th>
                                        </tr>
                                    </thead>
                                    <tbody id="grilla_datos">

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