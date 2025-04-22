<?php

//consultamos si existe la varieable de sesión
if (isset($_SESSION['usuarios'])) {
    //si existe la sesión guardamos en la variable "u" como array asiciativo
    $u = $_SESSION['usuarios'];
} else {
    //si no, direccionamos al inicio
    header("Location: http://localhost/SysGym/index.php");
}

require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

$objConexion = new Conexion();
$conexion = $objConexion->getConexion();
$perfil = $u['perf_descri'];

$sql = "select
        p.perf_descri,
        g.gui_descri,
        gp.guiperf_estado
        from gui_perfiles gp
        join perfiles p on p.perf_cod = gp.perf_cod
        join gui g on g.gui_cod = gp.gui_cod
        where p.perf_descri ilike '%$perfil%'";

$resultado = pg_query($conexion, $sql);
$guis = pg_fetch_all($resultado);

//
$REF_SEGURIDAD = false;
$REF_COMPRAS = false;
$PEDIDOS_COMPRA = false;
$PRESUPUESTOS_PROVEEDOR = false;
$ORDENES_COMPRA = false;
$COMPRAS = false;
$NOTAS_COMPRAS = false;
$AJUSTES_INVENTARIO = false;
$REF_SERVICIOS = false;
$INSCRIPCIONES = false;
$MEDICIONES = false;
$RUTINAS = false;
$PLANES_ALIMENTICIOS = false;
$PRESUPUESTO_PREPARACION = false;
$EVOLUCION = false;
$ASISTENCIAS = false;
$SALIDAS = false;
$REF_VENTAS = false;
$APERTURA_CIERRE_CAJA = false;
$PEDIDOS_VENTAS = false;
$VENTAS = false;
$COBRANZAS = false;
$NOTAS_VENTAS = false;
$INF_REF_COMPRAS = false;
$INF_MOV_COMPRAS = false;
$INF_REF_SERVICIOS = false;
$INF_MOV_SERVICIOS = false;
$INF_REF_VENTAS = false;
$INF_MOV_VENTAS = false;
$INF_SEGURIDAD = false;

$interfaz = [
    'REF_SEGURIDAD' => 'REF_SEGURIDAD',
    'REF_COMPRAS' => 'REF_COMPRAS',
    'PEDIDOS_COMPRA' => 'PEDIDOS_COMPRA',
    'PRESUPUESTOS_PROVEEDOR' => 'PRESUPUESTOS_PROVEEDOR',
    'ORDENES_COMPRA' => 'ORDENES_COMPRA',
    'COMPRAS' => 'COMPRAS',
    'NOTAS_COMPRAS' => 'NOTAS_COMPRAS',
    'AJUSTES_INVENTARIO' => 'AJUSTES_INVENTARIO',
    'REF_SERVICIOS' => 'REF_SERVICIOS',
    'INSCRIPCIONES' => 'INSCRIPCIONES',
    'MEDICIONES' => 'MEDICIONES',
    'RUTINAS' => 'RUTINAS',
    'PLANES_ALIMENTICIOS' => 'PLANES_ALIMENTICIOS',
    'PRESUPUESTO_PREPARACION' => 'PRESUPUESTO_PREPARACION',
    'EVOLUCION' => 'EVOLUCION',
    'ASISTENCIAS' => 'ASISTENCIAS',
    'SALIDAS' => 'SALIDAS',
    'REF_VENTAS' => 'REF_VENTAS',
    'APERTURA_CIERRE_CAJA' => 'APERTURA_CIERRE_CAJA',
    'PEDIDOS_VENTAS' => 'PEDIDOS_VENTAS',
    'VENTAS' => 'VENTAS',
    'COBRANZAS' => 'COBRANZAS',
    'NOTAS_VENTAS' => 'NOTAS_VENTAS',
    'INF_REF_COMPRAS' => 'INF_REF_COMPRAS',
    'INF_MOV_COMPRAS' => 'INF_MOV_COMPRAS',
    'INF_REF_SERVICIOS' => 'INF_REF_SERVICIOS',
    'INF_MOV_SERVICIOS' => 'INF_MOV_SERVICIOS',
    'INF_REF_VENTAS' => 'INF_REF_VENTAS',
    'INF_MOV_VENTAS' => 'INF_MOV_VENTAS',
    'INF_SEGURIDAD' => 'INF_SEGURIDAD'
];

