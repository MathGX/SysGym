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
    <title>FUNCIONARIOS</title>
    <!--Se icluyen los estilos CSS ingresando desde la carpeta raíz hacia el importCSS-->
    <?php include "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/extension/importCSS.php"; ?>
</head>

<body class="theme">
    <?php include "{$_SERVER['DOCUMENT_ROOT']}/SysGym/opciones.php"; ?>

    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">

                <div class="col-lg-12">
                    <!-- formulario de funcionarios -->
                    <div class="card">
                        <div class="header bg-indigo">
                            <h2>
                            FUNCIONARIOS<small>Registrar los datos de funcionarios</small>
                            </h2>
                        </div>
                        <div class="body">
                            <input type="hidden" id="operacion" value="0">
                            <input type="hidden" id="usu_cod" value="<?php echo $u['usu_cod'];?>">
                            <input type="hidden" id="usu_login" value="<?php echo $u['usu_login'];?>">
                            <input type="hidden" id="transaccion" value="">
                            <div class="row clearfix">

                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="text" id="fun_cod" class="form-control" disabled>
                                            <label class="form-label">Código</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focus focused">
                                            <input type="date" class="form-control disabledno" id="fun_fechaingreso" disabled>
                                            <label class="form-label">Fecha de Ingreso</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="hidden" id="per_cod" value="0">
                                            <input type="text" class="form-control disabledno" id="per_nrodoc" disabled onkeyup="getPersonas()">
                                            <label class="form-label">Documento Nro.</label>
                                            <div id="listaPersonas" style="display: none;">
                                                <ul class="list-group" id="ulPersonas" style="height:60px; overflow:auto;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="text" class="form-control" id="persona" disabled>
                                            <label class="form-label">Nombre y Apellido</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="hidden" id="ciu_cod" value="0">
                                            <input type="text" class="form-control disabledno" id="ciu_descripcion" disabled onkeyup="getCiudades()">
                                            <label class="form-label">Ciudad</label>
                                            <div id="listaCiudades" style="display: none;">
                                                <ul class="list-group" id="ulCiudades" style="height:60px; overflow:auto;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="hidden" id="car_cod" value="0">
                                            <input type="text" class="form-control disabledno" id="car_descri" disabled onkeyup="getCargos()">
                                            <label class="form-label">Cargo</label>
                                            <div id="listaCargos" style="display: none;">
                                                <ul class="list-group" id="ulCargos" style="height:60px; overflow:auto;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="hidden" id="emp_cod" value="0">
                                            <input type="text" class="form-control disabledno" id="emp_razonsocial" disabled onkeyup="getEmpresaSuc()">
                                            <label class="form-label">Empresa</label>
                                            <div id="listaEmpresaSuc" style="display: none;">
                                                <ul class="list-group" id="ulEmpresaSuc" style="height:60px; overflow:auto;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="hidden" id="suc_cod" value="0">
                                            <input type="text" class="form-control" id="suc_descri" disabled >
                                            <label class="form-label">Sucursal</label>
                                            <div id="listaSucursalEmp" style="display: none;">
                                                <ul class="list-group" id="ulSucursalEmp" style="height:60px; overflow:auto;"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line focus">
                                            <input type="text" id="fun_estado" class="form-control" disabled>
                                            <label class="form-label">Estado</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- botones del formulario de funcionarios -->
                            </div>
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
                                <button type="button" style=display:none class="btn bg-pink waves-effect btnOperacion2"
                                    onclick="controlVacio()">
                                    <i class="material-icons">archive</i>
                                    <span>CONFIRMAR</span>
                                </button>
                                <button type="button" style=display:none class="btn bg-pink waves-effect btnOperacion2"
                                    onclick="cancelar()">
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

                <!-- grilla del formulario funcionarios-->
                <div class="col-lg-12 tbl" style="display: block;">
                    <div class="card">
                        <div class="header bg-indigo">
                            <h2>
                                LISTADO FUNCIONARIOS<small>Funcionarios registrados</small>
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover table-borderer table-striped dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th>CÓDIGO</th>
                                            <th>FECHA DE INGRESO</th>
                                            <th>NOMBRE Y APELLIDO</th>
                                            <th>CIUDAD</th>
                                            <th>CARGO</th>
                                            <th>EMPRESA</th>
                                            <th>SUCURSAL</th>
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