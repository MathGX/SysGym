<!-- Jquery Core Js -->
<script src="/SysGym/plugins/jquery/jquery.min.js"></script>

<!-- Bootstrap Core Js -->
<script src="/SysGym/plugins/bootstrap/js/bootstrap.js"></script>

<!-- Validation Plugin Js -->
<script src="/SysGym/plugins/jquery-validation/jquery.validate.js"></script>

<!-- Select Plugin Js -->
<script src="/SysGym/plugins/bootstrap-select/js/bootstrap-select.js"></script>

<!-- Slimscroll Plugin Js -->
<script src="/SysGym/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

<!-- Waves Effect Plugin Js -->
<script src="/SysGym/plugins/node-waves/waves.js"></script>

<!-- Jquery DataTable Plugin Js -->
<script src="/SysGym/plugins/jquery-datatable/jquery.dataTables.js"></script>
<script src="/SysGym/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
<script src="/SysGym/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
<script src="/SysGym/plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
<script src="/SysGym/plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
<script src="/SysGym/plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
<script src="/SysGym/plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
<script src="/SysGym/plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
<script src="/SysGym/plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>

<!-- SweetAlert Plugin Js -->
<script src="/SysGym/plugins/sweetalert/sweetalert.min.js"></script>

<!-- Custom Js -->
<script src="/SysGym/js/admin.js"></script>
<script src="/SysGym/js/pages/forms/basic-form-elements.js"></script>
<script src="/SysGym/js/pages/index.js"></script>

<!-- Demo Js -->
<script src="/SysGym/js/demo.js"></script>

<!-- Tema Js -->
<script src="/SysGym/tema.js"></script>

<!-- Autosize Plugin Js -->
<script src="/SysGym/plugins/autosize/autosize.js"></script>

<!-- Moment Plugin Js -->
<script src="/SysGym/plugins/momentjs/moment.js"></script>

<!-- Bootstrap Material Datetime Picker Plugin Js -->
<script src="/SysGym/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>

<!-- Bootstrap Datepicker Plugin Js -->
<script src="/SysGym/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<!-- Jquery CountTo Plugin Js -->
<script src="/SysGym/plugins/jquery-countto/jquery.countTo.js"></script>

<!-- Morris Plugin Js -->
<script src="/SysGym/plugins/raphael/raphael.min.js"></script>
<script src="/SysGym/plugins/morrisjs/morris.js"></script>

<!-- ChartJs -->
<script src="/SysGym/plugins/chartjs/Chart.bundle.js"></script>

<!-- Flot Charts Plugin Js -->
<script src="/SysGym/plugins/flot-charts/jquery.flot.js"></script>
<script src="/SysGym/plugins/flot-charts/jquery.flot.resize.js"></script>
<script src="/SysGym/plugins/flot-charts/jquery.flot.pie.js"></script>
<script src="/SysGym/plugins/flot-charts/jquery.flot.categories.js"></script>
<script src="/SysGym/plugins/flot-charts/jquery.flot.time.js"></script>

<!-- Sparkline Chart Plugin Js -->
<script src="/SysGym/plugins/jquery-sparkline/jquery.sparkline.js"></script>

<!-- Bootstrap Notify Plugin Js -->
<script src="/SysGym/plugins/bootstrap-notify/bootstrap-notify.js"></script>

<!-- JQuery Steps Plugin Js -->
<script src="/SysGym/plugins/jquery-steps/jquery.steps.js"></script>

<!-- funcion para mantener activa la opcion seleccionada en el menu -->
<script>
    let activarMenu = () => {
        var url = window.location.pathname;
        $(".list li a ").each(function () {
            var href = $(this).attr("href");
            if (url == href) {
                $(this).parent().addClass("active");
                $(this).parent().parent().parent().addClass("active");
                $(this).parent().parent().parent().parent().parent().addClass("active");
                $(this).parent().parent().parent().parent().parent().parent().addClass("active");
                $(this).parent().parent().parent().parent().parent().parent().parent().addClass("active");
                $(this).parent().parent().parent().parent().parent().parent().parent().parent().addClass("active");
            }
        });
    };

    activarMenu();
</script>