//
foreach ($guis as $gui){
    if ((array_key_exists("gui_descri", $gui)) && ($gui['guiperf_estado'] == 'ACTIVO')) {
        $interfazVal = strval($interfaz[$gui['gui_descri']]);
        $$interfazVal = true;
    }
}

$apertura = ['apcier_cod' => 0,
            'caj_cod' => 0,
            'caj_descri' => '',
            'apcier_estado' => ''];

if(isset($_SESSION['numApcier'])){
    $apertura = ['apcier_cod' => $_SESSION['numApcier']['codigo'],
                'caj_cod' => $_SESSION['numApcier']['caja'],
                'caj_descri' => $_SESSION['numApcier']['cajDescri'],
                'apcier_estado' => $_SESSION['numApcier']['estado']];
}


?>

<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="preloader">
            <div class="spinner-layer pl-red">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
        <p>Espere un momento...</p>
    </div>
</div>
<!-- #END# Page Loader -->
<!-- Overlay For Sidebars -->
<div class="overlay"></div>
<!-- #END# Overlay For Sidebars -->
<!-- Search Bar -->
<div class="search-bar">
    <div class="search-icon">
        <i class="material-icons">search</i>
    </div>
    <input style="border:5px solid #3f51b5;" type="text" id="busquedaMenu" placeholder="INGRESE DATOS DE BÚSQUEDA" onkeyup="getGuiDescri()">
    <div class="close-search" onclick="oculSeccGUI()">
        <i class="material-icons">close</i>
    </div>
    <div id="listaGuiDescri" style="display: none;">
        <ul class="list-group" id="ulGuiDescri" style="height:60px; overflow:auto;"></ul>
    </div>
