
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>VENTAS</title>
    <!--Se icluyen los estilos CSS ingresando desde la carpeta raíz hacia el importCSS-->
    <?php include "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/extension/importCSS.php"; ?>
</head>

<body class="theme">
    <?php //iniciamos variables de sesión
    session_start();
    include "{$_SERVER['DOCUMENT_ROOT']}/SysGym/opciones.php"; 
    ?>

    <style>

    /* Estilos para los botones */
        .icon-and-text-button-demo .btn {
        font-size:  2em; /* Aumenta el tamaño de la fuente para hacer los botones más grandes */
        padding:  10px  20px; /* Añade espacio interno para que los botones sean más grandes */
        height: 80px;
        margin:  10px; /* Añade margen alrededor de cada botón para separación */
        }

        /* Estilos para centrar los botones usando Flexbox */
        .icon-and-text-button-demo {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height:  50vh; /* Asegúrate de que el contenedor ocupe toda la altura de la vista */
        }


    </style>

    <script>

    const productos = () =>{
        window.location = "ventaItem/index.php";
    }

    const servicios = () =>{
        window.location = "ventaPresupuesto/index.php";
    }

    </script>

    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
                <?php if (($apertura['apcier_estado'] == 'ABIERTA') || ($u['perf_descri'] == 'ADMINISTRADOR')) { ?>
                    <div class="col-lg-12">
                        <!-- formulario de ciudades -->
                        <div class="card">
                            <div class="header">
                                <h2>
                                    SELECCIONAR LA VENTA
                                </h2>
                            </div>
                            <div class="body">

                                <!-- botones del formulario de ventas -->
                                <div class="icon-and-text-button-demo">
                                    <button type="button"class="btn bg-green waves-effect" onclick= "productos()">
                                        <i class="material-icons">shopping_cart</i>
                                        <span>VENTA PRODUCTOS</span>
                                    </button>
                                    <button type="button" class="btn bg-green waves-effect" onclick= "servicios()">
                                        <i class="material-icons">assistant</i>
                                        <span>VENTA SERVICIOS</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="col-sm-12 bg-pink" style=" width:100%; border-radius:10px; padding:10px;" >
                        <div class="form-line focus">
                            <div style="text-align: center;"><h3>LA CAJA SE ENCUNETRA CERRADA, DEBE ABRIRSE PRIMERAMENTE</h3></div>
                        </div>
                    </div>
                <?php }?>
            </div>

        </div>
    </section>

    <!--Se icluyen los métodos JS ingresando desde la carpeta raíz hacia el importJS-->
    <?php include "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/extension/importJS.php"; ?>
</body>

</html>