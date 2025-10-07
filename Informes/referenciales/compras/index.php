<?php
//iniciamos variables de sesión
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>INFORME REF. COMPRAS</title>
    <!--Se icluyen los estilos CSS ingresando desde la carpeta raíz hacia el importCSS-->
    <?php 
    $u = $_SESSION['usuarios'];
    include "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/extension/importCSS.php"; 
    ?>
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
                                INFORME DE REFERENCIALED DE COMPRAS<small>Registros de referenciales de compras</small>
                            </h2>
                        </div>
                        <div class="body">
                            <input type="hidden" id="operacion" value="0">
                            <div class="row clearfix">

                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focus focus">
                                            <input type="text" id="tabla" class="form-control" onkeyup="getTabla()">
                                            <label class="form-label">Reporte</label>
                                            <div id="listaTabla" style="display: none;">
                                                <ul class="list-group" id="ulTabla" style="height:60px; overflow:auto;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 codigo">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" id="desde" class="form-control ">
                                            <label class="form-label">Desde (Opcional)</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 codigo">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" id="hasta" class="form-control">
                                            <label class="form-label">Hasta (Opcional)</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 empresa" style="display:none;">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="hidden" id="emp_cod" value="<?php echo $u['emp_cod'];?>">
                                            <input type="text" id="emp_razonsocial" class="form-control" value="<?php echo $u['emp_razonsocial']; ?> " disabled>
                                            <label class="form-label">Empresa</label>
                                            <div id="listaEmpresas" style="display: none;">
                                                <ul class="list-group" id="ulEmpresas" style="height:60px; overflow:auto;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 ciudad" style="display:none;">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="hidden" class="form-control disabledno" id="ciu_cod" value="0">
                                            <input type="text" class="form-control disabledno" id="ciu_descripcion" disabled onkeyup="getCiudades()">
                                            <label class="form-label">Ciudad (Opcional)</label>
                                            <div id="listaCiudades" style="display: none;">
                                                <ul class="list-group" id="ulCiudades" style="height:60px; overflow:auto;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-2 sucursal" style="display:none;">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="hidden" id="suc_cod" class="form-control disabledno" value="0">
                                            <input type="text" class="form-control disabledno" id="suc_descri" disabled >
                                            <label class="form-label">Sucursal (Opcional)</label>
                                            <div id="listaSucursalEmp" style="display: none;">
                                                <ul class="list-group" id="ulSucursalEmp"  style="height:60px; overflow:auto;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-2 tipo_item" style="display:none;">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="hidden" id="tipitem_cod" class="form-control disabledno" value="0">
                                            <input type="text" class="form-control disabledno" id="tipitem_descri" disabled onkeyup="getTipoItem()">
                                            <label class="form-label">Tipo de Item (Opcional)</label>
                                            <div id="listaTipoItem" style="display: none;">
                                                <ul class="list-group" id="ulTipoItem"  style="height:60px; overflow:auto;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 unidad_medida" style="display:none;">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="hidden" id="uni_cod" class="form-control disabledno" value="0">
                                            <input type="text" class="form-control disabledno" id="uni_descri" disabled onkeyup="getUniMedida()">
                                            <label class="form-label">Unidad de Medida (Opcional)</label>
                                            <div id="listaUniMedida" style="display: none;">
                                                <ul class="list-group" id="ulUniMedida"  style="height:60px; overflow:auto;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-2 impuesto" style="display:none;">
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="hidden" id="tipimp_cod" class="form-control disabledno" value="0">
                                            <input type="text" class="form-control disabledno" id="tipimp_descri" disabled onkeyup="getTipoImpuesto()">
                                            <label class="form-label">Impuesto (Opcional)</label>
                                            <div id="listaTipoImpuesto" style="display: none;">
                                                <ul class="list-group" id="ulTipoImpuesto"  style="height:60px; overflow:auto;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-2 tipo_proveedor" style="display:none;"> 
                                    <div class="form-group form-float">
                                        <div class="form-line foc">
                                            <input type="hidden" id="tiprov_cod" class="form-control disabledno" value="0">
                                            <input type="text" class="form-control disabledno" id="tiprov_descripcion" disabled onkeyup="getTipoProv()">
                                            <label class="form-label">Tipo de proveedor (Opcional)</label>
                                            <div id="listaTipoProv" style="display: none;">
                                                <ul class="list-group" id="ulTipoProv" style="height:60px; overflow:auto;"></ul>
                                            </div>
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