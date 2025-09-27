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
    <title>INFORMES DE VENTAS</title>
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
                                INFORME DE VENTAS<small>Reportes para el área de ventas</small>
                            </h2>
                        </div>
                        <div class="body">
                            <input type="hidden" id="operacion" value="0">
                            <div class="row clearfix">

                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div class="form-line mod">
                                            <input type="text" id="informe" class="form-control obligatorio" onkeyup="getInforme()">
                                            <label class="form-label">Reporte</label>
                                            <div id="listaInforme" style="display: none;">
                                                <ul class="list-group" id="ulInforme"  style="height:40px; overflow:auto;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 caja_rec" style="display:none;">
                                    <spam><b>Filtros del reporte de caja y recaudaciones</b></spam>
                                </div>

                                <div class="col-sm-12 libro_venta" style="display:none;">
                                    <spam><b>Filtros del reporte de libro de ventas</b></spam>
                                </div>

                                <div class="col-sm-12 cuentas_cobrar" style="display:none;">
                                    <spam><b>Filtros del reporte de cuentas a cobrar</b></spam>
                                </div>

                                    <div class="col-sm-2 caja_rec" style="display:none;">
                                        <div class="form-group form-float">
                                            <div class="form-line focus">
                                                <input type="hidden" id="caj_cod" value= "<?php echo $apertura['caj_cod']?>">
                                                <input type="text" class="form-control obligatorio" id="caj_descri" value= "<?php echo $apertura['caj_descri']?>" onkeyup="getCajas()" cuencob>
                                                <label class="form-label">Caja (Opcional)</label>
                                                <div id="listaCajas" style="display: none;">
                                                    <ul class="list-group" id="ulCajas" style="height:60px; overflow:auto;"></ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <div class="col-sm-2 libro_venta cuentas_cobrar caja_rec" style="display:none;">
                                    <div class="form-group form-float">
                                        <div class="form-line focus focused">
                                            <input type="date" id="desde" class="form-control obligatorio">
                                            <label class="form-label">Desde (Obligatorio)</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2 libro_venta cuentas_cobrar caja_rec" style="display:none;">
                                    <div class="form-group form-float">
                                        <div class="form-line focus focused">
                                            <input type="date" id="hasta" class="form-control obligatorio">
                                            <label class="form-label">Hasta (Obligatorio)</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group form-float libro_venta cuentas_cobrar" style="display:none;">
                                        <div class="form-line focus">
                                            <input type="hidden" id="per_nrodoc" value="0">
                                            <input type="hidden" id="cli_cod" value="0">
                                            <input type="text" class="form-control" id="cliente" cuencob onkeyup="getPedido()">
                                            <label class="form-label">Doc. - Cliente (Opcional)</label>
                                            <div id="listaPedido" style="display: none;">
                                                <ul class="list-group" id="ulPedido" style="height:60px; overflow:auto;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3 libro_venta" style="display:none;">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="hidden" id="tipcomp_cod" value="0">
                                            <input type="text" class="form-control" id="tipcomp_descri" onkeyup="getNota()">
                                            <label class="form-label">Tipo de Comprobante (Opcional)</label>
                                            <div id="listaNota" style="display: none;">
                                                <ul class="list-group" id="ulNota" style="height:60px; overflow:scroll;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3 cuentas_cobrar" style="display:none;">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="text" class="form-control" id="cuencob_estado" onkeyup="getNota()">
                                            <label class="form-label">Estado (Opcional)</label>
                                            <div id="listaNota" style="display: none;">
                                                <ul class="list-group" id="ulNota" style="height:60px; overflow:scroll;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2" style="display: none;">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="hidden" id="suc_cod" value="<?php echo $u['suc_cod']; ?> ">
                                            <input type="text" id="suc_descri" class="form-control" value="<?php echo $u['suc_descri']; ?> ">
                                            <label class="form-label">Sucursal</label> 
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4" style="display:none">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="hidden" id="emp_cod" value="<?php echo $u['emp_cod']; ?> ">
                                            <input type="text" id="emp_razonsocial" class="form-control" value="<?php echo $u['emp_razonsocial']; ?> ">
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
                                <button type="button" class="btn bg-red waves-effect " onclick="cancelar()">
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