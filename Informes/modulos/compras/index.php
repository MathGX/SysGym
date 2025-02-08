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
    <title>INFORME MOVIMIENTO DE COMPRAS</title>
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
                                INFORME DE MOVIMIENTOS DE COMPRA<small>Registrar datos de modulos</small>
                            </h2>
                        </div>
                        <div class="body">
                            <input type="hidden" id="operacion" value="0">
                            <div class="row clearfix">

                                <div class="col-md-4">
                                    <div class="form-group form-float">
                                        <div class="form-line mod focused">
                                            <input type="text" id="tabla" class="form-control" onclick="getTabla()">
                                            <label class="form-label">Movimiento</label>
                                            <div id="listaTabla" style="display: none;">
                                                <ul class="list-group" id="ulTabla"  style="height: 50px; width: 250px; overflow: scroll;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 codigo">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="date" id="desde" class="form-control ">
                                            <label class="form-label">Desde</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 codigo">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="date" id="hasta" class="form-control">
                                            <label class="form-label">Hasta</label>
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

                                <div class="col-sm-3 depo" style="display: none;">
                                    <div class="form-group form-float">
                                        <div class="form-line foc focused">
                                            <input type="hidden" id="dep_cod" value="0">
                                            <input type="text" class="form-control disabledno" id="dep_descri" onkeyup="getDeposito()">
                                            <label class="form-label">Depósito</label>
                                            <div id="listaDeposito" style="display: none;">
                                                <ul class="list-group" id="ulDeposito" style="height: 60px; width: 250px; overflow: scroll;"></ul>
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