<!--------------------------------------------------------------- funcion para la busqueda del menú --------------------------------------------------------------->
<script>
//capturamos los datos de la vista v_gui_amin en un JSON a través de POST para listarlo
function getGuiDescri() {
    $.ajax({
        method: "POST",
        url: "/SysGym/lists/listaGuiDescri.php",
        data: {
            busquedaMenu:$("#busquedaMenu").val()
        }
        //en base al JSON traído desde el listaGuiDescri arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>";
        }else{
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionGuiDescri("+JSON.stringify(item)+")'>"+item.guidescri+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulGuiDescri").html(fila);
        //le damos un estilo a la lista de GuiDescri
        $("#listaGuiDescri").attr("style", "display:block; position:absolute; z-index:3000; width:100%");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el item por su key y enviamos el dato al input correspondiente
function seleccionGuiDescri (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulGuiDescri").html();
    $("#listaGuiDescri").attr("style", "display:none;");
    window.location = datos.url;
}

function oculSeccGUI() {  
    $("#listaGuiDescri").attr("style", "display:none;");
}
</script>

<!--------------------------------------------------------------- JS del acceso --------------------------------------------------------------->
<script>
    /*Consultamos a la base de datos por método POST el usuario y contraseña */
    let verificar = function () {
        $.ajax({
            method: "POST",
            url: "/SysGym/others/inicio/acceso.php",
            data: {
                usu_login: $("#usu_login").val(),
                usu_contrasena: $("#usu_contrasena").val()
            }
        }).done(function (resultado) {
            /*a través de un ciclo if consultamos si el usuario está inactivo, si es así rechaza el login*/
            if (resultado.usu_estado == "INACTIVO") {
                $(".alerta_error").text("Usuario inactivo");
                $(".alert_fail").attr("class", "alert_fail alert bg-pink alert-dismissible");
                $("#usu_login").val("");
                $("#usu_contrasena").val("");

                // Insertar datos en la tabla 'acceso'
                $.ajax({
                    method: "POST",
                    url: "/SysGym/others/inicio/registroAcceso.php",
                    data: {
                        acc_usu: resultado.usu_login,
                        acc_fecha: new Date().toLocaleDateString(),
                        acc_hora: new Date().toLocaleTimeString(),
                        acc_obs: "USUARIO INACTIVO"
                    }
                });
            } else {
                if (!resultado) {
                    $(".alerta_error").text("Usuario o Contraseña Incorrecta");
                    $(".alert_fail").attr("class", "alert_fail alert bg-pink alert-dismissible");
                    $("#usu_contrasena").val("");

                    // Insertar datos en la tabla 'acceso'
                    $.ajax({
                        method: "POST",
                        url: "/SysGym/others/inicio/registroAcceso.php",
                        data: {
                            acc_usu: $("#usu_login").val(),
                            acc_fecha: new Date().toLocaleDateString(),
                            acc_hora: new Date().toLocaleTimeString(),
                            acc_obs: "LOGIN INCORRECTO"
                        }
                    });
                } else {
                    $(".acc1").attr("style", "display:none");
                    $(".acc2").removeAttr("style", "display:none");
                    $("#boton_ingreso").val('1');
                    $("#per_email").val(resultado.per_email);
                    $(".alert_fail").attr("class", "alert_fail alert bg-pink alert-dismissible hidden");
                    //Se envian datos para la clave de seguridad
                    $.ajax({
                        method: "POST",
                        url: "/SysGym/others/inicio/correoAcceso.php",
                        data: {
                            accon_usu: $("#usu_login").val(),
                            accon_clave: Math.floor(Math.random() * 900000) + 100000,
                            accon_fecha: new Date().toLocaleDateString(),
                            accon_hora: new Date().toLocaleTimeString(),
                            accon_obs: "CORREO ENVIADO",
                            correo: $("#per_email").val()
                        }
                    }).done(function (envio) {   
                        $(".alerta_correo").text(envio.mensaje);
                        $(".alert_mail").attr("class", "alert_mail alert bg-pink alert-dismissible");
                    });
                }
            }
        }).fail(function (a, b, c) {
            alert(c);
        });
    }

    /*Consultamos a la base de datos por método POST el usuario*/
    let ingresar = function () {
        $.ajax({
            method: "POST",
            url: "/SysGym/others/inicio/acceso_control.php",
            data: {
                accon_usu: $("#usu_login").val(),
                case: "control"
            }
        }).done(function (resultado) {
            if (resultado.accon_intentos <= "3" && resultado.accon_clave == $("#accon_clave").val()) {
                window.location = "menu.php";
                //---------------- Insertar datos en la tabla 'acceso'----------------
                $.ajax({
                    method: "POST",
                    url: "/SysGym/others/inicio/registroAcceso.php",
                    data: {
                        acc_usu: $("#usu_login").val(),
                        acc_fecha: new Date().toLocaleDateString(),
                        acc_hora: new Date().toLocaleTimeString(),
                        acc_obs: "ACCESO CORRECTO"
                    }
                });
                // Insertar datos en la tabla 'acceso_control'
                $.ajax({
                    method: "POST",
                    url: "/SysGym/others/inicio/acceso_control.php",
                    data: {
                        accon_usu: $("#usu_login").val(),
                        accon_obs: "CLAVE CORRECTA",
                        case: "observacion"
                    }
                });
            } else if (resultado.accon_intentos == "3" && resultado.accon_clave != $("#accon_clave").val()) {
                // Insertar datos en la tabla 'acceso_control'
                $.ajax({
                    method: "POST",
                    url: "/SysGym/others/inicio/acceso_control.php",
                    data: {
                        accon_usu: $("#usu_login").val(),
                        accon_obs: "CLAVE INCORRECTA",
                        case: "observacion"
                    }
                });
                location.reload(true);
            } else {
                // Insertar datos en la tabla 'acceso_control'
                $.ajax({
                    method: "POST",
                    url: "/SysGym/others/inicio/acceso_control.php",
                    data: {
                        accon_usu: $("#usu_login").val(),
                        accon_obs: "CLAVE INCORRECTA",
                        case: "observacion"
                    }
                });
                $(".alerta_error").text("Clave incorrecta");
                $(".alert_fail").attr("class", "alert_fail alert bg-pink alert-dismissible");
                $("#accon_clave").val("");
            }
        }).fail(function (a, b, c) {
            alert(c);
        });
    }

    document.addEventListener('keypress', function (e) {
        if (e.keyCode === 13 && $("#boton_ingreso").val() == 1) {
            ingresar();
        } else if (e.keyCode === 13 && $("#boton_ingreso").val() == 0) {
            verificar();
        }
    });
</script>

<!--------------------------------------------------------------- JS para el cambio de contraseña --------------------------------------------------------------->
<script>
    /*Consultamos a la base de datos por método POST el usuario para verificar que existe*/
    let verificar_user = function () {
        $.ajax({
            method: "POST",
            url: "/SysGym/others/cambio_pass/controlUser.php",
            data: {
                usu_login: $("#usu_login").val(),
                case: "verificar"
            }
        }).done(function (resultado) {
            //a través de un ciclo if consultamos si el usuario está inactivo, si es así rechaza el login
            if (resultado.usu_estado == "INACTIVO") {
                $(".alerta_error").text("Usuario inactivo");
                $(".alert_fail").attr("class", "alert_fail alert bg-pink alert-dismissible");
                $("#usu_login").val("");
                $("#usu_contrasena").val("");
            } else {
                if (!resultado) {
                    $(".alerta_error").text("Usuario inexistente");
                    $(".alert_fail").attr("class", "alert_fail alert bg-pink alert-dismissible");
                    $("#usu_login").val("");
                } else {
                    $(".acc1").attr("style", "display:none");
                    $(".acc2").removeAttr("style", "display:none");
                    $(".acc3").attr("style", "display:none");
                    $(".msg").html("<b>Ingrese clave de seguridad</b>");
                    $("#boton_recuperar").val('1');
                    $("#per_email").val(resultado.per_email);
                    $(".alert_fail").attr("class", "alert_fail alert bg-pink alert-dismissible hidden");
                    //Se envian datos para la clave de seguridad
                    $.ajax({
                        method: "POST",
                        url: "/SysGym/others/cambio_pass/correoCamPass.php",
                        data: {
                            actpas_usu: $("#usu_login").val(),
                            actpas_clave: Math.floor(Math.random() * 900000) + 100000,
                            actpas_fecha: new Date().toLocaleDateString(),
                            actpas_hora: new Date().toLocaleTimeString(),
                            actpas_obs: "CORREO ENVIADO",
                            correo: $("#per_email").val()
                        }
                    }).done(function (envio) {   
                        $(".alerta_correo").text(envio.mensaje);
                        $(".alert_mail").attr("class", "alert_mail alert bg-pink alert-dismissible");
                    }).fail(function (envio){
                        $(".alerta_correo").text(envio.mensaje);
                        $(".alert_mail").attr("class", "alert_mail alert bg-pink alert-dismissible");
                    });
                }
            }
        }).fail(function (a, b, c) {
            alert(c);
        });
    }

    /*Consultamos la clave a la base de datos por método POST*/
    let comprobar_clave = function () {
        $.ajax({
            method: "POST",
            url: "/SysGym/others/cambio_pass/controlUser.php",
            data: {
                actpas_usu: $("#usu_login").val(),
                case: "control"
            }
        }).done(function (resultado) {
            if (resultado.actpas_intentos <= "3" && resultado.actpas_clave == $("#actpas_clave").val()) {
                $(".acc1").attr("style", "display:none");
                $(".acc2").attr("style", "display:none");
                $(".acc3").removeAttr("style", "display:none");
                $(".msg").html("<b>Actualice contraseña de acceso <br> (Mínimo 16 caracteres entre mayúsculas, minúsculas, números y caracteres especiales)</b>");
                $("#boton_recuperar").val('2');
                $(".alert_fail").attr("class", "alert_fail alert bg-pink alert-dismissible hidden");

                // Insertar datos en la tabla 'actualizar_pass_user'
                $.ajax({
                    method: "POST",
                    url: "/SysGym/others/cambio_pass/controlUser.php",
                    data: {
                        actpas_usu: $("#usu_login").val(),
                        actpas_obs: "CLAVE CORRECTA",
                        case: "observacion"
                    }
                });
            } else if (resultado.actpas_intentos == "3" && resultado.actpas_clave != $("#actpas_clave").val()) {
                window.location = "index.php";
                // Insertar datos en la tabla 'actualizar_pass_user'
                $.ajax({
                    method: "POST",
                    url: "/SysGym/others/cambio_pass/controlUser.php",
                    data: {
                        actpas_usu: $("#usu_login").val(),
                        actpas_obs: "CLAVE INCORRECTA",
                        case: "observacion"
                    }
                });
            } else {
                // Insertar datos en la tabla 'actualizar_pass_user'
                $.ajax({
                    method: "POST",
                    url: "/SysGym/others/cambio_pass/controlUser.php",
                    data: {
                        actpas_usu: $("#usu_login").val(),
                        actpas_obs: "CLAVE INCORRECTA",
                        case: "observacion"
                    }
                });

                $(".alerta_error").text("Clave incorrecta");
                $(".alert_fail").attr("class", "alert_fail alert bg-pink alert-dismissible");
                $("#actpas_clave").val("");
            }
        }).fail(function (a, b, c) {
            alert(c);
        });
    }

    /*Se actualiza la contraseña por post*/
    let actualizar_pass = function () {
        let pass1 = $("#usu_contrasena1").val();
        let pass2 = $("#usu_contrasena2").val();
        let mensajeError = "";

        // Validación de la contraseña
        if (pass1.length < 16) {
            mensajeError = "La contraseña debe tener al menos 16 caracteres";
        } else if (!/[A-Z]/.test(pass1)) {
            mensajeError = "La contraseña debe tener al menos una letra mayúscula";
        } else if (!/[a-z]/.test(pass1)) {
            mensajeError = "La contraseña debe tener al menos una letra minúscula";
        } else if (!/[0-9]/.test(pass1)) {
            mensajeError = "La contraseña debe tener al menos un número";
        } else if (!/[°/\-'_¡!@#$%^&*(),.¿?":{}|<>;~`]/.test(pass1)) {
            mensajeError = "La contraseña debe tener al menos un carácter especial (@ # $ %)";
        } else if (pass1 !== pass2) {
            mensajeError = "Las contraseñas no coinciden";
        }

        // Manejo de errores
        if (mensajeError) {
            $(".alerta_error").text(mensajeError);
            $(".alert_fail").attr("class", "alert_fail alert bg-pink alert-dismissible");
            $("#usu_contrasena1, #usu_contrasena2").val("");
            return; // Salir de la función si hay un error
        }

        // Si todas las validaciones son exitosas, realizar la solicitud AJAX
        $.ajax({
            method: "POST",
            url: "/SysGym/others/cambio_pass/controlUser.php",
            data: {
                usu_login: $("#usu_login").val(),
                usu_contrasena: pass2,
                actpas_obs: "CONTRASEÑA ACTUALIZADA",
                case: "actualizar"
            }
        }).done(function (respuesta) {
            if (respuesta.status === "success") {
                swal({
                    title: "RESPUESTA!!",
                    text: respuesta.message,
                    type: respuesta.status,
                }, function () {
                    window.location = "index.php"; // Recargar la página
                });
            }
        });
    };

    document.addEventListener('keypress', function (e) {
        if (e.keyCode === 13 && $("#boton_recuperar").val() == 0) {
            verificar_user();
        } else if (e.keyCode === 13 && $("#boton_recuperar").val() == 1) {
            comprobar_clave(); 
        } else if (e.keyCode === 13 && $("#boton_recuperar").val() == 2) {
            actualizar_pass();
        }
    });
</script>