<?php
//iniciamos variables de sesión
session_start();

$u = $_SESSION['usuarios'];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>INFORMES DE SERVICIOS</title>
    <!--Se icluyen los estilos CSS ingresando desde la carpeta raíz hacia el importCSS-->
    <?php include "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/extension/importCSS.php"; ?>
</head>

<body class="theme">
    <?php include "{$_SERVER['DOCUMENT_ROOT']}/SysGym/opciones.php"; ?>

    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
                
                <div class="col-lg-12">
                    <!-- formulario de modulos -->
                    <div class="card">
                        <div class="header bg-indigo">
                            <h2>
                                INFORME DE SERVICIOS<small>Reportes para el área de servicios</small>
                            </h2>
                        </div>
                        <div class="body">
                            <input type="hidden" id="operacion" value="0">
                            <div class="row clearfix">

                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div class="form-line mod">
                                            <input type="text" id="tabla" class="form-control" onclick="getTabla()">
                                            <label class="form-label">Reporte</label>
                                            <div id="listaTabla" style="display: none;">
                                                <ul class="list-group" id="ulTabla"  style="height:60px; overflow:auto;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 row clearfix evol">

                                    <h5 class="col-sm-12">Filtros del reporte de evolución del atleta</h5>

                                    <div class="col-sm-4">
                                        <div class="form-group form-float">
                                            <div class="form-line focus">
                                                <input type="hidden" id="per_nrodoc" value="0">
                                                <input type="hidden" id="cli_cod" value="0">
                                                <input type="text" class="form-control disabledno" id="cliente" disabled onkeyup="getClientes()">
                                                <label class="form-label">C.I. - Cliente (Obligatorio)</label>
                                                <div id="listaClientes" style="display: none;">
                                                    <ul class="list-group" id="ulClientes" style="height:60px; overflow:auto;"></ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group form-float">
                                            <div class="form-line foc">
                                                <input type="hidden" id="param_cod" value="0">
                                                <input type="text" class="form-control disabledno" id="param_descri" disabled onkeyup="getParametro()">
                                                <label class="form-label">Parámetro (Opcional)</label>
                                                <div id="listaParametro" style="display: none;">
                                                    <ul class="list-group" id="ulParametro" style="height:60px; overflow:auto;"></ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group form-float">
                                            <div class="form-line focus focused">
                                                <input type="date" id="ordcom_fecha" class="form-control" disabled>
                                                <label class="form-label">Desde (Opcional)</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group form-float">
                                            <div class="form-line focus focused">
                                                <input type="date" id="ordcom_fecha" class="form-control" disabled>
                                                <label class="form-label">Hasta (Opcional)</label>
                                            </div>
                                        </div>
                                    </div>
                                
                                </div>

                                <div class="col-sm-12 row clearfix asis_cup">

                                    <h5 class="col-sm-12">Filtros del reporte de asistencias y cupos</h5>

                                    <div class="col-sm-2">
                                        <div class="form-group form-float">
                                            <div class="form-line focus focused">
                                                <input type="date" id="ordcom_fecha" class="form-control" disabled>
                                                <label class="form-label">Fecha (Obligatorio)</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group form-float">
                                            <div class="form-line focus focused">
                                                <input type="time" id="ordcom_fecha" class="form-control" disabled>
                                                <label class="form-label">Desde (Opcional)</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group form-float">
                                            <div class="form-line focus focused">
                                                <input type="time" id="ordcom_fecha" class="form-control" disabled>
                                                <label class="form-label">Hasta (Opcional)</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-5">
                                        <div class="form-group form-float">
                                            <div class="form-line foc">
                                                <input type="hidden" id="itm_cod" value="0">
                                                <input type="hidden" id="tipitem_cod" value="0">
                                                <input type="hidden" id="tipimp_cod" value="0">
                                                <input type="text" class="form-control disabledno" id="itm_descri" disabled onkeyup="getItems()">
                                                <label class="form-label">Servicio (Opcional)</label>
                                                <div id="listaItems" style="display: none;">
                                                    <ul class="list-group" id="ulItems" style="height:60px; overflow:auto;"></ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-sm-12 row clearfix recla">

                                    <h5 class="col-sm-12">Filtros del reporte de reclamos</h5>

                                    <div class="col-sm-2">
                                        <div class="form-group form-float">
                                            <div class="form-line focus focused">
                                                <input type="date" id="ordcom_fecha" class="form-control" disabled>
                                                <label class="form-label">Fecha (Opcional)</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group form-float">
                                            <div class="form-line focus">
                                                <input type="text" id="ordcom_fecha" class="form-control" disabled>
                                                <label class="form-label">Causa (Opcional)</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group form-float">
                                            <div class="form-line focus">
                                                <input type="hidden" id="tipcomp_cod" value="0">
                                                <input type="text" class="form-control disabledno" id="tipcomp_descri" disabled onkeyup="getNota()">
                                                <label class="form-label">Estado</label>
                                                <div id="listaNota" style="display: none;">
                                                    <ul class="list-group" id="ulNota" style="height:60px; overflow:scroll;"></ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-sm-2" style="display: none;">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="hidden" id="suc_cod" value="<?php echo $u['suc_cod']; ?> ">
                                            <input type="text" id="suc_descri" class="form-control" value="<?php echo $u['suc_descri']; ?> " disabled>
                                            <label class="form-label">Sucursal</label> 
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4" style="display:none">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="hidden" id="emp_cod" value="<?php echo $u['emp_cod']; ?> ">
                                            <input type="text" id="emp_razonsocial" class="form-control" value="<?php echo $u['emp_razonsocial']; ?> " disabled>
                                            <label class="form-label">Empresa</label> 
                                        </div>
                                    </div>
                                </div>

                            </div>
                            
                            <!-- botones del formulario de modulos -->
                            <div class="icon-and-text-button-demo">
                                <button type="button" class="btn bg-pink waves-effect " onclick="controlVacio()">
                                    <i class="material-icons">file_download</i>
                                    <span>GENERAR</span>
                                </button>
                                <button type="button" class="btn bg-pink waves-effect " onclick="cancelar()">
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

            </div>

        </div>
    </section>

    <!--Se icluyen los métodos JS ingresando desde la carpeta raíz hacia el importJS-->
    <?php include "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/extension/importJS.php"; ?>

    <!-- Nuestro método Js -->
    <script src="metodos.js"></script>
</body>

</html>