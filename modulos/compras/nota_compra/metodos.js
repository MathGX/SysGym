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
$("#notacom_fecha").val(formatoFecha(ahora));

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
    if (idInput === "#pro_razonsocial") {
        caracteres = /['_¡´°/\!@#$%^&*(),.¿?":{}|<>;~`+]/; //acepta guion
    } else {
        caracteres = /[-'_¡´°/\!@#$%^&*(),.¿?":{}|<>;~`+]/;
    }
    valor = $(idInput).val().trim();
    mensaje = "";
    //si el input no está vacío y contiene letras o caracteres especiales mostramos la alerta
    if ( valor !== "" && caracteres.test(valor)) {
        mensaje = "El campo <b>" + nombreInput + "</b> no acepta caracteres especiales";
        if (idInput === "#pro_razonsocial") {
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

//funcion para limpiar la cabecera
let limpiarCab = () =>{
    $(".tblcab input").each(function(){
        $(this).val('');
    });
    $(".tblcab .body #notacom_fecha").each(function(){
        $(this).val(formatoFecha(ahora));
    });
    $(".tblcab .header .focus").each(function() {
        $(this).attr("class", "form-line focus")
    });
    $(".tblcab .body .focus").each(function() {
        $(this).attr("class","form-line focus" )
    });
}

//funcion para obtener el nuevo codigo
let getCod = () => {
    $.ajax({
        method: "POST",
        url: "controlador.php",
        data: {consulCod: 1}
    }).done(function (respuesta){
        $("#notacom_cod").val(respuesta.codigo);
    });
}

//funcion nuevo
let nuevo = () => {
    limpiarCab();
    $("#operacion_cab").val(1);
    $("#transaccion").val('INSERCION');
    $(".disabledno").removeAttr("disabled");
    $(".focus").attr("class", "form-line focus focused");
    $("#notacom_estado").val('ACTIVO');
    $(".tbl, .tbldet, .nota_remision").attr("style", "display:none");
    getCod();
    habilitarBotones(true);
    datusUsuarios();
    window.scroll(0, -100);
};

//funcion anular
let anular = () => {
    $("#operacion_cab").val(2);
    $("#transaccion").val('ANULACION');
    $("#notacom_estado").val('ANULADO');
    habilitarBotones(true);
    window.scroll(0, -100);
};

//funcion actualizar cuotas de compra
let actCuotas = () => {
    if ($("#com_cod").val() === "0" || $("#com_cod").val() === "") {
        alertaLabel("SELCCIONE UNA NOTA DE DEBITO O CREDITO");
    } else if ($("#tipcomp_cod").val() == 3) {
        alertaLabel("NO SE PUEDEN ACTUALIZAR LAS CUOTAS POR UNA <b> NOTA DE REMISION </b>");
    } else if ($("#grilla_det tr").length < 1) {
        alertaLabel("EL REGISTRO SELECCIONADO NO TIENE DETALLES");
    } else {
        $("#operacion_cab").val(3);
        $(".cant_cuotas").removeAttr("style","").find(".focus").attr("class", "form-line focus focused");
        $("#transaccion").val('MODIFICACION');
        $("#com_cuotas").removeAttr("disabled");
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
            notacom_cod: $("#notacom_cod").val(),
            notacom_fecha: $("#notacom_fecha").val(),
            notacom_nronota: $("#notacom_nronota").val(),
            notacom_concepto: $("#notacom_concepto").val(),
            notacom_estado: $("#notacom_estado").val(),
            com_cod: $("#com_cod").val(),
            suc_cod: $("#suc_cod").val(),
            emp_cod: $("#emp_cod").val(),
            usu_cod: $("#usu_cod").val(),
            tipcomp_cod: $("#tipcomp_cod").val(),
            pro_cod: $("#pro_cod").val(),
            tiprov_cod: $("#tiprov_cod").val(),
            notacom_timbrado: $("#notacom_timbrado").val(),
            notacom_timb_fec_venc: $("#notacom_timb_fec_venc").val(),
            funprov_cod: $("#funprov_cod").val() || 0,
            chapve_cod: $("#chapve_cod").val() || 0,
            operacion_cab: $("#operacion_cab").val(),
            pro_razonsocial: $("#pro_razonsocial").val(),
            usu_login: $("#usu_login").val(),
            suc_descri: $("#suc_descri").val(),
            emp_razonsocial: $("#emp_razonsocial").val(),
            transaccion: $("#transaccion").val(),
            com_cuotas: $("#com_cuotas").val(),
            //datos de para funcionario_proveedor
            funprov_nombres: $("#funprov_nombres").val(),
            funprov_apellidos: $("#funprov_apellidos").val(),
            funprov_nro_doc: $("#funprov_nro_doc").val(),
            //datos para marca_vehiculo
            marcve_cod: $("#marcve_cod").val() || 0,
            marcve_descri: $("#marcve_descri").val(),
            //datos para modelo_vehiculo
            modve_cod: $("#modve_cod").val() || 0,
            modve_descri: $("#modve_descri").val(),
            //datos para chapa_vehiculo
            chapve_chapa: $("#chapve_chapa").val()
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
        
        // Si está dentro de nota_remision y tipcomp_cod=3
        if (dentroNotaRemision.length > 0) {
            if (!dentroNotaRemision.is(':visible') || $('#tipcomp_cod').val() !== '3') return; // no validar si no es visibel
        } 
        // Si está dentro de cant_cuotas 
        if (esCuota.length > 0) {
            if (!esCuota.is(':visible')) return; // no validar si no es visible
        } 
        // Para form-lines fuera de nota_remision y cant_cuotas se verifica siempre
        if (valor === "") {
            let nombreInput = $formLine.find('.form-label').text() || $input.attr('id');
            if (nombreInput !== "C.I. Funcionario") {
                camposVacios.push(nombreInput);
            }
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
    window.scroll(0, -100);
};

//funcion para actualizar libro de compras
// let libro_compras = () => {
//     return new Promise((resolve, reject) => {
//         $.ajax({
//             method: "POST",
//             url: "controladorDetalles.php",
//             data: {
//                 com_cod: $("#com_cod").val(),
//                 operacion_det: $("#operacion_det").val(),
//                 tipimp_cod: $("#tipimp_cod").val(),
//                 tipitem_cod: $("#tipitem_cod").val(),
//                 notacomdet_cantidad: $("#notacomdet_cantidad").val(),
//                 notacomdet_precio: $("#notacomdet_precio").val(),
//                 usu_cod: $("#usu_cod").val(),
//                 usu_login: $("#usu_login").val(),
//                 notacom_nronota: $("#notacom_nronota").val(),
//                 tipcomp_cod: $("#tipcomp_cod").val(),
//                 case: "libro"
//             }
//         });
//         setTimeout(() => {
//             resolve(); // Llama a resolve cuando se complete
//         }, 1000);
//     });
// };

//funcion para actualizar cuentas a pagar
// let cuentas_pagar = () => {
//     return new Promise((resolve, reject) => {
//         $.ajax({
//             method: "POST",
//             url: "controladorDetalles.php",
//             data: {
//                 com_cod: $("#com_cod").val(),
//                 operacion_det: $("#operacion_det").val(),
//                 tipitem_cod: $("#tipitem_cod").val(),
//                 notacomdet_cantidad: $("#notacomdet_cantidad").val(),
//                 notacomdet_precio: $("#notacomdet_precio").val(),
//                 usu_cod: $("#usu_cod").val(),
//                 usu_login: $("#usu_login").val(),
//                 tipcomp_cod: $("#tipcomp_cod").val(),
//                 case: "cuentas"notven_nronota
//             }
//         });
//         setTimeout(() => {
//             resolve(); // Llama a resolve cuando se complete
//         }, 1000);
//     });
// };

/*enviamos por POST a la base de datos los datos cargados los input para grabar un nuevo detalle de inscripción*/
function grabar2() {
    //return new Promise((resolve, reject) => {
        $.ajax({
            method: "POST",
            url: "controladorDetalles.php",
            data: {
                itm_cod: $("#itm_cod").val(),
                tipitem_cod: $("#tipitem_cod").val(),
                notacom_cod: $("#notacom_cod").val(),
                notacomdet_cantidad: $("#notacomdet_cantidad").val(),           
                notacomdet_precio: $("#notacomdet_precio").val(),
                dep_cod: $("#dep_cod").val(),
                suc_cod: $("#suc_cod").val(),
                emp_cod: $("#emp_cod").val(),
                operacion_det: $("#operacion_det").val(),
                com_cod: $("#com_cod").val(),
                usu_cod: $("#usu_cod").val(),
                usu_login: $("#usu_login").val(),
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
                        listar2(); //actualizamos la grilla
                        $(".foc").find(".form-control").val(''); //limpiamos los input
                        $(".foc").attr("class", "form-line foc"); //se bajan los labels quitando el focused
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
        // setTimeout(() => {
        //     resolve(); // Llama a resolve cuando se complete
        // }, 1000);
    //});
};

// async function ejecutarFunciones() {
//     await libro_compras();
//     await cuentas_pagar();
//     await grabar2();
// }

/*funcion para verificar que no se repita el item en el detalle*/
// let aprueba_mov = () => {
//     $.ajax({
//         type: "POST",
//         url: "controladorDetalles.php",
//         data: {
//             tipcomp_cod: $("#tipcomp_cod").val(),
//             itm_cod: $("#itm_cod").val(),
//             notacom_cod: $("#notacom_cod").val(),
//             operacion_det: $("#operacion_det").val(),
//             consulta: "consulItem"
//         }
//     }).done(
//         function (respuesta) {
//             if (respuesta.itm == 1) {
//                 swal({
//                     title: "ERROR!!",
//                     text: "ESTE ITEM YA ESTÁ CARGADO",
//                     type: "error",
//                 })
//             } else if (respuesta.itm == 0) {
//                 if ($("#tipcomp_cod").val() != 3) {
//                     ejecutarFunciones();
//                 } else {
//                     grabar2();
//                 }
//             }
//         }
//     );
// }

/*funcion para verificar que la cantidad de items en el detalle no sea mayor a lo del detalle de compra */
let cantItem = () => {
    $.ajax({
        type: "POST",
        url: "controladorDetalles.php",
        data: {
            itm_cod: $("#itm_cod").val(),
            com_cod: $("#com_cod").val(),
            cantidad: "cantidad"
        }
    }).done(
        function (respuesta) {
            if ($("#tipcomp_cod").val() == 1) {
                if (parseFloat($("#notacomdet_cantidad").val()) > parseFloat(respuesta.cant)) {
                    alertaLabel("LA CANTIDAD SUPERA LO COMPRADO");
                    $("#notacomdet_cantidad").val("");
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
    } else if (($("#tipitem_cod").val() == "1") && $("#notacomdet_cantidad").val() !== "0") {
        alertaLabel("El campo <b>Cantidad</b> debe ser 0 (cero) para los servicios.");
    } else {
        confirmar2();
    }
};

const itemServicio = () => {
    if ($('#tipitem_cod').val() == '1') {
        $('#notacomdet_cantidad').val(0);
        $('#notacomdet_precio').removeAttr('disabled');
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
            notacom_cod: $("#notacom_cod").val(),
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
                    impuesto10 = parseFloat (objeto.notacomdet_precio);
                } else {
                    impuesto10 = parseFloat (objeto.iva10)
                }
                totalExe += parseFloat(objeto.exenta);
                totalI5 += parseFloat(objeto.iva5);
                totalI10 += parseFloat (impuesto10);
                tabla += "<tr onclick='seleccionarFila2(" + JSON.stringify(objeto).replace(/'/g, '&#39;') + ")'>";
                    tabla += "<td>" + objeto.itm_descri + "</td>";
                    tabla += "<td align='right'>" + objeto.notacomdet_cantidad + "</td>";
                    tabla += "<td>" + objeto.uni_descri + "</td>";
                    tabla += "<td align='right'>" + new Intl.NumberFormat('us-US').format(objeto.notacomdet_precio) + "</td>";
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
    window.scroll(0, -100);

    $(".focus").attr("class", "form-line focus focused");
    $(".tbldet").removeAttr("style", "display:none;");
    datusUsuarios();
    listar2();
    //validar si es nota de debito para mostrar el input de deposito y permitir modificar el precio
    if ($("#tipcomp_cod").val() == 2) {
        $(".depo").removeAttr("style", "display:none;");
        $("#notacomdet_precio").removeAttr("readonly");
    } else {
        $(".depo").attr("style", "display:none;");
        $("#notacomdet_precio").attr("readonly","");
    }
    //validar si es nota de remision para evitar modificar la cantidad de items
    if ($("#tipcomp_cod").val() == 3) {
        $("#notacomdet_cantidad").attr("readonly","");
        $(".nota_remision").removeAttr("style");
    } else {
        $("#notacomdet_cantidad").removeAttr("readonly");
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
                    tabla += "<td>" + objeto.notacom_cod + "</td>";
                    tabla += "<td>" + objeto.notacom_fecha2 + "</td>";
                    tabla += "<td>" + objeto.usu_login + "</td>";
                    tabla += "<td>" + objeto.suc_descri + "</td>";
                    tabla += "<td>" + objeto.pro_razonsocial + "</td>";
                    tabla += "<td>" + objeto.com_cod + "</td>";
                    tabla += "<td>" + objeto.com_nrofac + "</td>";
                    tabla += "<td>" + objeto.tipcomp_descri + "</td>";
                    tabla += "<td>" + objeto.notacom_concepto + "</td>";
                    tabla += "<td>" + objeto.notacom_estado + "</td>";
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
        url: "/SysGym/modulos/compras/nota_compra/listas/listaChapa.php",
        data: {
            chapve_chapa:$("#chapve_chapa").val()
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

//_________________________________capturamos los datos de la tabla Compra_cab en un JSON a través de POST para listarlo_________________________________
function getCompra() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/compras/nota_compra/listas/listaCompra.php",
        data: {
            pro_razonsocial:$("#pro_razonsocial").val()
        }
        //en base al JSON traído desde el listaCompra arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{    
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionCompra("+JSON.stringify(item)+")'> Compra n°"+item.com_cod+": "+item.pro_razonsocial+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulCompra").html(fila);
        //le damos un estilo a la lista de Compra
        $("#listaCompra").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el Compra de compra por su key y enviamos el dato al input correspondiente
function seleccionCompra (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulCompra").html();
    $("#listaCompra").attr("style", "display:none;");
    $(".focus").attr("class", "form-line focus focused");
    $(".disa").removeAttr("disabled");
}

//_________________________________capturamos los datos de la tabla Deposito en un JSON a través de POST para listarlo_________________________________
function getDeposito() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/compras/nota_compra/listas/listaDeposito.php",
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

}

//_________________________________capturamos los datos de la tabla funcionario_proveedor en un JSON a través de POST para listarlo_________________________________
function getFunProv() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/compras/nota_compra/listas/listaFunProv.php",
        data: {
            pro_cod:$("#pro_cod").val(),
            funprov_nro_doc:$("#funprov_nro_doc").val()
        }
        //en base al JSON traído desde el listaFunProv arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        let fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{    
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionFunProv("+JSON.stringify(item)+")'>"+item.funcionario+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulFunProv").html(fila);
        //le damos un estilo a la lista de FunProv
        $("#listaFunProv").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el FunProv por su key y enviamos el dato al input correspondiente
function seleccionFunProv (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulFunProv").html();
    $("#listaFunProv").attr("style", "display:none;");
    $(".focus").attr("class", "form-line focus focused");
}

//_________________________________capturamos los datos de la tabla items en un JSON a través de POST para listarlo_________________________________
function getItems() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/compras/nota_compra/listas/listaItems.php",
        data: {
            itm_descri:$("#itm_descri").val(),
            com_cod:$("#com_cod").val(),
            dep_cod:$("#dep_cod").val()||0,
            tipcomp_cod:$("#tipcomp_cod").val()
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
        $("#notacomdet_precio").removeAttr("readonly");
    } else {
        $(".depo").attr("style", "display:none;");
        $("#notacomdet_precio").attr("readonly","");
    }
}

//_________________________________capturamos los datos de la tabla marca_vehiculo en un JSON a través de POST para listarlo_________________________________
function getMarca() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/compras/nota_compra/listas/listaMarca.php",
        data: {
            marcve_descri:$("#marcve_descri").val()
        }
        //en base al JSON traído desde el listaMarca arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        let fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{    
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionMarca("+JSON.stringify(item)+")'>"+item.marcve_descri+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulMarca").html(fila);
        //le damos un estilo a la lista de Marca
        $("#listaMarca").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el Marca por su key y enviamos el dato al input correspondiente
function seleccionMarca (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulMarca").html();
    $("#listaMarca").attr("style", "display:none;");
    $(".focus").attr("class", "form-line focus focused");
}

//_________________________________capturamos los datos de la tabla Modelo_vehiculo en un JSON a través de POST para listarlo_________________________________
function getModelo() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/compras/nota_compra/listas/listaModelo.php",
        data: {
            modve_descri:$("#modve_descri").val()
        }
        //en base al JSON traído desde el listaModelo arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        let fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{    
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionModelo("+JSON.stringify(item)+")'>"+item.marca_modelo+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulModelo").html(fila);
        //le damos un estilo a la lista de Modelo
        $("#listaModelo").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el Modelo por su key y enviamos el dato al input correspondiente
function seleccionModelo (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulModelo").html();
    $("#listaModelo").attr("style", "display:none;");
    $(".focus").attr("class", "form-line focus focused");
}

//_________________________________capturamos los datos de la tabla Nota en un JSON a través de POST para listarlo_________________________________
function getNota() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/compras/nota_compra/listas/listaNota.php",
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
    if ($("#tipcomp_cod").val() == 3) {
        $(".nota_remision").removeAttr("style");
    } else {
        $(".nota_remision").attr("style", "display:none;");
    }
}