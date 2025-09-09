// 
let datusUsuarios = () => {
    $.ajax({
        method: "POST",
        url: "/SysGym/others/inicio/datosUser.php",
        }).done(function (datos) {
            $("#usu_cod").val(datos.usu_cod);
            $("#usu_login").val(datos.usu_login);
            $("#suc_cod").val(datos.suc_cod);
            $("#suc_descri").val(datos.suc_descri);
            $("#emp_cod").val(datos.emp_cod);
            $("#emp_razonsocial").val(datos.emp_razonsocial);
        });
};

// funcion para obtener la fecha actual
let formatoFecha = (fecha) => {
    let dia = fecha.getDate();
    let mes = fecha.getMonth() + 1;
    let ano = fecha.getFullYear();
    let fechaHoy = '';

    mes = mes < 10 ? '0' + mes : mes;
    dia = dia < 10 ? '0' + dia : dia;

    fechaHoy = `${ano}-${mes}-${dia}`;

    return fechaHoy;
}

let ahora = new Date();
$("#presprov_fecha").val(formatoFecha(ahora));

//funcion para mostrar alertas con label en el mensaje
let alertaLabel = (msj) => {
    swal({
        html: true,
        title: "ATENCIÓN!!",
        text: msj,
        type: "error",
    });
}

// Variable para rastrear si se hizo clic en la lista
let clickEnLista = false;

// Evento mousedown para todos los elementos cuyo id comience con "lista"
$("[id^='lista']").on("mousedown", function() {
    clickEnLista = true;
});

//funcion para alertar campos vacios de forma individual
let completarDatos = (nombreInput, idInput) => {
    mensaje = "";
    //si el input está vacío mostramos la alerta
    if ($(idInput).val().trim() === "") {
        mensaje = "El campo <b>" + nombreInput + "</b> no puede quedar vacío.";
        alertaLabel(mensaje);
        $(".focus").attr("class", "form-line focus focused");
    }
}

// Evento blur para inputs con clase .disabledno
$(".disabledno").each(function() {
    $(this).on("blur", function() {
        let idInput = "#" + $(this).attr("id");
        let nombreInput = $(this).closest('.form-line').find('.form-label').text();

        if (clickEnLista) {
            clickEnLista = false; // Reinicia bandera
            return;
        }
        completarDatos(nombreInput, idInput);
    });
});

