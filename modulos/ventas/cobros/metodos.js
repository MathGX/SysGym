
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
            $("#apcier_cod").val(datos.apcier_cod);
            $("#caj_cod").val(datos.caj_cod);
            $("#caj_descri").val(datos.caj_descri);
        });
};

//funcion para obtener la fecha y hora actual
let formatoFecha = (fecha) => {
    let dia = fecha.getDate();
    let mes = fecha.getMonth() + 1;
    let ano = fecha.getFullYear();
    let horas = fecha.getHours();
    let minutos = fecha.getMinutes();
    let segundos = fecha.getSeconds();

    mes = mes < 10 ? '0' + mes : mes;
    dia = dia < 10 ? '0' + dia : dia;

    horas = horas < 10 ? '0' + horas : horas;
    minutos = minutos < 10 ? '0' + minutos : minutos;
    segundos = segundos < 10 ? '0' + segundos : segundos;

    return `${ano}-${mes}-${dia} ${horas}:${minutos}:${segundos}`;
}
let ahora = new Date();

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
    if (idInput === "#per_nrodoc") {
        caracteres = /['_¡´°/\!@#$%^&*(),.¿?":{}|<>;~`+]/; //acepta guion
    } else {
        caracteres = /[-'_¡´°/\!@#$%^&*(),.¿?":{}|<>;~`+]/;
    }
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
    if (["#ent_razonsocial_tarj", "#ent_razonsocial"].includes(idInput)) {
        caracteres = /[-'_¡!°/\@#$%^&*(),¿?":{}|<>;~`+]/; //acepta punto
    } else{
        caracteres = /[-'_¡!°/\@#$%^&*(),.¿?":{}|<>;~`+]/;
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

//funcion para obtener el numero de comprobante
let getNroComprob = () => {
    $.ajax({
        method: "POST",
        url: "controlador.php",
        data: {
            consulNroComprob: 1,
            tipcomp_cod: $("#tipcomp_cod").val()
        }
    }).done(function (respuesta){
        if (respuesta.disponibles < 0) {
            alertaLabel("SE ALCANZÓ EL LÍMETE DE RECIBOS HABLITADOS, VERFIQUE POR FAVOR");
        } else {
            $("#cobr_nrorec").val(respuesta.comprobante);
        }
    });
}

let getCod = () => {
    $.ajax({
        method: "POST",
        url: "controlador.php",
        data: {consulCod: 1}
    }).done(function (respuesta){
        $("#cobr_cod").val(respuesta.codigo);
        getNroComprob();
    });
}

//funcion para controlar que nro de cuota se va abonar
let cuotaNro = () => {
    $.ajax({
        method: "POST",
        url: "ctrlNroCuota.php",
        data: {
            operacion_cab: $("#operacion_cab" ).val(),
            ven_cod: $("#ven_cod").val(),
            cobr_cod: $( "#cobr_cod" ).val(),
            case: "1"
        }
    }).done(function (respuesta){
        $("#cobr_nrocuota").val(respuesta.cobr_nrocuota);
    });
}

//funcion para limpiar los campos de la cabecera
let limpiarCab = () =>{
    $(".tblcab input").each(function(){
        $(this).val('');
    });
    $(".tblcab .body #cobr_fecha").each(function(){
        $(this).val(formatoFecha(ahora));
    });
    $(".tblcab .header .focus").each(function() {
        $(this).attr("class", "form-line focus")
    });
    $(".tblcab .body .focus").each(function() {
        $(this).attr("class","form-line focus" )
    });
}

//funcion nuevo
let nuevo = () => {
    limpiarCab();
    $("#operacion_cab").val(1);
    $("#tipcomp_cod").val(5);
    $(".disabledno").removeAttr("disabled");
    $(".focus").attr("class", "form-line focus focused");
    $("#cobr_estado").val('ACTIVO');
    $(".tbldet, .tblgrcab").attr("style", "display:none");
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
            cobr_cod: $("#cobr_cod").val(),
            cobr_fecha: $("#cobr_fecha").val(),
            cobr_nrocuota: $("#cobr_nrocuota").val(),
            cobr_estado: $("#cobr_estado").val(),
            caj_cod: $("#caj_cod").val(),
            suc_cod: $("#suc_cod").val(),
            emp_cod: $("#emp_cod").val(),
            usu_cod: $("#usu_cod").val(),
            apcier_cod: $("#apcier_cod").val(),
            tipcomp_cod: $("#tipcomp_cod").val(),
            ven_cod: $("#ven_cod").val(),
            cobr_nrorec: $("#cobr_nrorec").val(),
            operacion_cab: $("#operacion_cab").val()
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

    /* De acuerdo si la operacion es 2 */
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

let reporteCobro = () => {
    if ($("#cobr_cod").val() == '') {
        swal({
            title: "RESPUESTA!!",
            text: "SELECCIONE UN REGISTRO",
            type: "error",
        });
    } else {
        window.open ("/SysGym/modulos/ventas/cobros/reporteCobro.php?cobr_cod=" + $("#cobr_cod").val());
    }
}

let enviarDoc = () => {
    if ($("#cobr_cod").val() == '') {
        swal({
            title: "RESPUESTA!!",
            text: "SELECCIONE UN REGISTRO",
            type: "error",
        });
    } else {
        $.ajax({
            method: "POST",
            url: "/SysGym/others/mail/envioReciboVenta.php",
            data: { 
                cobr_cod: $("#cobr_cod").val()
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

const getCodDet = () => {
    $.ajax({
        method: "GET",
        url: "controlaCheqTarj.php"
    }).done(function (respuesta){
        $("#cobrdet_cod").val(respuesta.cobrdet_cod);
    });
}

let limpiarDet = () =>{
    $(".tbldet input").each(function(){
        $(this).val('');
    });
    $(".tbldet .foc").each(function() {
        $(this).attr("class","form-line foc" )
    });
    $(".tbltarj input").each(function() {
        $(this).val("")
    });
    $(".tblcheq input").each(function() {
        $(this).val("")
    });
}

//funcion para validar si se puede agregar o eliminar un detalle
let validarDetalle = () => {
    return $.ajax({
        method: "POST",
        url: "controladorDetalles.php",
        data: {
            validacion_det: 1,
            ven_cod: $("#ven_cod").val(),
            cobr_nrocuota: $("#cobr_nrocuota").val(),
        }
    });
}

//funcion agregar
let agregar = () => {
    validarDetalle().done(function(respuesta) {
        if (respuesta.validar == 1) {
            alertaLabel("NO SE PUEDEN AGREGAR DETALLES PORQUE EXISTE UN COBRO DE UNA CUOTA POSTERIOR");
            return;
        }
        limpiarDet();
        $("#operacion_det").val(1);
        //$("#cobrtarj_monto, #cobrcheq_monto, #cobrdet_monto, #cobrcheq_cod, #cobrtarj_cod").val(0);
        $(".disabledno2").removeAttr("disabled");
        $(".foc").attr("class", "form-line foc focused");
        $(".abono, .tbltarj, .tblcheq").attr("style", "display:none");
        $(".formaDeCobro .btn").removeAttr("disabled");
        habilitarBotones2(true);
        getCodDet();
    });
};

//funcion eliminar
let eliminar = () => {
    validarDetalle().done(function(respuesta) {
        if (respuesta.validar == 1) {
            alertaLabel("NO SE PUEDEN ELIMINAR DETALLES PORQUE EXISTE UN COBRO DE UNA CUOTA POSTERIOR");
            return;
        }
        $("#operacion_det").val(2);
        habilitarBotones2(true);
    });
};

//funcion para calcular la fecha de vencimiento de un cheque
let vencCheque = () => {
    // se obtiene la fecha de emision
    let fechaEmision = new Date($("#cobrcheq_fecha_emi").val());

    // suma 30 días
    fechaEmision.setDate(fechaEmision.getDate() + 30); 
    
    // Convertir a formato YYYY-MM-DD para volver a asignar al input
    let vencimiento = fechaEmision.toISOString().split("T")[0];

    // asignar el valor al input de vencimiento
    $("#cobrcheq_fechaven").val(vencimiento);
}

/*enviamos por POST a la base de datos los datos cargados los input para grabar un nuevo detalle de cobros si se abona en cheque*/
function grabarCheque() {
    $.ajax({
        method: "POST",
        url: "controlaCheqTarj.php",
        data: {
            cobrcheq_cod: $("#cobrcheq_cod").val() || 0,
            cobrcheq_num: $("#cobrcheq_num").val(),
            cobrcheq_monto: $("#cobrcheq_monto").val(),
            cobrcheq_tipcheq: $("#cobrcheq_tipcheq").val(),
            cobrcheq_fecha_emi: $("#cobrcheq_fecha_emi").val(),
            cobrcheq_fechaven: $("#cobrcheq_fechaven").val(),
            cobr_cod: $("#cobr_cod").val(),
            cobrdet_cod: $("#cobrdet_cod").val(),
            ent_cod: $("#ent_cod").val(),
            operacion_det: $("#operacion_det").val(),
            forcob_cod: $("#forcob_cod").val()
        }
})};

/*enviamos por POST a la base de datos los datos cargados los input para grabar un nuevo detalle de cobros si se abona en cheque*/
function grabarTarjeta() {
    $.ajax({
        method: "POST",
        url: "controlaCheqTarj.php",
        data: {
            cobrtarj_cod: $("#cobrtarj_cod").val() || 0,
            cobrtarj_transaccion: $("#cobrtarj_transaccion").val(),
            cobrtarj_monto: $("#cobrtarj_monto").val(),
            cobrtarj_tiptarj: $("#cobrtarj_tiptarj").val(),
            cobr_cod: $("#cobr_cod").val(),
            cobrdet_cod: $("#cobrdet_cod").val(),
            martarj_cod: $("#martarj_cod").val(),
            ent_cod: $("#ent_cod_tarj").val(),
            entahd_cod: $("#entahd_cod").val(),
            redpag_cod: $("#redpag_cod").val(),
            forcob_cod: $("#forcob_cod").val(),
            operacion_det: $("#operacion_det").val()
        }
})};

//funcion para controlar cuanto ya se pagó
function ctrlMontoCuota() {
    $.ajax({
        method: "POST",
        url: "ctrlNroCuota.php",
        data: {
            cobr_cod: $("#cobr_cod").val(),
            ven_montocuota: $("#ven_montocuota").val(),
            case: "2"
        }
    }).done(function(respuesta) {
        if (respuesta.pagado_tot === "S") {
            window.location.reload();
        }
    });
};

/*enviamos por POST a la base de datos los datos cargados los input para grabar un nuevo detalle de cobros*/
function grabar2() {
    $.ajax({
        method: "POST",
        url: "controladorDetalles.php",
        data: {
            cobr_cod: $("#cobr_cod").val(),
            forcob_cod: $("#forcob_cod").val(),
            cobrdet_cod: $("#cobrdet_cod").val(),
            cobrdet_monto: $("#cobrdet_monto").val() || $("#cobrtarj_monto").val() || $("#cobrcheq_monto").val(),
            operacion_det: $("#operacion_det").val(),
            ven_cod: $("#ven_cod").val(),
            cobrcheq_num: $("#cobrcheq_num").val() || "0",
            ent_cod: $("#ent_cod").val() || 0,
            usu_cod: $("#usu_cod").val(),
            cobrtarj_transaccion: $("#cobrtarj_transaccion").val() || "0",
            redpag_cod: $("#redpag_cod").val() || 0,
            //saldo para el mensje
            pendiente: $("#pendiente").val() || "0",
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
                if  ($("#operacion_det").val() == "1"){
                    if ($("#forcob_cod").val() == "1") {
                        grabarCheque();
                    } else if ($("#forcob_cod").val() == "3"){
                        grabarTarjeta();
                    }
                }
                listar2(); //actualizamos la grilla
                getForcob(); // se verifcan los medios de pago que se pueden aceptar
                $(".foc").find(".form-control").val(''); //limpiamos los input
                $(".foc").attr("class", "form-line foc"); //
                $(".disabledno2").attr("disabled", "disabled"); //deshabilitamos los input
                $(".abono, .tbltarj, .tblcheq").attr("style", "display:none"); // se ocultan los formularios de cobro
                habilitarBotones2(false); //deshabilitamos los botones
                ctrlMontoCuota(); // se verifica si se completó la cuota
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
                // if ($("#operacion_det").val() == '1') {
                //     ctrlMontoCuota();
                // } else {
                    grabar2();
                //}
            } else {
                //Si cancelamos la operacion realizamos un reload
                cancelar();
            }
        }
    );
};

//funcion control vacio
let controlVacio2 = () => {
    let camposVacios = [];

    // Seleccionar los contenedores visibles que correspondan a la forma de cobro activa
    let secciones = [".tbldet"];
    if ($(".tbltarj").is(":visible")) secciones.push(".tbltarj");
    if ($(".tblcheq").is(":visible")) secciones.push(".tblcheq");
    if ($(".abono").is(":visible")) secciones.push(".abono"); // solo monto

    // Recorre todos los inputs visibles y habilitados dentro de las secciones activas
    $(secciones.join(", ")).find(".foc .form-control:enabled:visible").each(function () {
        if ($(this).val().trim() === "") {
            let nombreInput = $(this).closest(".form-line").find(".form-label").text() || this.id;
            camposVacios.push(nombreInput);
        }
    });

    // Mostrar alerta si hay vacíos, o confirmar si todo está completo
    if (camposVacios.length > 0) {
        alertaLabel("Complete los siguientes campos: <b>" +camposVacios.join(", ") +"</b>.");
    } else if (($("#forcob_cod").val().trim() === "0") || $("#forcob_cod").val().trim() === "") {
        alertaLabel("Debe seleccionar una <b>Forma de Cobro</b> y completar los campos de la misma");
    } else {
        confirmar2();
    }
};

//funcion para seleccionar la forma de cobro y mostrar los botones y formularios correspondientes
let setForcobCod = (cod) => {
    $("#forcob_cod").val(cod);

    // Define qué elementos mostrar según el código
    const mostrar = {
        1: [".tblcheq"], //cheque
        2: [".abono"], //efectivo
        3: [".tbltarj"] //tarjeta
    };

    // Todos los formularios de cobro  que pueden estar
    const formCobro = [".abono", ".tblcheq", ".tbltarj"];

    // Se recorren la constante con los formualrios de cobros
    formCobro.forEach(grupo => {
        if (mostrar[cod].includes(grupo)) {
            $(grupo).show();
        } else {
            $(grupo).hide();
            // Vacía inputs solo de los formularios de cobros ocultos
            $(`${grupo} input`).val("");
        }
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
    switch ($("#forcob_cod").val()) {
        case "1":
            $(".tblcheq").attr("style", "");
            $(".abono, .tbltarj").attr("style", "display:none;");
            break;
        case "3":
            $(".tbltarj").attr("style", "");
            $(".abono, .tblcheq").attr("style", "display:none;");
            break;
        default:
            $(".abono").attr("style", "");
            $(".tblcheq, .tbltarj").attr("style", "display:none;");
            break;
    }
};

//funcion listar
let listar2 = () => {
    $.ajax({
        method: "POST",
        url: "controladorDetalles.php",
        data: {
            cobr_cod: $("#cobr_cod").val(),
        }
    }).done(function (respuesta) {
            let tabla = "";
            for (objeto of respuesta) {;
                tabla += "<tr onclick='seleccionarFila2(" + JSON.stringify(objeto).replace(/'/g, '&#39;') + ")'>";
                    tabla += "<td>"+ objeto.forcob_descri +"</td>";
                    tabla += "<td style='text-align:right;'>"+ objeto.comprobante +"</td>";
                    tabla += "<td style='text-align:right;'>"+ new Intl.NumberFormat('us-US').format(objeto.cobrdet_monto)  +"</td>";
                tabla += "</tr>";
            }
            $("#grilla_det").html(tabla);
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
    window.scroll(0, -100);

    $(".focus").attr("class", "form-line focus focused");
    $(".tbldet").removeAttr("style", "display:none;");
    datusUsuarios();
    listar2();
    getForcob();
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
                    tabla += "<td>"+ objeto.cobr_cod +"</td>";
                    tabla += "<td>"+ objeto.cobr_fecha2 +"</td>";
                    tabla += "<td>"+ objeto.usu_login +"</td>";
                    tabla += "<td>"+ objeto.suc_descri +"</td>";
                    tabla += "<td>"+ objeto.caj_descri +"</td>";
                    tabla += "<td style='text-align:right;'>"+ objeto.ven_cod +"</td>";
                    tabla += "<td>"+ objeto.ven_nrofac +"</td>";
                    tabla += "<td>"+ objeto.cliente +"</td>";
                    tabla += "<td style='text-align:right;'>"+ objeto.cobr_nrocuota +"</td>";
                    tabla += "<td>"+ objeto.cobr_estado +"</td>";
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

//capturamos los datos de la tabla entidad_adherida en un JSON a través de POST para listarlo
function getEntAd() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/ventas/cobros/listas/listaEntAd.php",
        data: {
            ent_razonsocial_tarj:$("#ent_razonsocial_tarj").val()
        }
        //en base al JSON traído desde el listaEntAd arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        let fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>";
        }else{
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionEntAd("+JSON.stringify(item)+")'>"+item.ent_razonsocial_tarj+" - "+item.martarj_descri+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulEntAd").html(fila);
        //le damos un estilo a la lista de Entidad Adherida
        $("#listaEntAd").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el item por su key y enviamos el dato al input correspondiente
function seleccionEntAd (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulEntAd").html();
    $("#listaEntAd").attr("style", "display:none;");
    $(".foc").attr("class", "form-line foc focused");
}

//capturamos los datos de la tabla Entidad emisora en un JSON a través de POST para listarlo
function getEntidad() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/ventas/cobros/listas/listaEntidad.php",
        data: {
            ent_razonsocial:$("#ent_razonsocial").val()
        }
        //en base al JSON traído desde el listaEntidad arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        let fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>";
        }else{
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionEntidad("+JSON.stringify(item)+")'>"+item.ent_razonsocial+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulEntidad").html(fila);
        //le damos un estilo a la lista de Entidad
        $("#listaEntidad").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el Entidad por su key y enviamos el dato al input correspondiente
function seleccionEntidad (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulEntidad").html();
    $("#listaEntidad").attr("style", "display:none;");
    $(".foc").attr("class", "form-line foc focused");

}

//capturamos los datos de la tabla forma_cobro en un JSON a través de POST para listarlo
function getForcob() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/ventas/cobros/listas/listaForcob.php",
        cache: false,
        data: {
            ven_cod: $("#ven_cod").val() || 0,
            cobr_cod: $("#cobr_cod").val() || 0
        }
    }).done(function(lista) {
        if (lista.true === true) {
            // Muestra un mensaje de error si no hay datos
            swal("ERROR", lista.fila, "error");
        } else {
            // Limpia todos los botones primero
            $(".icon-button-demo button").hide();
            // Muestra los botones según el valor de forcob_cod
            $(".formaDeCobro .btn").attr("style", "display:none; border-radius:20px;").attr("disabled", "disabled");
            $.each(lista, function(i, item) {
                if (item.forcob_cod == '1') {
                    $("#btnCheque").show().attr("onclick", "setForcobCod("+item.forcob_cod+")");
                } 
                if (item.forcob_cod == '2') {
                    $("#btnEfectivo").show().attr("onclick", "setForcobCod("+item.forcob_cod+")");
                }
                if (item.forcob_cod == '3') {
                    $("#btnTarjeta").show().attr("onclick", "setForcobCod("+item.forcob_cod+")");
                }
            });
        }
    }).fail(function(a, b, c) {
        swal("ERROR", c, "error");
    }); 
}

//capturamos los datos de la tabla red_pago emisora en un JSON a través de POST para listarlo
function getRedPago() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/ventas/cobros/listas/listaRedPago.php",
        data: {
            redpag_descri:$("#redpag_descri").val()
        }
        //en base al JSON traído desde el listaRedPago arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        let fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>";
        }else{
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionRedPago("+JSON.stringify(item)+")'>"+item.redpag_descri+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulRedPago").html(fila);
        //le damos un estilo a la lista de RedPago
        $("#listaRedPago").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el RedPago por su key y enviamos el dato al input correspondiente
function seleccionRedPago (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulRedPago").html();
    $("#listaRedPago").attr("style", "display:none;");
    $(".foc").attr("class", "form-line foc focused");

}

//funcion para seleccionar TipCheq
function getTipCheq () {
    $.ajax({
        //Enviamos datos para poder filtrar
        method: "POST",
        url: "/SysGym/modulos/ventas/cobros/listas/listaTipCheq.php",
        data: {
            cobrcheq_tipcheq: $("#cobrcheq_tipcheq").val()
        }
    }) //Cargamos la lista
        .done(function (lista) {
            let fila = "";
            //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
            if(lista.true == true){
                fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
            } else {
                //recorremos el array de objetos
                $.each(lista, function (i, objeto) {
                    fila +=
                        "<li class='list-group-item' onclick='seleccionTipCheq(" + JSON.stringify(objeto) + ")'>" + objeto.cobrcheq_tipcheq + "</li>";
                });
            }
            //cargamos la lista
            $("#ulTipCheq").html(fila);
            //hacemos visible la lista
            $("#listaTipCheq").attr("style", "display: block; position:absolute; z-index:3000; width:100%;");
        })
        .fail(function (a, b, c) {
            swal("ERROR", c, "error");
        });
};

//funcion selecccionar TipCheq
function seleccionTipCheq (datos) {
    //Enviamos los datos a su respectivo input
    Object.keys(datos).forEach((key) => {
        $("#" + key).val(datos[key]);
    });
    /* Vaciamos y ocultamos la lista */
    $("#ulTipCheq").html();
    $("#listaTipCheq").attr("style", "display: none;");
    $(".foc").attr("class", "form-line foc focused");
};

//funcion para seleccionar TipTarj
function getTipTarj () {
    $.ajax({
        //Enviamos datos para poder filtrar
        method: "POST",
        url: "/SysGym/modulos/ventas/cobros/listas/listaTipTarj.php",
        data: {
            cobrtarj_tiptarj: $("#cobrtarj_tiptarj").val()
        }
    }) //Cargamos la lista
        .done(function (lista) {
            let fila = "";
            //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
            if(lista.true == true){
                fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
            } else {
                //recorremos el array de objetos
                $.each(lista, function (i, objeto) {
                    fila +=
                        "<li class='list-group-item' onclick='seleccionTipTarj(" + JSON.stringify(objeto) + ")'>" + objeto.cobrtarj_tiptarj + "</li>";
                });
            }
            //cargamos la lista
            $("#ulTipTarj").html(fila);
            //hacemos visible la lista
            $("#listaTipTarj").attr("style", "display: block; position:absolute; z-index:3000; width:100%;");
        })
        .fail(function (a, b, c) {
            swal("ERROR", c, "error");
        });
};

//funcion selecccionar TipTarj
function seleccionTipTarj (datos) {
    //Enviamos los datos a su respectivo input
    Object.keys(datos).forEach((key) => {
        $("#" + key).val(datos[key]);
    });
    /* Vaciamos y ocultamos la lista */
    $("#ulTipTarj").html();
    $("#listaTipTarj").attr("style", "display: none;");
    $(".foc").attr("class", "form-line foc focused");
};

//capturamos los datos de la tabla ventas cabecera en un JSON a través de POST para listarlo
function getVentas() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/ventas/cobros/listas/listaVentas.php",
        data: {
            per_nrodoc: $("#per_nrodoc").val(),
            ven_cod:$("#ven_cod").val()
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
        //le damos un estilo a la lista de ventas
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
    cuotaNro();
}