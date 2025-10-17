// datos del usuario y empresa
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
            $("#perf_cod").val(datos.perf_cod);
            $("#caj_cod").val(datos.caj_cod);
        });
};

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
$("#notven_fecha").val(formatoFecha(ahora));

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
$(".disabledno, .disabledno2").each(function() {
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
    if (idInput === "#per_nrodoc") {
        caracteres = /['_¡´°/\!@#$%^&*(),.¿?":{}|<>;~`+]/; //acepta guion
    } else {
        caracteres = /[-'_¡´°/\!@#$%^&*(),.¿?":{}|<>;~`+]/;
    }
    valor = $(idInput).val().trim();
    mensaje = "";
    //si el input no está vacío y contiene letras o caracteres especiales mostramos la alerta
    if ( valor !== "" && caracteres.test(valor)) {
        mensaje = "El campo <b>" + nombreInput + "</b> no acepta caracteres especiales";
        if (idInput === "#per_nrodoc") {
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

//funcion para obtener el nuevo codigo
let getCod = () => {
    $.ajax({
        method: "POST",
        url: "controlador.php",
        data: {consulCod: 1}
    }).done(function (respuesta){
        $("#notven_cod").val(respuesta.codigo);
    });
}

//funcion para limpiar la cabecera
let limpiarCab = () =>{
    $(".tblcab input").each(function(){
        $(this).val('');
    });
    $(".tblcab .body #notven_fecha").each(function(){
        $(this).val(formatoFecha(ahora));
    });
    $(".tblcab .header .focus").each(function() {
        $(this).attr("class", "form-line focus")
    });
    $(".tblcab .body .focus").each(function() {
        $(this).attr("class","form-line focus" )
    });
}

//funcion para obtener el numero de comprobante
let getComprobante = () => {
    $.ajax({
        method: "POST",
        url: "controlador.php",
        data: {
            consulComprob: 1,
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
            $("#notven_nronota").val(respuesta.comprobante);
            $("#notven_timbrado").val(respuesta.tim_nro);
            $("#notven_timb_fec_venc").val(respuesta.tim_fec_venc);
        }
    });
}

//funcion nuevo
let nuevo = () => {
    limpiarCab();
    $("#operacion_cab").val(1);
    $(".disabledno").removeAttr("disabled");
    $(".focus").attr("class", "form-line focus focused");
    $("#notven_cod").val(0);
    $("#notven_estado").val('ACTIVO');
    $(".tbl, .tbldet, .nota_remision").attr("style", "display:none");
    getCod();
    habilitarBotones(true);
    datusUsuarios();
    window.scroll(0, -100);
};

//anular anular
let anular = () => {
    $("#operacion_cab").val(2);
    habilitarBotones(true);
    window.scroll(0, -100);
};

//funcion actualizar cuotas de compra
let actCuotas = () => {
    if ($("#ven_cod").val() === "0" || $("#ven_cod").val() === "") {
        alertaLabel("SELCCIONE UNA NOTA DE DEBITO O CREDITO");
    } else if ($("#tipcomp_cod").val() == 3) {
        alertaLabel("NO SE PUEDEN ACTUALIZAR LAS CUOTAS POR UNA <b> NOTA DE REMISION </b>");
    } else if ($("#grilla_det tr").length < 1) {
        alertaLabel("EL REGISTRO SELECCIONADO NO TIENE DETALLES");
    } else {
        $("#operacion_cab").val(3);
        $(".cant_cuotas").removeAttr("style","").find(".focus").attr("class", "form-line focus focused");
        $("#ven_cuotas").removeAttr("disabled");
        habilitarBotones(true);
        window.scroll(0, -100);
    }
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
            notven_cod: $("#notven_cod").val(),
            notven_fecha: $("#notven_fecha").val(),
            notven_timbrado: $("#notven_timbrado").val(),
            notven_nronota: $("#notven_nronota").val(),
            notven_concepto: $("#notven_concepto").val(),
            notven_funcionario: $("#fun_cod").val() || 0,
            notven_chapa_vehi: $("#chapve_cod").val() || 0,
            notven_estado: $("#notven_estado").val(),
            tipcomp_cod: $("#tipcomp_cod").val(),
            ven_cod: $("#ven_cod").val(),
            suc_cod: $("#suc_cod").val(),
            emp_cod: $("#emp_cod").val(),
            usu_cod: $("#usu_cod").val(),
            cli_cod: $("#cli_cod").val(),
            notven_timb_fec_venc: $("#notven_timb_fec_venc").val(),
            operacion_cab: $("#operacion_cab").val(),
            ven_cuotas: $("#ven_cuotas").val(),          
            caj_cod: $("#caj_cod").val(),  
            //datos extra        
            perf_cod: $("#perf_cod").val(),          
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
                grabar();
                if (($('#operacion_cab').val() == '2') && $('#tipcomp_cod').val() == '1') {
                    notCredito();
                } else if (($('#operacion_cab').val() == '2') && $('#tipcomp_cod').val() == '2') {
                    notDebito();
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
    // Obtener todos los inputs .form-control dentro de cualquier .focus
    let camposVacios = [];

    $(".focus .form-control").each(function () {
        let $input = $(this);
        let valor = $input.val().trim();
        let $formLine = $input.closest('.form-line');

        // Caso especial: si el .form-line está dentro de .nota_remision y el tipcomp_cod es 3 (remision)
        let dentroNotaRemision = $formLine.closest('.nota_remision');
        // Caso especial: si el .form-line stá dentro de .cant_cuotas
        let esCuota = $formLine.closest('.cant_cuotas');
        
        // Si está dentro de nota_remision y tipcomp_cod=3 y está vacío
        if (dentroNotaRemision.length > 0) {
            if (!dentroNotaRemision.is(':visible') || $('#tipcomp_cod').val() !== '3') return; // no validar
        } 
        // Si está dentro de cant_cuotas
        if (esCuota.length > 0) {
            if (!esCuota.is(':visible')) return; // no validar
        } 
        // Para form-lines fuera de nota_remision y cant_cuotas se verifica siempre
        if (valor === "") {
            let nombreInput = $formLine.find('.form-label').text() || $input.attr('id');
            camposVacios.push(nombreInput);
        }
    });

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

let reporteNota = () => {
    if ($("#notven_cod").val() == '') {
        alertaLabel("SELECCIONE UN REGISTRO");
    } else {
        window.open ("/SysGym/modulos/ventas/nota_venta/reporteNota.php?notven_cod="+$("#notven_cod").val()+"&tipcomp_cod="+$("#tipcomp_cod").val()+"&tipcomp_descri="+$("#tipcomp_descri").val());
    }
}

let enviarDoc = () => {
    if ($("#notven_cod").val() == '') {
        alertaLabel("SELECCIONE UN REGISTRO");
    } else {
        $.ajax({
            method: "POST",
            url: "/SysGym/others/mail/envioNotaVenta.php",
            data: { 
                notven_cod: $("#notven_cod").val(),
                tipcomp_cod: $("#tipcomp_cod").val(),
                per_email: $("#per_email").val(),
            }
        }).done(function (respuesta) {
            swal({
                title: "RESPUESTA!!",
                text: respuesta.mensaje,
                type: respuesta.tipo,
            });
        })
    }
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

//funcion agregar
let agregar = () => {
    $("#operacion_det").val(1);
    $(".disabledno2").removeAttr("disabled");
    $(".foc").find(".form-control").val('');
    $(".foc").attr("class", "form-line foc focused");
    habilitarBotones2(true);
};

//funcion eliminar
let eliminar = () => {
    $("#operacion_det").val(2);
    habilitarBotones2(true);
};

// function notDebito () {
//     $.ajax({
//         method: "POST",
//         url: "ctrlLibCobrDeb.php",
//         data: {
//             notven_cod: $("#notven_cod").val(),
//             ven_cod: $("#ven_cod").val(),
//             ven_tipfac: $("#ven_tipfac").val(),
//             ven_montocuota: $("#ven_montocuota").val(),
//             tipcomp_cod: $("#tipcomp_cod").val(),
//             tipimp_cod: $("#tipimp_cod").val(),
//             notvendet_cantidad: $("#notvendet_cantidad").val(),
//             notvendet_precio: $("#notvendet_precio").val(),
//             tipitem_cod: $("#tipitem_cod").val(),
//             operacion_cab: $("#operacion_cab").val(),
//             operacion_det: $("#operacion_det").val()
//         }
//     })
// }

// function notCredito () {
//     $.ajax({
//         method: "POST",
//         url: "ctrlLibCobrCred.php",
//         data: {
//             notven_cod: $("#notven_cod").val(),
//             ven_cod: $("#ven_cod").val(),
//             ven_tipfac: $("#ven_tipfac").val(),
//             ven_montocuota: $("#ven_montocuota").val(),
//             tipcomp_cod: $("#tipcomp_cod").val(),
//             tipimp_cod: $("#tipimp_cod").val(),
//             notvendet_cantidad: $("#notvendet_cantidad").val(),
//             notvendet_precio: $("#notvendet_precio").val(),
//             tipitem_cod: $("#tipitem_cod").val(),
//             notven_concepto: $("#notven_concepto").val(),
//             itm_cod: $("#itm_cod").val(),
//             operacion_cab: $("#operacion_cab").val(),
//             operacion_det: $("#operacion_det").val()
//         }
//     })
// }

/*enviamos por POST a la base de datos los datos cargados los input para grabar un nuevo detalle de inscripción*/
function grabar2() {
    $.ajax({
        method: "POST",
        url: "controladorDetalles.php",
        data: {
            itm_cod: $("#itm_cod").val(),
            tipitem_cod: $("#tipitem_cod").val(),
            notven_cod: $("#notven_cod").val(),
            notvendet_cantidad: $("#notvendet_cantidad").val(),           
            notvendet_precio: $("#notvendet_precio").val(),
            dep_cod: $("#dep_cod").val(),
            suc_cod: $("#suc_cod").val(),
            emp_cod: $("#emp_cod").val(),
            operacion_det: $("#operacion_det").val(),
            tipcomp_cod: $("#tipcomp_cod").val(),
            ven_cod: $("#ven_cod").val(),
            libven_nrocomprobante: $("#notven_nronota").val(),
            tipimp_cod: $("#tipimp_cod").val(),
            usu_cod: $("#usu_cod").val(),
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
            if (respuesta.tipo == "success") {
                listar2(); //actualizamos la grilla
                $(".foc").find(".form-control").val(''); //limpiamos los input
                $(".foc").attr("class", "form-line foc"); //se bajan los labels quitando el focused
                $(".disabledno2").attr("disabled", "disabled"); //deshabilitamos los input
                habilitarBotones2(false); //deshabilitamos los botones
            }
        },
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

// let exceso = () => {
//     $.ajax({
//         method: "POST",
//         url: "ctrlMonto.php",
//         data: {
//             tipitem_cod: $("#tipitem_cod").val(),
//             ven_cod: $("#ven_cod").val(),
//             notvendet_cantidad: $("#notvendet_cantidad").val(),
//             notvendet_precio: $("#notvendet_precio").val(),
//             operacion_det: $("#operacion_det").val()
//         }
//     }).done(function (respuesta) {
//         if (respuesta.tipo == "error") {
//             swal({
//                     title: "RESPUESTA!!",
//                     text: respuesta.mensaje,
//                     type: respuesta.tipo,
//                 })
//         } else  if (respuesta.tipo == "success"){
//             grabar2();
//         }
//     });
// }

/*funcion para verificar que la cantidad de items en el detalle no sea mayor a lo del detalle de venta */
let cantItem = () => {
    $.ajax({
        type: "POST",
        url: "controladorDetalles.php",
        data: {
            itm_cod: $("#itm_cod").val(),
            ven_cod: $("#ven_cod").val(),
            cantidad: "cantidad"
        }
    }).done(
        function (respuesta) {
            if ($("#tipcomp_cod").val() == 1) {
                if (parseFloat($("#notvendet_cantidad").val()) > parseFloat(respuesta.cant)) {
                    alertaLabel("LA CANTIDAD SUPERA LO VENDIDO");
                    $("#notvendet_cantidad").val("");
                }
            }
        }
    )
}

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
        alertaLabel("Complete los siguientes campos: <b>" + camposVacios.join(", ") + "</b>.");
    } else if (($("#tipitem_cod").val() == "1") && $("#notvendet_cantidad").val() !== "0") {
        alertaLabel("El campo <b>Cantidad</b> debe ser 0 (cero) para los servicios.");
    } else {
        confirmar2();
    }
};

const itemServicio = () => {
    if ($('#tipitem_cod').val() == '1') {
        $('#notvendet_cantidad').val(0);
        $('#notvendet_precio').removeAttr('disabled');
    }
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
            notven_cod: $("#notven_cod").val(),
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
                    impuesto10 = parseFloat (objeto.notvendet_precio);
                } else {
                    impuesto10 = parseFloat (objeto.iva10)
                }
                totalExe += parseFloat(objeto.exenta);
                totalI5 += parseFloat(objeto.iva5);
                totalI10 += parseFloat(impuesto10);
                tabla += "<tr onclick='seleccionarFila2(" + JSON.stringify(objeto).replace(/'/g, '&#39;') + ")'>";
                    tabla += "<td>" + objeto.itm_descri + "</td>";
                    tabla += "<td align='right'>" + objeto.notvendet_cantidad + "</td>";
                    tabla += "<td>" + objeto.uni_descri + "</td>";
                    tabla += "<td align='right'>" + new Intl.NumberFormat('us-US').format(objeto.notvendet_precio) + "</td>";
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
                imp += "<th colspan='2' style='font-weight: bold;'> TOTAL IVA: "+ new Intl.NumberFormat('us-US').format(totalIva.toFixed(2)) + "</th>";

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
    //validar si es nota de debito para mostrar el input de deposito y permitir modificar el precio
    if ($("#tipcomp_cod").val() == 2) {
        $(".depo").removeAttr("style", "display:none;");
        $("#notvendet_precio").removeAttr("readonly");
    } else {
        $(".depo").attr("style", "display:none;");
        $("#notvendet_precio").attr("readonly","");
    }
    //validar si es nota de remision para evitar modificar la cantidad de items
    if ($("#tipcomp_cod").val() == 3) {
        $("#notvendet_cantidad").attr("readonly","");
        $(".nota_remision").removeAttr("style");
    } else {
        $("#notvendet_cantidad").removeAttr("readonly");
        $(".nota_remision").attr("style", "display:none;");
    }
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
                    tabla += "<td>" + objeto.notven_cod + "</td>";
                    tabla += "<td>" + objeto.notven_fecha2 + "</td>";
                    tabla += "<td>" + objeto.usu_login + "</td>";
                    tabla += "<td>" + objeto.suc_descri + "</td>";
                    tabla += "<td>" + objeto.cliente + "</td>";
                    tabla += "<td>" + objeto.ven_cod + "</td>";
                    tabla += "<td>" + objeto.ven_nrofac + "</td>";
                    tabla += "<td>" + objeto.tipcomp_descri + "</td>";
                    tabla += "<td>" + objeto.notven_concepto + "</td>";
                    tabla += "<td>" + objeto.notven_estado + "</td>";
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

//_________________________________capturamos los datos de la tabla chapa_vehiculo en un JSON a través de POST para listarlo_________________________________
function getChapa() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/ventas/nota_venta/listas/listaChapa.php",
        data: {
            vehiculo:$("#vehiculo").val()
        }
        //en base al JSON traído desde el listaChapa arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        let fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{    
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionChapa("+JSON.stringify(item)+")'>"+item.vehiculo+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulChapa").html(fila);
        //le damos un estilo a la lista de Chapa
        $("#listaChapa").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el Chapa por su key y enviamos el dato al input correspondiente
function seleccionChapa (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulChapa").html();
    $("#listaChapa").attr("style", "display:none;");
    $(".focus").attr("class", "form-line focus focused");
}

//_________________________________capturamos los datos de la tabla Deposito en un JSON a través de POST para listarlo_________________________________
function getDeposito() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/ventas/nota_venta/listas/listaDeposito.php",
        data: {
            dep_descri:$("#dep_descri").val(),
            suc_cod:$("#suc_cod").val(),
            emp_cod:$("#emp_cod").val(),
        }
        //en base al JSON traído desde el listaDeposito arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        let fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{    
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionDeposito("+JSON.stringify(item)+")'>"+item.dep_descri+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulDeposito").html(fila);
        //le damos un estilo a la lista de Deposito
        $("#listaDeposito").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el deposito por su key y enviamos el dato al input correspondiente
function seleccionDeposito (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulDeposito").html();
    $("#listaDeposito").attr("style", "display:none;");
    $(".foc").attr("class", "form-line foc focused");
    $(".disa").removeAttr("disabled");

}

//__________________capturamos los datos de la tabla funcionarios en un JSON a través de POST para listarlo________________________
function getFuncionarios() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/ventas/nota_venta/listas/listaFuncionarios.php",
        data: {
            funcionario:$("#funcionario").val()
        }
        //en base al JSON traído desde el listaFuncionarios arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{  
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionFuncionarios("+JSON.stringify(item)+")'>"+item.funcionario+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulFuncionarios").html(fila);
        //le damos un estilo a la lista de GUI
        $("#listaFuncionarios").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el funcionario por su key y enviamos el dato al input correspondiente
function seleccionFuncionarios (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulFuncionarios").html();
    $("#listaFuncionarios").attr("style", "display:none;");
    $(".focus").attr("class", "form-line focus focused");
}

//________________________________capturamos los datos de la tabla items en un JSON a través de POST para listarlo________________________________
function getItems() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/ventas/nota_venta/listas/listaItems.php",
        data: {
            itm_descri:$("#itm_descri").val(),
            tipcomp_cod: $("#tipcomp_cod").val(),
            ven_cod: $("#ven_cod").val(),
            dep_cod: $("#dep_cod").val(),
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
    itemServicio();
    //validar si es nota de debito para mostrar el input de deposito y permitir modificar el precio
    if ($("#tipcomp_cod").val() == 2) {
        $(".depo").removeAttr("style", "display:none;");
        $("#notvendet_precio").removeAttr("readonly");
    } else {
        $(".depo").attr("style", "display:none;");
        $("#notvendet_precio").attr("readonly","");
    }
}

//________________________________capturamos los datos de la tabla Nota en un JSON a través de POST para listarlo________________________________
function getNota() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/ventas/nota_venta/listas/listaNota.php",
        data: {
            tipcomp_descri:$("#tipcomp_descri").val()
        }
        //en base al JSON traído desde el listaNota arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{    
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionNota("+JSON.stringify(item)+")'>"+item.tipcomp_descri+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulNota").html(fila);
        //le damos un estilo a la lista de Nota
        $("#listaNota").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el item por su key y enviamos el dato al input correspondiente
function seleccionNota (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulNota").html();
    $("#listaNota").attr("style", "display:none;");
    $(".focus").attr("class", "form-line focus focused");
    $("#per_nrodoc").removeAttr("disabled");
    if ($("#tipcomp_cod").val() == 3) {
        $(".nota_remision").removeAttr("style");
    } else {
        $(".nota_remision").attr("style", "display:none;");
    }
    getComprobante();
}

//________________________________capturamos los datos de la tabla venta_cab en un JSON a través de POST para listarlo________________________________
function getVentas() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/ventas/nota_venta/listas/listaVentas.php",
        data: {
            per_nrodoc: $("#per_nrodoc").val()
        }
        //en base al JSON traído desde el listaVentas arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        let fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{    
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionVentas("+JSON.stringify(item)+")'>Venta: "+item.ven_cod+" - Factura: "+item.ven_nrofac+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulVentas").html(fila);
        //le damos un estilo a la lista de Entidad Adherida
        $("#listaVentas").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el item por su key y enviamos el dato al input correspondiente
function seleccionVentas (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulVentas").html();
    $("#listaVentas").attr("style", "display:none;");
    $(".focus").attr("class", "form-line focus focused");
}