//funcion para alertar campos que solo acepten numeros
let soloNumeros = (nombreInput, idInput) => {
    if (idInput === "#pro_ruc") { // admite guion
        caracteres = /['_¡´°/\!@#$%^&*(),.¿?":{}|<>;~`+]/;
    } else {
        caracteres = /[-'_¡´°/\!@#$%^&*(),.¿?":{}|<>;~`+]/;
    }
    letras = /[a-zA-Z]/;
    valor = $(idInput).val().trim();
    mensaje = "";
    //si el input no está vacío y contiene letras o caracteres especiales mostramos la alerta
    if ( valor !== "" && (caracteres.test(valor) || letras.test(valor))) {
        mensaje = "El campo <b>" + nombreInput + "</b> solo puede aceptar valores numéricos";
        if (idInput === "#pro_ruc") {
            mensaje += " y guión"; // concatena la cadena extra
        }
        alertaLabel(mensaje);
        $(idInput).val("");
    }
}

//ejecución del método soloNumeros al perder el foco de los inputs con clase .soloNum
$(".soloNum").each(function() {
    $(this).on("keyup", function() {
        let idInput = "#" + $(this).attr("id"); //capturamos el id del input que perdió el foco
        let nombreInput = $(this).closest('.form-line').find('.form-label').text(); //capturamos el texto de la etiqueta label asociada al input
        soloNumeros(nombreInput, idInput); //llamamos a la función pasarle el nombre del input y su id
    });
});

//funcion para alertar campos que no acepten caracteres especiales
let sinCaracteres = (nombreInput, idInput) => {
    caracteres = /[-'_¡´°/\!@#$%^&*(),.¿?":{}|<>;~`+]/;
    valor = $(idInput).val().trim();
    mensaje = "";
    //si el input no está vacío y contiene letras o caracteres especiales mostramos la alerta
    if ( valor !== "" && caracteres.test(valor)) {
        mensaje = "El campo <b>" + nombreInput + "</b> no puede aceptar caracteres especiales.",
        alertaLabel(mensaje);
        $(idInput).val("");
    }
}

//ejecución del método sinCaracteres al perder el foco de los inputs con clase .sinCarac
$(".sinCarac").each(function() {
    $(this).on("keyup", function() {
        let idInput = "#" + $(this).attr("id"); //capturamos el id del input que perdió el foco
        let nombreInput = $(this).closest('.form-line').find('.form-label').text(); //capturamos el texto de la etiqueta label asociada al input
        sinCaracteres(nombreInput, idInput); //llamamos a la función pasarle el nombre del input y su id
    });
});

/*-------------------------------------------- METODOS DE LA CABECERA --------------------------------------------*/

//funcion habilitar inputs
let habilitarBotones = (operacion_cab) => {
    /* Segun el parametro sea verdadero o falso deshabilitamos o habilitamos los botones */
    if (operacion_cab) {
        $(".btnOperacion1").attr("style", "display:none;");
        $(".btnOperacion2").attr("style", "width:12.5%;");
    } else {
        $(".btnOperacion2").attr("style", "display:none;");
        $(".btnOperacion1").attr("style", "width:12.5%;");
    }
};

//metodo para limpiar los campos de cabecera
let limpiarCab = () =>{
    $(".tblcab .body input").each(function(){
        $(this).val('');
    });
    $(".tblcab .body #presprov_fecha").each(function(){
        $(this).val(formatoFecha(ahora));
    });
    $(".tblcab .header .focus").each(function() {
        $(this).attr("class", "form-line focus")
    });
    $(".tblcab .body .focus").each(function() {
        $(this).attr("class","form-line focus" )
    });
}

//funcion para obtener el siguiente codigo
let getCod = () => {
    $.ajax({
        method: "POST",
        url: "controlador.php",
        data: {consulCod: 1}
    }).done(function (respuesta){
        $("#presprov_cod").val(respuesta.codigo);
    });
}

//funcion nuevo
let nuevo = () => {
    limpiarCab();
    $("#operacion_cab").val(1);
    $("#transaccion").val('INSERCION');
    $(".disabledno").removeAttr("disabled");
    $(".focus").attr("class", "form-line focus focused");
    $("#presprov_estado").val('ACTIVO');
    $(".tbl, .tbldet").attr("style", "display:none");
    getCod();
    habilitarBotones(true);
    datusUsuarios();
    window.scroll(0, -100);
};

//anular anular
let anular = () => {
    $("#operacion_cab").val(2);
    $("#transaccion").val('BORRADO');
    $("#presprov_estado").val('ANULADO');
    habilitarBotones(true);
    window.scroll(0, -100);
};

//funcion cancelar
let cancelar = () => {
    window.location.reload();
};

//funcion salir
let salir = () => {
    window.location = "/SysGym/menu.php";
};

//funcion grabar
let grabar = () => {
    $.ajax({
        //Enviamos datos al controlador
        method: "POST",
        url: "controlador.php",
        data: {
            presprov_cod: $("#presprov_cod").val(),
            presprov_fecha: $("#presprov_fecha").val(),
            presprov_fechavenci: $("#presprov_fechavenci").val(),
            presprov_estado: $("#presprov_estado").val(),
            pro_cod: $("#pro_cod").val(),
            tiprov_cod: $("#tiprov_cod").val(),
            suc_cod: $("#suc_cod").val(),
            emp_cod: $("#emp_cod").val(),
            usu_cod: $("#usu_cod").val(),
            pedcom_cod: $("#pedcom_cod").val(),
            operacion_cab: $("#operacion_cab").val(),
            pro_ruc: $("#pro_ruc").val(),
            pro_razonsocial: $("#pro_razonsocial").val(),
            suc_descri: $("#suc_descri").val(),
            emp_razonsocial: $("#emp_razonsocial").val(),
            usu_login: $("#usu_login").val(),
            transaccion: $("#transaccion").val()
        },
    }) //Establecemos un mensaje segun el contenido de la respuesta
        .done(function (respuesta) {
            swal(
                {
                    title: "RESPUESTA!!",
                    text: respuesta.mensaje,
                    type: respuesta.tipo,
                },
                function () {
                    //Si la respuesta devuelve un success recargamos la pagina
                    if (respuesta.tipo == "success") {
                        location.reload(true);
                    }
                }
            );
        }).fail(function (a, b, c) {
            let errorTexto = a.responseText;
            let inicio = errorTexto.indexOf("{"); // Obtenemos el índice del primer "{" y agregamos 1 para saltar el mismo
            let final = errorTexto.lastIndexOf("}") + 1; // Obtenemos el índice del último "}"
            let errorJson = errorTexto.substring(inicio, final); // Extraemos la palabra entre los índices obtenidos

            let errorObjeto = JSON.parse(errorJson);
            console.log(errorObjeto.tipo);

            if (errorObjeto.tipo == "error") {
                swal({
                    title: "RESPUESTA!!",
                    text: errorObjeto.mensaje,
                    type: errorObjeto.tipo,
                });
            }
        });
};

//funcion confirmar SweetAlert
let confirmar = () => {
    //solicitamos el value del input operacion
    var oper = $("#operacion_cab").val();

    preg = "¿Desea agregar el registro?";

    /* De acuerdo si la operacion es 2 o 3 modificamos la pregunta */
    if (oper == 2) {
        preg = "¿Desea anular el registro?";
    }
    swal(
        {
            title: "Atención!!!",
            text: preg,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "SI",
            cancelButtonText: "NO",
            closeOnConfirm: false,
            closeOnCancel: false,
        },
        function (isConfirm) {
            //Si la operacion es correcta llamamos al metodo grabar
            if (isConfirm) {
                let archivo = $("#archExcel")[0].files[0];
                if (archivo) {
                    grabarExcel();
                } else {
                    grabar();
                }
            } else {
                //Si cancelamos la operacion realizamos un reload
                cancelar();
            }
        }
    );
};

//funcion control vacio
let controlVacio = () => {
    // Obtener todos los ids de los elementos con clase disabledno
    let campos = $(".focus").find('.form-control').map(function() {
        return this.id;
    }).get();

    // Array para almacenar los nombres de los campos vacíos
    let camposVacios = [];

    // Recorrer los ids y verificar si el valor está vacío
    campos.forEach(function(id) {
        let $input = $("#" + id);
        if ($input.val().trim() === "") {
            // Busca el label asociado
            let nombreInput = $input.closest('.form-line').find('.form-label').text() || id;
            camposVacios.push(nombreInput);
        }
    });

    // Si hay campos vacíos, mostrar alerta; de lo contrario, confirmar
    if (camposVacios.length > 0) {
        let archivo = $("#archExcel")[0].files[0];
        if (archivo) {
            confirmar();
        } else {
            swal({
                html: true,
                title: "RESPUESTA!!",
                text: "Complete los siguientes campos: <b>" + camposVacios.join(", ") + "</b>.",
                type: "error",
            });
        }
    } else {
        confirmar();
    }
};

/*se establece el formato de la grilla*/
function formatoTabla() {
    $(".js-exportable").DataTable({
        language: {
            url: "/SysGym/others/extension/Spanish.json",
        },
        dom:
            "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        responsive: true,
        buttons: [],
    });
}

/*-------------------------------------------- METODOS DEL DETALLE --------------------------------------------*/

//funcion habilitar inputs
let habilitarBotones2 = (operacion_det) => {
    /* Segun el parametro sea verdadero o falso deshabilitamos o habilitamos los botones */
    if (operacion_det) {
        $(".btnOperacion3").attr("style", "display:none;");
        $(".btnOperacion4").attr("style", "width:12.5%;");
    } else {
        $(".btnOperacion4").attr("style", "display:none;");
        $(".btnOperacion3").attr("style", "width:12.5%;");
    }
};

//funcion para validar si se puede agregar o eliminar un detalle
let validarDetalle = () => {
    return $.ajax({
        method: "POST",
        url: "controladorDetalles.php",
        data: {
            validacion_det: 1,
            presprov_cod: $("#presprov_cod").val(),
        }
    });
}

//funcion agregar
let agregar = () => {
    validarDetalle().done(function(respuesta) {
        if (respuesta.validar == 1) {
            alertaLabel("NO SE PUEDEN AGREGAR MAS ITEMS, EL PRESUPUESTO SE ENCUENTRA ASOCIADO A UNA ORDEN");
            return;
        }
        $("#operacion_det").val(1);
        $(".foc").find(".form-control").val('');
        $(".foc").find(".disabledno").removeAttr("disabled");
        $(".foc").attr("class", "form-line foc focused");
        habilitarBotones2(true);
        window.scroll(0, -100);
    });
};

//funcion eliminar
let eliminar = () => {
    validarDetalle().done(function(respuesta) {
        if (respuesta.validar == 1) {
            alertaLabel("NO SE PUEDEN ELIMINAR ITEMS, EL PRESUPUESTO SE ENCUENTRA ASOCIADO A UNA ORDEN");
            return;
        }
        $("#operacion_det").val(2);
        habilitarBotones2(true);
        window.scroll(0, -100);
    });
};

/*enviamos por POST a la base de datos los datos cargados los input para grabar un nuevo detalle de inscripción*/
function grabar2() {
    $.ajax({
        method: "POST",
        url: "controladorDetalles.php",
        data: {
            itm_cod: $("#itm_cod").val(),
            tipitem_cod: $("#tipitem_cod").val(),
            presprov_cod: $("#presprov_cod").val(),
            presprovdet_cantidad: $("#presprovdet_cantidad").val(),           
            presprovdet_precio: $("#presprovdet_precio").val(),
            operacion_det: $("#operacion_det").val(),
        }
}) //Establecemos un mensaje segun el contenido de la respuesta
    .done(function (respuesta) {
        swal(
            {
                title: "RESPUESTA!!",
                text: respuesta.mensaje,
                type: respuesta.tipo,
            },
            function () {
                //Si la respuesta devuelve un success recargamos la pagina
                if (respuesta.tipo == "success") {
                    listar2(); //actualizamos la grilla
                    $(".foc").find(".form-control").val(''); //limpiamos los input
                    $(".foc").attr("class", "form-line foc"); //se bajan los labels quitando el focused
                    $(".disabledno").attr("disabled", "disabled"); //deshabilitamos los input
                    habilitarBotones2(false); //deshabilitamos los botones
                }
            }
        );
    }).fail(function (a, b, c) {
        let errorTexto = a.responseText;
        let inicio = errorTexto.indexOf("{"); // Obtenemos el índice del primer "{" y agregamos 1 para saltar el mismo
        let final = errorTexto.lastIndexOf("}") + 1; // Obtenemos el índice del último "}"
        let errorJson = errorTexto.substring(inicio, final); // Extraemos la palabra entre los índices obtenidos

        let errorObjeto = JSON.parse(errorJson);
        console.log(errorObjeto.tipo);

        if (errorObjeto.tipo == "error") {
            swal({
                title: "RESPUESTA!!",
                text: errorObjeto.mensaje,
                type: errorObjeto.tipo,
            });
        }
    });
};

//funcion confirmar SweetAlert
let confirmar2 = () => {
    //solicitamos el value del input operacion
    var oper = $("#operacion_det").val();

    preg = "¿Desea agregar el registro?";

    /* De acuerdo si la operacion es 2 o 3 modificamos la pregunta */
    if (oper == 2) {
        preg = "¿Desea eliminar el registro?";
    }
    swal(
        {
            title: "Atención!!!",
            text: preg,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "SI",
            cancelButtonText: "NO",
            closeOnConfirm: false,
            closeOnCancel: false,
        },
        function (isConfirm) {
            //Si la operacion es correcta llamamos al metodo grabar
            if (isConfirm) {
                grabar2();
            } else {
                //Si cancelamos la operacion realizamos un reload
                cancelar();
            }
        }
    );
};

//funcion para validar que no haya campos vacios al grabar
let controlVacio2 = () => {
    // Obtener todos los ids de los elementos con clase disabledno
    let campos = $(".foc").find('.form-control').map(function() {
        return this.id;
    }).get();

    // Array para almacenar los nombres de los campos vacíos
    let camposVacios = [];

    // Recorrer los ids y verificar si el valor está vacío
    campos.forEach(function(id) {
        let $input = $("#" + id);
        if ($input.val().trim() === "") {
            // Busca el label asociado
            let nombreInput = $input.closest('.form-line').find('.form-label').text() || id;
            camposVacios.push(nombreInput);
        }
    });

    // Si hay campos vacíos, mostrar alerta; de lo contrario, confirmar
    if (camposVacios.length > 0) {
        swal({
            html: true,
            title: "RESPUESTA!!",
            text: "Complete los siguientes campos: <b>" + camposVacios.join(", ") + "</b>.",
            type: "error",
        });
    } else if (($("#tipitem_cod").val() == "1") && $("#pedcomdet_cantidad").val() !== "0") {
        alertaLabel("El campo <b>Cantidad</b> debe ser 0 (cero) para los servicios.");
    } else {
        confirmar2();
    }
};

/*---------------------------------------------------- GRABAR EXCEL ----------------------------------------------------*/

function grabarExcel() {
    
    let archivo = $("#archExcel")[0].files[0];

    let formData = new FormData();
    formData.append("archExcel", archivo);

    // Agregar datos
    formData.append("presprov_cod", $("#presprov_cod").val());
    formData.append("presprov_fecha", $("#presprov_fecha").val());
    formData.append("presprov_estado", $("#presprov_estado").val());
    formData.append("usu_cod", $("#usu_cod").val());
    formData.append("suc_cod", $("#suc_cod").val());
    formData.append("emp_cod", $("#emp_cod").val());
    formData.append("usu_login", $("#usu_login").val());
    formData.append("suc_descri", $("#suc_descri").val());
    formData.append("emp_razonsocial", $("#emp_razonsocial").val());
    formData.append("operacion_cab", $("#operacion_cab").val());
    formData.append("transaccion", $("#transaccion").val());

    $.ajax({
        method: "POST",
        url: "controladorExcel.php",
        data: formData,
        contentType: false,
        processData: false
    })
    .done(function (respuesta) {
        let texto = "";
        if (Array.isArray(respuesta.mensajes)) {
            texto = respuesta.mensajes.join("<br>");
        } else {
            texto = respuesta.mensaje; // fallback por si acaso
        }

        swal(
            {
                html: true,
                title: "RESPUESTA!!",
                text: texto,
                type: respuesta.tipo,
            },
            function () {
                if (respuesta.tipo === "success" || respuesta.tipo === "warning") {
                    location.reload(true);
                }
            }
        );
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
        let icon = "error";
        let mensajes = ["Ocurrió un error inesperado."];

        try {
            // Intentar parsear el JSON tal cual
            const json = JSON.parse(jqXHR.responseText || "{}");

            if (Array.isArray(json.mensajes)) {
                mensajes = json.mensajes;
            } else if (json.mensaje) {
                mensajes = [json.mensaje];
            }

            if (json.tipo) {
                icon = json.tipo;
            }
        } catch (e) {
            // Si hay basura en la respuesta, intentar extraer el {...}
            const txt = jqXHR.responseText || "";
            const i = txt.indexOf("{");
            const j = txt.lastIndexOf("}");
            if (i !== -1 && j !== -1 && j > i) {
                try {
                    const json2 = JSON.parse(txt.substring(i, j + 1));
                    if (Array.isArray(json2.mensajes)) {
                        mensajes = json2.mensajes;
                    } else if (json2.mensaje) {
                        mensajes = [json2.mensaje];
                    }
                    if (json2.tipo) {
                        icon = json2.tipo;
                    }
                } catch (_) {
                    // dejo el mensaje genérico
                }
            }
        }

        swal({
            html: true,
            title: "RESPUESTA!!",
            text: mensajes.join("<br>"),
            type: icon
        }, function () {
            if (icon === "error" || icon === "warning") {
                location.reload(true);
            }
        });
    });
}


/*---------------------------------------------------- LISTAR Y SELECCION DE CABECERA Y DETALLE ----------------------------------------------------*/

//funcion seleccionar Fila
let seleccionarFila2 = (objetoJSON) => {
    // Enviamos los datos a sus respectivos inputs
    Object.keys(objetoJSON).forEach(function (propiedad) {
        $("#" + propiedad).val(objetoJSON[propiedad]);
    });
    $(".foc").attr("class", "form-line foc focused");
};

//funcion listar
let listar2 = () => {
    $.ajax({
        method: "POST",
        url: "controladorDetalles.php",
        data: {
            presprov_cod: $("#presprov_cod").val(),
        }
    }).done(function (respuesta) {
        let tabla = "";
        let totalExe = 0;
        let totalI5 = 0;
        let totalI10 = 0;
        let impuesto10 = 0;
        let discrIva5 = 0;
        let discrIva10 = 0;
        let totalIva = 0;
        let totalGral = 0;
        for (objeto of respuesta) {
            if (objeto.tipitem_cod == 1){
                impuesto10 = parseFloat (objeto.presprovdet_precio);
            } else {
                impuesto10 = parseFloat (objeto.iva10)
            }
            totalExe += parseFloat(objeto.exenta);
            totalI5 += parseFloat(objeto.iva5);
            totalI10 += parseFloat (impuesto10);
                tabla += "<tr onclick='seleccionarFila2(" + JSON.stringify(objeto).replace(/'/g, '&#39;') + ")'>";
                    tabla += "<td>" + objeto.itm_descri + "</td>";
                    tabla += "<td align='right'>" + objeto.presprovdet_cantidad + "</td>";
                    tabla += "<td>" + objeto.uni_descri + "</td>";
                    tabla += "<td align='right'>" + new Intl.NumberFormat('us-US').format(objeto.presprovdet_precio) + "</td>";
                    tabla += "<td align='right'>" + new Intl.NumberFormat('us-US').format(objeto.exenta) + "</td>";
                    tabla += "<td align='right'>" + new Intl.NumberFormat('us-US').format(objeto.iva5) + "</td>";
                    tabla += "<td align='right'>" + new Intl.NumberFormat('us-US').format(impuesto10) + "</td>";
                tabla += "</tr>";
            }
            discrIva5 = parseFloat (totalI5/21);
            discrIva10 = parseFloat (totalI10/11);
            totalIva = (discrIva5 + discrIva10);
            totalGral = (totalExe + totalI5 + totalI10);

            let subt = "<th colspan='4' style='font-weight: bold;'> SUBTOTAL: </th>";
                subt += "<th style='text-align:right;'>" + new Intl.NumberFormat('us-US').format(totalExe) + "</th>";
                subt += "<th style='text-align:right;'>" + new Intl.NumberFormat('us-US').format(totalI5) + "</th>";
                subt += "<th style='text-align:right;'>" + new Intl.NumberFormat('us-US').format(totalI10) + "</th>";

            let tot = "<th colspan='6' style='font-weight: bold;'> TOTAL A PAGAR: </th>";
                tot += "<th style='text-align:right;'>" + new Intl.NumberFormat('us-US').format(totalGral) + "</th>";

            let imp = "<th colspan='2' style='font-weight: bold;'> IVA 5%: " + new Intl.NumberFormat('us-US').format(discrIva5.toFixed(2)) + "</th>";
                imp += "<th colspan='3' style='font-weight: bold;'> IVA 10%: " + new Intl.NumberFormat('us-US').format(discrIva10.toFixed(2)) + "</th>";
                imp += "<th colspan='3' style='font-weight: bold;'> TOTAL IVA: "+ new Intl.NumberFormat('us-US').format(totalIva.toFixed(2)) + "</th>";

            $("#grilla_det").html(tabla);
            $("#subtotal").html(subt);
            $("#total").html(tot);
            $("#impuesto").html(imp);
        })
        .fail(function (a, b, c) {
            swal("ERROR", c, "error");
        });
};

//funcion seleccionar Fila
let seleccionarFila = (objetoJSON) => {
    // Enviamos los datos a sus respectivos inputs
    Object.keys(objetoJSON).forEach(function (propiedad) {
        $("#" + propiedad).val(objetoJSON[propiedad]);
    });

    $(".focus").attr("class", "form-line focus focused");
    $(".tbldet").removeAttr("style", "display:none;");
    datusUsuarios();
    listar2();
};

//funcion listar
let listar = () => {
    $.ajax({
        method: "GET",
        url: "controlador.php"
    }).done(function (respuesta) {
            let tabla = "";
            for (objeto of respuesta) {
                tabla += "<tr onclick='seleccionarFila(" + JSON.stringify(objeto).replace(/'/g, '&#39;') + ")'>";
                    tabla += "<td>" + objeto.presprov_cod + "</td>";
                    tabla += "<td>" + objeto.usu_login + "</td>";
                    tabla += "<td>" + objeto.suc_descri + "</td>";
                    tabla += "<td>" + objeto.pedcom_cod + "</td>";
                    tabla += "<td>" + objeto.pro_razonsocial + "</td>";
                    tabla += "<td>" + objeto.presprov_fecha2 + "</td>";
                    tabla += "<td>" + objeto.presprov_fechavenci2 + "</td>";
                    tabla += "<td>" + objeto.presprov_estado + "</td>";
                tabla += "</tr>";
            }
            $("#grilla_cab").html(tabla);
            formatoTabla();
        })
        .fail(function (a, b, c) {
            swal("ERROR", c, "error");
        });
};

listar();

/*---------------------------------------------------- AUTOCOMPLETADOS ----------------------------------------------------*/

//capturamos los datos de la tabla pedido_compra_cab en un JSON a través de POST para listarlo
function getPedCom() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/compras/presupuesto_proveedor/listas/listaPedCom.php",
        data: {
            pedcom_cod:$("#pedcom_cod").val()
        }
        //en base al JSON traído desde el listaPedCom arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{    
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionPedCom("+JSON.stringify(item)+")'>"+item.pedcom_cod+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulPedCom").html(fila);
        //le damos un estilo a la lista de PedCom
        $("#listaPedCom").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el pedido de compra por su key y enviamos el dato al input correspondiente
function seleccionPedCom (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulPedCom").html();
    $("#listaPedCom").attr("style", "display:none;");
    $(".focus").attr("class", "form-line focus focused");
}

//capturamos los datos de la tabla items en un JSON a través de POST para listarlo
function getItems() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/compras/presupuesto_proveedor/listas/listaItems.php",
        data: {
            pedcom_cod:$("#pedcom_cod").val(),
            itm_descri:$("#itm_descri").val()
        }
        //en base al JSON traído desde el listaItems arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{    
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionItems("+JSON.stringify(item)+")'>"+item.itm_descri+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulItems").html(fila);
        //le damos un estilo a la lista de items
        $("#listaItems").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el item por su key y enviamos el dato al input correspondiente
function seleccionItems (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulItems").html();
    $("#listaItems").attr("style", "display:none;");
    $(".foc").attr("class", "form-line foc focused");
}

//capturamos los datos de la tabla Proveedor en un JSON a través de POST para listarlo
function getProveedor() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/compras/presupuesto_proveedor/listas/listaProveedor.php",
        data: {
            pro_ruc:$("#pro_ruc").val()
        }
        //en base al JSON traído desde el listaProveedor arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{    
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionProveedor("+JSON.stringify(item)+")'>"+item.pro_razonsocial+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulProveedor").html(fila);
        //le damos un estilo a la lista de Proveedor
        $("#listaProveedor").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el proveedor por su key y enviamos el dato al input correspondiente
function seleccionProveedor (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulProveedor").html();
    $("#listaProveedor").attr("style", "display:none;");
    $(".focus").attr("class", "form-line focus focused");
}