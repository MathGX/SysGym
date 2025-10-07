
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
            $("#caj_cod").val(datos.caj_cod);
            $("#perf_cod").val(datos.perf_cod);
        });
};

const seleccionVenta = () => {
    window.location = "../index.php";
}

//funcion para obtener la fecha actual
let formatoFecha = (fecha) => {
    let dia = fecha.getDate();
    let mes = fecha.getMonth() + 1;
    let ano = fecha.getFullYear();

    mes = mes < 10 ? '0' + mes : mes;
    dia = dia < 10 ? '0' + dia : dia;

    return `${ano}-${mes}-${dia}`;
}
let ahora = new Date();
$("#ven_fecha").val(formatoFecha(ahora));

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
$(".disabledno, .disabledno2, .disa").each(function() {
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
    caracteres = /[-'_¡´°/\!@#$%^&*(),.¿?":{}|<>;~`+]/;
    letras = /[a-zA-Z]/;
    valor = $(idInput).val().trim();
    mensaje = "";
    //si el input no está vacío y contiene letras o caracteres especiales mostramos la alerta
    if ( valor !== "" && (caracteres.test(valor) || letras.test(valor))) {
        mensaje = "El campo <b>" + nombreInput + "</b> solo puede aceptar valores numéricos";
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
    if (idInput === "#cliente") {
        caracteres = /['_¡´°/\!@#$%^&*(),.¿?":{}|<>;~`+]/; //acepta guion
    } else if (idInput === "#ven_intefecha"){
        caracteres = /[-'_¡´°\!@#$%^&*(),.¿?":{}|<>;~`+]/; // acepta barra
    } else {
        caracteres = /[-'_¡´°/\!@#$%^&*(),.¿?":{}|<>;~`+]/;
    }
    valor = $(idInput).val().trim();
    mensaje = "";
    //si el input no está vacío y contiene letras o caracteres especiales mostramos la alerta
    if ( valor !== "" && caracteres.test(valor)) {
        mensaje = "El campo <b>" + nombreInput + "</b> no acepta caracteres especiales ";
        if (idInput === "#cliente") {
            mensaje += "a parte del guión"; // concatena la cadena extra
        }
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

//funcion para alertar campos que solo acepten texto
let soloTexto = (nombreInput, idInput) => {
    caracteres = /[-'_¡!°/\@#$%^&*(),.¿?":{}|<>;~`+]/;
    numeros = /[0-9]/;
    valor = $(idInput).val().trim();
    mensaje = "";
    //si el input no está vacío y contiene números o caracteres especiales mostramos la alerta
    if (valor !== "" && (caracteres.test(valor) || numeros.test(valor))) {
        mensaje = "El campo <b>" + nombreInput + "</b> solo puede aceptar texto.";
        alertaLabel(mensaje);
        $(idInput).val("");
    }
}

//ejecución del método soloTexto al perder el foco de los inputs con clase .soloTxt
$(".soloTxt").each(function() {
    $(this).on("keyup", function() {
        let idInput = "#" + $(this).attr("id"); //capturamos el id del input que perdió el foco
        let nombreInput = $(this).closest('.form-line').find('.form-label').text(); //capturamos el texto de la etiqueta label asociada al input
        soloTexto(nombreInput, idInput); //llamamos a la función pasarle el nombre del input y su id
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

//funcion para limpiar los campos de la cabecera
let limpiarCab = () =>{
    $(".tblcab input").each(function(){
        $(this).val('');
    });
    $(".tblcab .body #ven_fecha").each(function(){
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
        $("#ven_cod").val(respuesta.codigo);
        getFactura();
    });
}

//funcion para obtener el numeo de comprobante
let getFactura = () => {
    $.ajax({
        method: "POST",
        url: "controlador.php",
        data: {
            consulFactura: 1,
            suc_cod: $("#suc_cod").val(),
            emp_cod: $("#emp_cod").val(),
            caj_cod: $("#caj_cod").val(),
            perf_cod: $("#perf_cod").val(),
            tipcomp_cod: $("#tipcomp_cod").val()
        }
    }).done(function (respuesta){
        if (respuesta.disponibles < 0) {
            alertaLabel("EL TIMBRADO ALCANZÓ EL LÍMETE DE COMPROBANTES HABLITADOS, VERFIQUE POR FAVOR");
        } else {
            $("#ven_nrofac").val(respuesta.factura);
            $("#ven_timbrado").val(respuesta.tim_nro);
            $("#ven_timb_fec_venc").val(respuesta.tim_fec_venc);
        }
    });
}

//funcion nuevo
let nuevo = () => {
    limpiarCab();
    datusUsuarios();
    $("#operacion_cab").val(2);
    $("#tipcomp_cod").val(4);
    $(".disabledno").removeAttr("disabled");
    $(".focus").attr("class", "form-line focus focused");
    $("#ven_cuotas").val(1);
    $("#ven_intefecha").val('S/I');
    $("#ven_estado").val('ACTIVO');
    $(".tbl, .tbldet").attr("style", "display:none");
    getCod();
    habilitarBotones(true);
    window.scroll(0, -100);
};

//anular anular
let anular = () => {
    $("#operacion_cab").val(4);
    $("#ven_estado").val('ANULADO');
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
            ven_cod: $("#ven_cod").val().trim(),
            ven_fecha: $("#ven_fecha").val().trim(),
            ven_nrofac: $("#ven_nrofac").val().trim(),
            ven_tipfac: $("#ven_tipfac").val().trim(),
            ven_cuotas: $("#ven_cuotas").val().trim(),
            ven_montocuota: $("#ven_montocuota").val().trim(),
            ven_intefecha: $("#ven_intefecha").val().trim(),
            ven_estado: $("#ven_estado").val().trim(),
            cli_cod: $("#cli_cod").val().trim(),
            usu_cod: $("#usu_cod").val().trim(),
            suc_cod: $("#suc_cod").val().trim(),
            emp_cod: $("#emp_cod").val().trim(),
            ven_timbrado: $("#ven_timbrado").val().trim(),
            tipcomp_cod: $("#tipcomp_cod").val().trim(),
            ven_timb_fec_venc: $("#ven_timb_fec_venc").val().trim(),
            //pedven_cod: $("#pedven_cod").val().trim(),
            prpr_cod: $("#prpr_cod").val().trim() || 0,
            operacion_cab: $("#operacion_cab").val().trim(),
            caj_cod: $("#caj_cod").val().trim(),
            //datos extras para determinar la caja
            perf_cod: $("#perf_cod").val().trim(),
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
    let oper = $("#operacion_cab").val();

    preg = "¿Desea agregar el registro?";

    /* De acuerdo si la operacion es 2 o 3 modificamos la pregunta */
    if (oper == 3) {
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
                grabar();
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
        alertaLabel("Complete los siguientes campos: <b>" + camposVacios.join(", ") + "</b>.");
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




/*--------------------------------------------METODOS DEL DETALLE---------------------------------------------------------*/

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
            ven_cod: $("#ven_cod").val(),
        }
    });
}

//funcion agregar
let agregar = () => {
    validarDetalle().done(function(respuesta) {
        if (respuesta.validar == 1) {
            alertaLabel("NO SE PUEDEN AGREGAR MAS ITEMS, LA VENTA SE ENCUENTRA ASOCIADA A "+respuesta.comp);
            return;
        }
        $("#operacion_det").val(1);
        $(".disabledno2").removeAttr("disabled");
        $(".foc").find(".form-control").val('');
        $(".foc").attr("class", "form-line foc focused");
        habilitarBotones2(true);
    });
};

//funcion eliminar
let eliminar = () => {
    validarDetalle().done(function(respuesta) {
        if (respuesta.validar == 1) {
            alertaLabel("NO SE PUEDEN ELIMINAR ITEMS, LA COMPRA SE ENCUENTRA ASOCIADA A "+respuesta.comp);
            return;
        }
        $("#operacion_det").val(2);
        habilitarBotones2(true);
    });
};

/*
let libro_ventas = () => {
    $.ajax({
        method: "POST",
        url: "controladorDetalles.php",
        data: {
            ven_cod: $("#ven_cod").val(),
            ven_nrofac: $("#ven_nrofac").val(),
            operacion_det: $("#operacion_det").val(),
            tipimp_cod: $("#tipimp_cod").val(),
            tipitem_cod: $("#tipitem_cod").val(),
            vendet_cantidad: $("#vendet_cantidad").val(),
            vendet_precio: $("#vendet_precio").val(),
            tipcomp_cod: $("#tipcomp_cod").val(),
            case: "libro"
        }
    })
}

let cuentas_cobrar = () => {
    $.ajax({
        method: "POST",
        url: "controladorDetalles.php",
        data: {
            ven_cod: $("#ven_cod").val(),
            operacion_det: $("#operacion_det").val(),
            tipitem_cod: $("#tipitem_cod").val(),
            vendet_cantidad: $("#vendet_cantidad").val(),
            vendet_precio: $("#vendet_precio").val(),
            tipcomp_cod: $("#tipcomp_cod").val(),
            case: "cuentas"
        }
    })
}
*/

/*enviamos por POST a la base de datos los datos cargados los input para grabar un nuevo detalle de inscripción*/
function grabar2() {
    $.ajax({
        method: "POST",
        url: "controladorDetalles.php",
        data: {
            ven_cod: $("#ven_cod").val(),
            itm_cod: $("#itm_cod").val(),
            tipitem_cod: $("#tipitem_cod").val(),
            dep_cod: $("#dep_cod").val(),
            suc_cod: $("#suc_cod").val(),
            emp_cod: $("#emp_cod").val(),
            vendet_cantidad: $("#vendet_cantidad").val(),
            vendet_precio: $("#vendet_precio").val(),
            operacion_det: $("#operacion_det").val(),
            case: "detalle"
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
                /*libro_ventas();
                cuentas_cobrar();*/
                listar2(); //actualizamos la grilla
                $(".foc").find(".form-control").val(''); //limpiamos los input
                $(".foc").attr("class", "form-line foc"); //
                $(".disabledno2").attr("disabled", "disabled"); //deshabilitamos los input
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
    let oper = $("#operacion_det").val();

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
        alertaLabel("Complete los siguientes campos: <b>" + camposVacios.join(", ") + "</b>.");
    } else {
        confirmar2();
    }
};

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
            ven_cod: $("#ven_cod").val(),
        }
    }).done(function (respuesta) {
            let tabla = "";
            let totalExe = 0;
            let totalI5 = 0;
            let totalI10 = 0;
            let discrIva5 = 0;
            let discrIva10 = 0;
            let totalIva = 0;
            let totalGral = 0;
            for (objeto of respuesta) {
                totalExe += parseFloat(objeto.exenta);
                totalI5 += parseFloat(objeto.iva5);
                totalI10 += parseFloat (objeto.iva10);
                tabla += "<tr onclick='seleccionarFila2(" + JSON.stringify(objeto).replace(/'/g, '&#39;') + ")'>";
                    tabla += "<td>"+ objeto.itm_descri +"</td>";
                    tabla += "<td style='text-align:right;'>"+ objeto.vendet_cantidad +"</td>";
                    tabla += "<td style='text-align:right;'>" + new Intl.NumberFormat('us-US').format(objeto.vendet_precio) + "</td>";
                    tabla += "<td style='text-align:right;'>" + new Intl.NumberFormat('us-US').format(objeto.exenta) + "</td>";
                    tabla += "<td style='text-align:right;'>" + new Intl.NumberFormat('us-US').format(objeto.iva5) + "</td>";
                    tabla += "<td style='text-align:right;'>" + new Intl.NumberFormat('us-US').format(objeto.iva10) + "</td>";
                tabla += "</tr>";
            }
            discrIva5 = parseFloat (totalI5/21);
            discrIva10 = parseFloat (totalI10/11);
            totalIva = (discrIva5 + discrIva10);
            totalGral = (totalExe + totalI5 + totalI10);

            let subt = "<th colspan='3' style='font-weight: bold;'> SUBTOTAL: </th>";
                subt += "<th style='text-align:right;'>" + new Intl.NumberFormat('us-US').format(totalExe) + "</th>";
                subt += "<th style='text-align:right;'>" + new Intl.NumberFormat('us-US').format(totalI5) + "</th>";
                subt += "<th style='text-align:right;'>" + new Intl.NumberFormat('us-US').format(totalI10) + "</th>";

            let tot = "<th colspan='5' style='font-weight: bold;'> TOTAL A PAGAR: </th>";
                tot += "<th style='text-align:right;'>" + new Intl.NumberFormat('us-US').format(totalGral) + "</th>";

                let imp = "<th style='font-weight: bold;'> IVA 5%: " + new Intl.NumberFormat('us-US').format(discrIva5.toFixed(2)) + "</th>";
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
                    tabla += "<td>"+ objeto.ven_cod +"</td>";
                    tabla += "<td>"+ objeto.ven_fecha2 +"</td>";
                    tabla += "<td>"+ objeto.usu_login +"</td>";
                    tabla += "<td>"+ objeto.suc_descri +"</td>";
                    tabla += "<td>"+ objeto.cliente +"</td>";
                    tabla += "<td>"+ objeto.ven_tipfac +"</td>";
                    tabla += "<td>"+ objeto.ven_nrofac +"</td>";
                    tabla += "<td>"+ objeto.ven_cuotas +" x "+ new Intl.NumberFormat('us-US').format(objeto.ven_montocuota) +"</td>";
                    tabla += "<td>"+ objeto.ven_intefecha +"</td>";
                    tabla += "<td>"+ objeto.ven_estado +"</td>";
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

//capturamos los datos de la tabla items en un JSON a través de POST para listarlo
function getItems() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/ventas/ventas/ventaPresupuesto/listas/listaItems.php",
        data: {
            prpr_cod: $("#prpr_cod").val(),
            itm_descri:$("#itm_descri").val(),
            dep_cod:$("#dep_cod").val(),
            suc_cod:$("#suc_cod").val(),
            emp_cod:$("#emp_cod").val(),
        }
        //en base al JSON traído desde el listaItems arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        let fila = "";
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

//funcion para seleccionar TipFac
function getTipFac (){
    $.ajax({
        //Enviamos datos para poder filtrar
        method: "POST",
        url: "/SysGym/modulos/ventas/ventas/ventaPresupuesto/listas/listaTipFac.php",
        data: {
            ven_tipfac:$("#ven_tipfac").val()
        }
    }) //Cargamos la lista
        .done(function (lista) {
            let fila = "";
            //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
            if(lista.true == true){
                fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
            }else{    
                //recorremos el array de objetos
                $.each(lista, function (i, objeto) {
                    fila +=
                        "<li class='list-group-item' onclick='seleccionTipFac(" + JSON.stringify(objeto) + ")'>" + objeto.ven_tipfac + "</li>";
                });
            }
            //cargamos la lista
            $("#ulTipFac").html(fila);
            //hacemos visible la lista
            $("#listaTipFac").attr("style", "display: block; position:absolute; z-index:3000; width:100%;");
        })
        .fail(function (a, b, c) {
            swal("ERROR", c, "error");
        });
};

//funcion selecccionar TipFac
function seleccionTipFac(datos) {
    //Enviamos los datos a su respectivo input
    Object.keys(datos).forEach((key) => {
        $("#" + key).val(datos[key]);
    });
    /* Vaciamos y ocultamos la lista */
    $("#ulTipFac").html();
    $("#listaTipFac").attr("style", "display: none;");
    $(".focus").attr("class", "form-line focus focused");
};

//capturamos los datos de la tabla presupuesto_prep_cab en un JSON a través de POST para listarlo
function getPresupuesto() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/ventas/ventas/ventaPresupuesto/listas/listaPresupuesto.php",
        data: {
            cliente:$("#cliente").val(),
            suc_cod:$("#suc_cod").val(),
            emp_cod:$("#emp_cod").val()
        }
        //en base al JSON traído desde el listaPresupuesto arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>";
        }else{
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionPresupuesto("+JSON.stringify(item)+")'>"+item.cliente+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulPresupuesto").html(fila);
        //le damos un estilo a la lista de Presupuesto
        $("#listaPresupuesto").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el Presupuesto de preparacion por su key y enviamos el dato al input correspondiente
function seleccionPresupuesto (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulPresupuesto").html();
    $("#listaPresupuesto").attr("style", "display:none;");
    $(".focus").attr("class", "form-line focus focused");
}