</div>
<!-- #END# Search Bar -->
<!-- Top Bar -->
<nav class="navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse"
                data-target="#navbar-collapse" aria-expanded="false"></a>
            <a href="javascript:void(0);" class="bars"></a>
            <a class="navbar-brand" href="/SysGym/menu.php">ORBUS GYM</a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <!-- Call Search -->
                <li><a href="javascript:void(0);" class="js-search" data-close="true"><i
                            class="material-icons">search</i></a></li>
                <!-- #END# Call Search -->
                <!-- Notifications -->
                <!-- <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                        <i class="material-icons">notifications</i>
                        <span class="label-count">7</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">NOTIFICATIONS</li>
                        <li class="body">
                            <ul class="menu">
                                <li>
                                    <a href="javascript:void(0);">
                                        <div class="icon-circle bg-light-green">
                                            <i class="material-icons">person_add</i>
                                        </div>
                                        <div class="menu-info">
                                            <h4>12 new members joined</h4>
                                            <p>
                                                <i class="material-icons">access_time</i> 14 mins ago
                                            </p>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <div class="icon-circle bg-cyan">
                                            <i class="material-icons">add_shopping_cart</i>
                                        </div>
                                        <div class="menu-info">
                                            <h4>4 sales made</h4>
                                            <p>
                                                <i class="material-icons">access_time</i> 22 mins ago
                                            </p>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <div class="icon-circle bg-red">
                                            <i class="material-icons">delete_forever</i>
                                        </div>
                                        <div class="menu-info">
                                            <h4><b>Nancy Doe</b> deleted account</h4>
                                            <p>
                                                <i class="material-icons">access_time</i> 3 hours ago
                                            </p>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <div class="icon-circle bg-orange">
                                            <i class="material-icons">mode_edit</i>
                                        </div>
                                        <div class="menu-info">
                                            <h4><b>Nancy</b> changed name</h4>
                                            <p>
                                                <i class="material-icons">access_time</i> 2 hours ago
                                            </p>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <div class="icon-circle bg-blue-grey">
                                            <i class="material-icons">comment</i>
                                        </div>
                                        <div class="menu-info">
                                            <h4><b>John</b> commented your post</h4>
                                            <p>
                                                <i class="material-icons">access_time</i> 4 hours ago
                                            </p>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <div class="icon-circle bg-light-green">
                                            <i class="material-icons">cached</i>
                                        </div>
                                        <div class="menu-info">
                                            <h4><b>John</b> updated status</h4>
                                            <p>
                                                <i class="material-icons">access_time</i> 3 hours ago
                                            </p>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <div class="icon-circle bg-purple">
                                            <i class="material-icons">settings</i>
                                        </div>
                                        <div class="menu-info">
                                            <h4>Settings updated</h4>
                                            <p>
                                                <i class="material-icons">access_time</i> Yesterday
                                            </p>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="javascript:void(0);">View All Notifications</a>
                        </li>
                    </ul>
                </li> -->
                <!-- #END# Notifications -->
                <!-- Tasks -->
                <!-- <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                        <i class="material-icons">flag</i>
                        <span class="label-count">9</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">TASKS</li>
                        <li class="body">
                            <ul class="menu tasks">
                                <li>
                                    <a href="javascript:void(0);">
                                        <h4>
                                            Footer display issue
                                            <small>32%</small>
                                        </h4>
                                        <div class="progress">
                                            <div class="progress-bar bg-pink" role="progressbar" aria-valuenow="85"
                                                aria-valuemin="0" aria-valuemax="100" style="width: 32%">
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <h4>
                                            Make new buttons
                                            <small>45%</small>
                                        </h4>
                                        <div class="progress">
                                            <div class="progress-bar bg-cyan" role="progressbar" aria-valuenow="85"
                                                aria-valuemin="0" aria-valuemax="100" style="width: 45%">
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <h4>
                                            Create new dashboard
                                            <small>54%</small>
                                        </h4>
                                        <div class="progress">
                                            <div class="progress-bar bg-teal" role="progressbar" aria-valuenow="85"
                                                aria-valuemin="0" aria-valuemax="100" style="width: 54%">
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <h4>
                                            Solve transition issue
                                            <small>65%</small>
                                        </h4>
                                        <div class="progress">
                                            <div class="progress-bar bg-orange" role="progressbar" aria-valuenow="85"
                                                aria-valuemin="0" aria-valuemax="100" style="width: 65%">
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <h4>
                                            Answer GitHub questions
                                            <small>92%</small>
                                        </h4>
                                        <div class="progress">
                                            <div class="progress-bar bg-purple" role="progressbar" aria-valuenow="85"
                                                aria-valuemin="0" aria-valuemax="100" style="width: 92%">
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="javascript:void(0);">View All Tasks</a>
                        </li>
                    </ul>
                </li> -->
                <!-- #END# Tasks -->
                <!-- <li class="pull-right"><a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i
                            class="material-icons">more_vert</i></a></li> -->
            </ul>
        </div>
    </div>
</nav>
<!-- #Top Bar -->
<section>
    <!-- Left Sidebar -->
    <aside id="leftsidebar" class="sidebar">
        <!-- User Info -->
        <div class="user-info">
            <div class="image">
                <img src="/SysGym/images/user.png" width="48" height="48" alt="User" />
            </div>
            <div class="info-container">
                <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo $u['per_nombres'] . " " . $u['per_apellidos'] ?>
                </div>
                <div class="email">
                    <?php echo $u['per_email']; ?>
                </div>
                <div class="btn-group user-helper-dropdown">
                    <i class="material-icons" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="true">keyboard_arrow_down</i>
                    <ul class="dropdown-menu pull-right">
                        <li><a href="javascript:void(0);"><i class="material-icons">person</i>Perfil</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="javascript:void(0);"><i class="material-icons">group</i>
                                <?php echo $u['perf_descri']; ?>
                            </a></li>
                        <li><a href="javascript:void(0);"><i class="material-icons">shopping_cart</i>
                                <?php echo $u['suc_descri']; ?>
                            </a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="/SysGym/index.php"><i class="material-icons">input</i>Salir</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- #User Info -->
        <!-- Menu -->
        <div class="menu">
            <ul class="list">
                <li class="header">NAVEGACIÓN </li>
                <li class="active">
                    <a href="/SysGym/menu.php">
                        <i class="material-icons">home</i>
                        <span>INICIO</span>
                    </a>
                </li>
                <?php if ($u['mod_descri'] == 'SISTEMAS') { ?>
                <!--OPCIONES DE REFERNCIALES-->
                <li>
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">assignment_turned_in</i>
                        <span>REFERENCIALES</span>
                    </a>
                    <ul class="ml-menu">
                    <?php if($REF_SEGURIDAD == true){?>
                        <li>
                            <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">security</i>
                                <span>Ref. Seguridad</span>
                            </a>
                            <ul class="ml-menu">
                                <li >
                                    <a href="/SysGym/referenciales/seguridad/usuarios/index.php">Usuarios</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/seguridad/asignacion_per_usu/index.php">Asignación de permisos</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/seguridad/modulos/index.php">Modulos</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/seguridad/permisos/index.php">Permisos</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/seguridad/perfiles/index.php">Perfiles</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/seguridad/GUI/index.php">GUI</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/seguridad/perfiles_permisos/index.php">Permisos por perfil</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/seguridad/gui_perfiles/index.php">GUI por perfil</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/seguridad/configuraciones/index.php">Configuraciones</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/seguridad/suc_config/index.php">Configuraciones por Sucursal</a>
                                </li>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if($REF_SERVICIOS == true){?>
                        <li>
                            <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">fitness_center</i>
                                <span>Ref. Servicios</span>
                            </a>
                            <ul class="ml-menu">
                                <li>
                                    <a href="/SysGym/referenciales/servicios/personas/index.php">Personas</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/servicios/cargos/index.php">Cargos</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/servicios/funcionarios/index.php">Funcionarios</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/servicios/dias/index.php">Días</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/servicios/tipo_equipo/index.php">Tipos de Equipo</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/servicios/equipos/index.php">Equipos</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/servicios/parametros_medicion/index.php">Parámetros de medición</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/servicios/unidad_medida/index.php">Unidades de medida</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/servicios/ejercicios/index.php">Ejercicios</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/servicios/tipo_rutina/index.php">Tipos de Rutina</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/servicios/tipo_plan_alimenticio/index.php">Tipos de plan alimenticio</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/servicios/comidas/index.php">Comidas</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/servicios/horarios_comida/index.php">Horarios de Comida</a>
                                </li>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if($REF_COMPRAS == true){?>
                        <li>
                            <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">add_shopping_cart</i>
                                <span>Ref. Compras</span>
                            </a>
                            <ul class="ml-menu">
                                <li>
                                    <a href="/SysGym/referenciales/compras/empresa/index.php">Empresas</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/compras/sucursales/index.php">Sucursales</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/compras/depositos/index.php">Depósitos</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/compras/ciudades/index.php">Ciudades</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/compras/tipo_impuesto/index.php">Tipos de impuesto</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/compras/tipo_item/index.php">Tipos de item</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/compras/items/index.php">Items</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/compras/tipo_proveedor/index.php">Tipos de proveedor</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/compras/proveedores/index.php">Proveedores</a>
                                </li>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if($REF_VENTAS == true){?>
                        <li>
                            <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">shop</i>
                                <span>Ref. Ventas</span>
                            </a>
                            <ul class="ml-menu">
                                <li>
                                    <a href="/SysGym/referenciales/ventas/clientes/index.php">Clientes</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/ventas/entidad_emisora/index.php">Entidades Financieras Emisoras</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/ventas/marca_tarjeta/index.php">Marcas de tarjeta</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/ventas/entidad_adherida/index.php">Entidades Adheridas</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/ventas/red_pago/index.php">Redes de pago</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/ventas/caja/index.php">Cajas</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/ventas/forma_cobro/index.php">Formas de cobro</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/ventas/tipo_documento/index.php">Tipo de Documento</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/ventas/tipo_comprobante/index.php">Tipo de Comprobante</a>
                                </li>
                                <li>
                                    <a href="/SysGym/referenciales/ventas/facturas/index.php">Facturas de Venta</a>
                                </li>
                            </ul>
                        </li>
                    <?php } ?>

                    </ul>
                </li>
                <?php } ?>

                <li>
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">domain</i>
                        <span>MODULOS</span>
                    </a>
                    <ul class="ml-menu">
                        <?php if (($u['mod_descri'] == 'COMPRAS') || ($u['perf_descri'] == 'ADMINISTRADOR')) { ?>
                        <li>
                            <a href="javascript:void(0);" class="menu-toggle">
                                <i class="material-icons">add_shopping_cart</i>
                                <span>Compras</span>
                            </a>
                            <ul class="ml-menu">
                                <?php if ($PEDIDOS_COMPRA == true) { ?>
                                <li>
                                    <a href="/SysGym/modulos/compras/pedidos_compra/index.php">Pedidos de Compra</a>
                                </li>
                                <?php } ?>
                                <?php if ($PRESUPUESTOS_PROVEEDOR == true) { ?>
                                <li>
                                    <a href="/SysGym/modulos/compras/presupuesto_proveedor/index.php">Presupuestos de Proveedores</a>
                                </li>
                                <?php } ?>
                                <?php if ($ORDENES_COMPRA == true) { ?>
                                <li>
                                    <a href="/SysGym/modulos/compras/orden_compra/index.php">Ordenes de Compra</a>
                                </li>
                                <?php } ?>
                                <?php if ($COMPRAS == true) { ?>
                                <li>
                                    <a href="/SysGym/modulos/compras/compras/index.php">Compras</a>
                                </li>
                                <?php } ?>
                                <?php if ($AJUSTES_INVENTARIO == true) { ?>
                                <li>
                                    <a href="/SysGym/modulos/compras/ajuste_inventario/index.php">Ajustes de inventario</a>
                                </li>
                                <?php } ?>
                                <?php if ($NOTAS_COMPRAS == true) { ?>
                                <li>
                                    <a href="/SysGym/modulos/compras/nota_compra/index.php">Notas de compra</a>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php } ?>

                        <?php if (($u['mod_descri'] == 'SERVICIOS') || ($u['perf_descri'] == 'ADMINISTRADOR')) { ?>
                        <li>
                            <a href="javascript:void(0);" class="menu-toggle">
                                <i class="material-icons">fitness_center</i>
                                <span>Servicios</span>
                            </a>
                            <ul class="ml-menu">
                                <?php if ($INSCRIPCIONES == true) { ?>
                                <li>
                                    <a href="/SysGym/modulos/servicios/inscripciones/index.php">Inscripciones</a>
                                </li>
                                <?php } ?>
                                <?php if ($PRESUPUESTO_PREPARACION == true) { ?>
                                <li>
                                    <a href="/SysGym/modulos/servicios/presupuesto_preparacion/index.php">Presupuesto de Preparación</a>
                                </li>
                                <?php } ?>
                                <?php if ($MEDICIONES == true) { ?>
                                <li>
                                    <a href="/SysGym/modulos/servicios/mediciones/index.php">Mediciones</a>
                                </li>
                                <?php } ?>
                                <?php if ($RUTINAS == true) { ?>
                                <li>
                                    <a href="/SysGym/modulos/servicios/rutinas/index.php">Rutinas</a>
                                </li>
                                <?php } ?>
                                <?php if ($PLANES_ALIMENTICIOS == true) { ?>
                                <li>
                                    <a href="/SysGym/modulos/servicios/plan_alimenticio/index.php">Planes Alimenticios</a>
                                </li>
                                <?php } ?>
                                <?php if ($EVOLUCION == true) { ?>
                                <li>
                                    <a href="/SysGym/modulos/servicios/evolucion/index.php">Evolucion</a>
                                </li>
                                <?php } ?>
                                <?php if ($ASISTENCIAS == true) { ?>
                                <li>
                                    <a href="/SysGym/modulos/servicios/asistencias/index.php">Asistencias</a>
                                </li>
                                <?php } ?>
                                <?php if ($SALIDAS == true) { ?>
                                <li>
                                    <a href="/SysGym/modulos/servicios/salidas/index.php">Salidas</a>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php } ?>

                        <?php if (($u['mod_descri'] == 'VENTAS') || ($u['perf_descri'] == 'ADMINISTRADOR')) { ?>
                        <li>
                            <a href="javascript:void(0);" class="menu-toggle">
                                <i class="material-icons">shop</i>
                                <span>Ventas</span>
                            </a>
                            <ul class="ml-menu">
                                <?php if ($APERTURA_CIERRE_CAJA == true) { ?>
                                <li>
                                    <a href="/SysGym/modulos/ventas/apertura_cierre/index.php">Apertura y cierre de caja</a>
                                </li>
                                <?php } ?>
                                <?php if ($PEDIDOS_VENTAS == true) { ?>
                                <li>
                                    <a href="/SysGym/modulos/ventas/pedidos_venta/index.php">Pedidos de Venta</a>
                                </li>
                                <?php } ?>
                                <?php 
                                if (($VENTAS == true) && (($apertura['apcier_estado'] === 'ABIERTA') || ($u['perf_descri'] == 'ADMINISTRADOR'))) { ?>
                                <li>
                                    <a href="/SysGym/modulos/ventas/ventas/index.php">Ventas</a>
                                    <ul class="ml-menu">
                                        <li>
                                            <a href="/SysGym/modulos/ventas/ventas/ventaItem/index.php">Ventas Productos</a>
                                        </li>
                                        <li>
                                            <a href="/SysGym/modulos/ventas/ventas/ventaPresupuesto/index.php">Ventas Servicios</a>
                                        </li>
                                    </ul>
                                </li>
                                <?php } ?>
                                <?php if (($COBRANZAS == true) && (($apertura['apcier_estado'] === 'ABIERTA') || ($u['perf_descri'] == 'ADMINISTRADOR'))) { ?>
                                <li>
                                    <a href="/SysGym/modulos/ventas/cobros/index.php">Cobranzas</a>
                                </li>
                                <?php } ?>
                                <?php 
                                if ($NOTAS_VENTAS == true) { ?>
                                <li>
                                    <a href="/SysGym/modulos/ventas/nota_venta/index.php">Notas de Venta</a>
                                </li>
                                <?php } ?>

                            </ul>
                        </li>
                        <?php } ?>

                    </ul>

                </li>

                <li>
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">library_books</i>
                        <span>REPORTES</span>
                    </a>
                    <ul class="ml-menu">
                        <?php if ($u['mod_descri'] == 'SISTEMAS') { ?>
                        <li>
                            <a href="javascript:void(0);" class="menu-toggle">
                                <i class="material-icons">label</i>
                                <span>Referenciales</span>
                            </a>
                            <ul class="ml-menu">
                                <?php if ($INF_SEGURIDAD == true) { ?>
                                <li>
                                    <a href="/SysGym/Informes/referenciales/seguridad/index.php">Seguridad</a>
                                </li>
                                <?php } ?>
                                <?php if ($INF_REF_COMPRAS == true) { ?>
                                <li>
                                    <a href="/SysGym/Informes/referenciales/compras/index.php">Ref. compras</a>
                                </li>
                                <?php } ?>
                                <?php if ($INF_REF_SERVICIOS == true) { ?>
                                <li>
                                    <a href="/SysGym/Informes/referenciales/servicios/index.php">Ref. servicios</a>
                                </li>
                                <?php } ?>
                                <?php if ($INF_REF_VENTAS == true) { ?>
                                <li>
                                    <a href="/SysGym/Informes/referenciales/ventas/index.php">Ref. ventas</a>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php } ?>

                        <li>
                            <a href="javascript:void(0);" class="menu-toggle">
                                <i class="material-icons">work</i>
                                <span>Modulos</span>
                            </a>
                            <ul class="ml-menu">
                                <?php if ($INF_MOV_COMPRAS == true) { ?>
                                <li>
                                    <a href="/SysGym/Informes/modulos/compras/index.php">Compras</a>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
                    </ul>
                </li>



                <!-- <li class="header">LABELS</li>
                <li>
                    <a href="javascript:void(0);">
                        <i class="material-icons col-red">donut_large</i>
                        <span>Important</span>
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0);">
                        <i class="material-icons col-amber">donut_large</i>
                        <span>Warning</span>
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0);">
                        <i class="material-icons col-light-blue">donut_large</i>
                        <span>Information</span>
                    </a>
                </li> -->
            </ul>
        </div>
        <!-- #Menu -->
        <!-- Footer -->
        <!-- <div class="legal">
            <div class="copyright">
                &copy; 2016 - 2017 <a href="javascript:void(0);">AdminBSB - Material Design</a>.
            </div>
            <div class="version">
                <b>Version: </b> 1.0.5
            </div>
        </div> -->
        <!-- #Footer -->

    </aside>
    <!-- #END# Left Sidebar -->
    <!-- Right Sidebar -->
    <aside id="rightsidebar" class="right-sidebar">
        <!-- <ul class="nav nav-tabs tab-nav-right" role="tablist">
            <li role="presentation" class="active"><a href="#skins" data-toggle="tab">SKINS</a></li>
            <li role="presentation"><a href="#settings" data-toggle="tab">SETTINGS</a></li>
        </ul> -->
        <div class="tab-content">
            <!-- <div role="tabpanel" class="tab-pane fade in active in active" id="skins">
                <ul class="demo-choose-skin">
                    <li data-theme="red" class="active">
                        <div class="red"></div>
                        <span>Red</span>
                    </li>
                    <li data-theme="pink">
                        <div class="pink"></div>
                        <span>Pink</span>
                    </li>
                    <li data-theme="purple">
                        <div class="purple"></div>
                        <span>Purple</span>
                    </li>
                    <li data-theme="deep-purple">
                        <div class="deep-purple"></div>
                        <span>Deep Purple</span>
                    </li>
                    <li data-theme="indigo">
                        <div class="indigo"></div>
                        <span>Indigo</span>
                    </li>
                    <li data-theme="blue">
                        <div class="blue"></div>
                        <span>Blue</span>
                    </li>
                    <li data-theme="light-blue">
                        <div class="light-blue"></div>
                        <span>Light Blue</span>
                    </li>
                    <li data-theme="cyan">
                        <div class="cyan"></div>
                        <span>Cyan</span>
                    </li>
                    <li data-theme="teal">
                        <div class="teal"></div>
                        <span>Teal</span>
                    </li>
                    <li data-theme="green">
                        <div class="green"></div>
                        <span>Green</span>
                    </li>
                    <li data-theme="light-green">
                        <div class="light-green"></div>
                        <span>Light Green</span>
                    </li>
                    <li data-theme="lime">
                        <div class="lime"></div>
                        <span>Lime</span>
                    </li>
                    <li data-theme="yellow">
                        <div class="yellow"></div>
                        <span>Yellow</span>
                    </li>
                    <li data-theme="amber">
                        <div class="amber"></div>
                        <span>Amber</span>
                    </li>
                    <li data-theme="orange">
                        <div class="orange"></div>
                        <span>Orange</span>
                    </li>
                    <li data-theme="deep-orange">
                        <div class="deep-orange"></div>
                        <span>Deep Orange</span>
                    </li>
                    <li data-theme="brown">
                        <div class="brown"></div>
                        <span>Brown</span>
                    </li>
                    <li data-theme="grey">
                        <div class="grey"></div>
                        <span>Grey</span>
                    </li>
                    <li data-theme="blue-grey">
                        <div class="blue-grey"></div>
                        <span>Blue Grey</span>
                    </li>
                    <li data-theme="black">
                        <div class="black"></div>
                        <span>Black</span>
                    </li>
                </ul>
            </div> -->
            <!-- <div role="tabpanel" class="tab-pane fade" id="settings">
                <div class="demo-settings">
                    <p>GENERAL SETTINGS</p>
                    <ul class="setting-list">
                        <li>
                            <span>Report Panel Usage</span>
                            <div class="switch">
                                <label><input type="checkbox" checked><span class="lever"></span></label>
                            </div>
                        </li>
                        <li>
                            <span>Email Redirect</span>
                            <div class="switch">
                                <label><input type="checkbox"><span class="lever"></span></label>
                            </div>
                        </li>
                    </ul>
                    <p>SYSTEM SETTINGS</p>
                    <ul class="setting-list">
                        <li>
                            <span>Notifications</span>
                            <div class="switch">
                                <label><input type="checkbox" checked><span class="lever"></span></label>
                            </div>
                        </li>
                        <li>
                            <span>Auto Updates</span>
                            <div class="switch">
                                <label><input type="checkbox" checked><span class="lever"></span></label>
                            </div>
                        </li>
                    </ul>
                    <p>ACCOUNT SETTINGS</p>
                    <ul class="setting-list">
                        <li>
                            <span>Offline</span>
                            <div class="switch">
                                <label><input type="checkbox"><span class="lever"></span></label>
                            </div>
                        </li>
                        <li>
                            <span>Location Permission</span>
                            <div class="switch">
                                <label><input type="checkbox" checked><span class="lever"></span></label>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div> -->
    </aside>
    <!-- #END# Right Sidebar -->
</section>