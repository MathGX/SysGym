
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

    return `${dia}/${mes}/${ano} ${horas}:${minutos}:${segundos}`;
}

let ahora = new Date();

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

let getCod = () => {
    $.ajax({
        method: "POST",
        url: "controlador.php",
        data: {consulCod: 1}
    }).done(function (respuesta){
        $("#cobr_cod").val(respuesta.codigo);
    });
}

//funcion nuevo
let nuevo = () => {
    $("#operacion_cab").val(1);
    $(".disabledno").removeAttr("disabled");
    $(".focus").attr("class", "form-line focus focused");
    $("#cobr_estado").val('ACTIVO');
    $(".tbldet, .tblgrcab").attr("style", "display:none");
    $("#cobr_fecha").val(formatoFecha(ahora));
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
            cobr_estado: $("#cobr_estado").val(),
            caj_cod: $("#caj_cod").val(),
            suc_cod: $("#suc_cod").val(),
            emp_cod: $("#emp_cod").val(),
            usu_cod: $("#usu_cod").val(),
            apcier_cod: $("#apcier_cod").val(),
            tipcomp_cod: $("#tipcomp_cod").val(),
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
    let condicion = "c";

    if ($("#cobr_cod").val() == "") {
        condicion = "i";
    } else if ($("#emp_razonsocial").val() == "") {
        condicion = "i";
    } else if ($("#suc_descri").val() == "") {
        condicion = "i";
    } else if ($("#usu_login").val() == "") {
        condicion = "i";
    } else if ($("#cobr_fecha").val() == "") {
        condicion = "i";
    } else if ($("#apcier_cod").val() == "") {
        condicion = "i";
    } else if ($("#caj_descri").val() == "") {
        condicion = "i";
    } else if ($("#cobr_estado").val() == "") {
        condicion = "i";
    }

    if (condicion === "i") {
        swal({
            title: "RESPUESTA!!",
            text: "Cargue todos los campos en blanco",
            type: "error",
        });
    } else {
        confirmar();
    }
};

/*se establece el formato de la grilla*/
function formatoTabla() {
    $(".js-exportable").DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json",
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

const cuotaNro = () => {
    $.ajax({
        method: "POST",
        url: "ctrlNroCuota.php",
        data: {
            operacion_det: $( "#operacion_det" ).val(),
            ven_cod: $("#ven_cod").val(),
            cobr_cod: $( "#cobr_cod" ).val(),
            case: "1"
        }
    }).done(function (respuesta){
        $("#cobrdet_nrocuota").val(respuesta.cobrdet_nrocuota);
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


//funcion agregar
let agregar = () => {
    limpiarDet();
    $("#operacion_det").val(1);
    $("#cobrtarj_monto, #cobrcheq_monto, #cobrdet_monto, #cobrcheq_cod, #cobrtarj_cod").val(0);
    $(".disabledno").removeAttr("disabled");
    $(".foc").attr("class", "form-line foc focused");
    $(".grilla_det1").attr("style", "display:none");
    $(".abono").attr("style", "display:none");
    habilitarBotones2(true);
    getCodDet();
    window.scroll(0, -100);
};

//funcion eliminar
let eliminar = () => {
    $("#operacion_det").val(2);
    habilitarBotones2(true);
    window.scroll(0, -100);
};

/*enviamos por POST a la base de datos los datos cargados los input para grabar un nuevo detalle de cobros si se abona en cheque*/
function grabarCheque() {
    $.ajax({
        method: "POST",
        url: "controlaCheqTarj.php",
        data: {
            cobrcheq_cod: $("#cobrcheq_cod").val(),
            cobrcheq_num: $("#cobrcheq_num").val(),
            cobrcheq_monto: $("#cobrcheq_monto").val(),
            cobrcheq_tipcheq: $("#cobrcheq_tipcheq").val(),
            cobrcheq_fechaven: $("#cobrcheq_fechaven").val(),
            ven_cod: $("#ven_cod").val(),
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
            cobrtarj_cod: $("#cobrtarj_cod").val(),
            cobrtarj_num: $("#cobrtarj_num").val(),
            cobrtarj_monto: $("#cobrtarj_monto").val(),
            cobrtarj_tiptarj: $("#cobrtarj_tiptarj").val(),
            ven_cod: $("#ven_cod").val(),
            cobr_cod: $("#cobr_cod").val(),
            cobrdet_cod: $("#cobrdet_cod").val(),
            martarj_cod: $("#martarj_cod").val(),
            ent_cod: $("#ent_cod_tarj").val(),
            entahd_cod: $("#entahd_cod").val(),
            cobrtarj_transaccion: $("#cobrtarj_transaccion").val(),
            redpag_cod: $("#redpag_cod").val(),
            operacion_det: $("#operacion_det").val(),
            forcob_cod: $("#forcob_cod").val()
        }
})};

/*enviamos por POST a la base de datos los datos cargados los input para grabar un nuevo detalle de cobros*/
function grabar2() {
    $.ajax({
        method: "POST",
        url: "controladorDetalles.php",
        data: {
            ven_cod: $("#ven_cod").val(),
            cobr_cod: $("#cobr_cod").val(),
            cobrdet_cod: $("#cobrdet_cod").val(),
            cobrdet_monto: $("#cobrdet_monto").val() ? $("#cobrdet_monto").val() : 0,
            cobrdet_nrocuota: $("#cobrdet_nrocuota").val(),
            forcob_cod: $("#forcob_cod").val(),
            cobrcheq_num: $("#cobrcheq_num").val() ? $("#cobrcheq_num").val() : "----",
            ent_cod: $("#ent_cod").val() ? $("#ent_cod").val() : 0,
            usu_cod: $("#usu_cod").val(),
            cobrtarj_transaccion: $("#cobrtarj_transaccion").val() ? $("#cobrtarj_transaccion").val() : "-----",
            redpag_cod: $("#redpag_cod").val() ? $("#redpag_cod").val() : 0,
            operacion_det: $("#operacion_det").val(),
            cobrcheq_monto: $("#cobrcheq_monto").val() ? $("#cobrcheq_monto").val() : 0,
            cobrtarj_monto: $("#cobrtarj_monto").val() ? $("#cobrtarj_monto").val() : 0,
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

//funcion para controlar que no se cargue mas de lo que es el monto de la cuota
function ctrlMontoCuota() {
    $.ajax({
        method: "POST",
        url: "ctrlNroCuota.php",
        data: {
            ven_cod: $("#ven_cod").val(),
            cobr_cod: $("#cobr_cod").val(),
            cobrcheq_monto: $("#cobrcheq_monto").val(),
            cobrtarj_monto: $("#cobrtarj_monto").val(),
            cobrdet_monto: $("#cobrdet_monto").val(),
            ven_montocuota: $("#ven_montocuota").val(),
            forcob_cod: $("#forcob_cod").val(),
            cuencob_monto: $("#cuencob_monto").val(),
            operacion_det: $("#operacion_det").val(),
            case: "2"
        }
    }).done(function(respuesta) {
        if (respuesta.tipo == "error") {
            swal(
                {
                    title: "RESPUESTA!!",
                    text: respuesta.mensaje,
                    type: respuesta.tipo,
                }
            )
        } else if (respuesta.tipo == "success") {
            grabar2();
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
                if ($("#operacion_det").val() == '1') {
                    ctrlMontoCuota();
                } else {
                    grabar2();
                }
            } else {
                //Si cancelamos la operacion realizamos un reload
                cancelar();
            }
        }
    );
};

//funcion control vacio
let controlVacio2 = () => {
    let condicion = "c";

    if ($("#per_nrodoc").val() == "") {
        condicion = "i";
    } else if ($("#cuencob_saldo").val() == "") {
        condicion = "i";
    } else if ($("#ven_cuotas").val() == "") {
        condicion = "i";
    } else if ($("#cobrdet_cod").val() == "") {
        condicion = "i";
    } else if ($("#ven_cod").val() == "") {
        condicion = "i";
    } else if ($("#ven_nrofac").val() == "") {
        condicion = "i";
    } else if ($("#cliente").val() == "") {
        condicion = "i";
    } else if ($("#cobrdet_nrocuota").val() == "") {
        condicion = "i";
    } else if ($("#ven_montocuota").val() == "") {
        condicion = "i";
    } else if ($("#ven_intefecha").val() == "") {
        condicion = "i";
    } else if ($("#forcob_cod").val() == "") {
        condicion = "i";
    } else if ($("#forcob_cod").val() == "3") {
        if ($("#cobrtarj_num").val() == "") {
            condicion = "i";
        } else if ($("#cobrtarj_monto").val() == "") {
            condicion = "i";
        } else if ($("#cobrtarj_tiptarj").val() == "") {
            condicion = "i";
        } else if ($("#entahd_cod").val() == "") {
            condicion = "i";
        } else if ($("#ent_cod_tarj").val() == "") {
            condicion = "i";
        } else if ($("#ent_razonsocial_tarj").val() == "") {
            condicion = "i";
        } else if ($("#martarj_cod").val() == "") {
            condicion = "i";
        } else if ($("#martarj_descri").val() == "") {
            condicion = "i";
        } else if ($("#redpag_descri").val() == "") {
            condicion = "i";
        } else if ($("#cobrtarj_transaccion").val() == "") {
            condicion = "i";
        }
    } else if ($("#forcob_cod").val() == "1") {
        if ($("#cobrcheq_num").val() == "") {
            condicion = "i";
        } else if ($("#cobrcheq_monto").val() == "") {
            condicion = "i";
        } else if ($("#cobrcheq_tipcheq").val() == "") {
            condicion = "i";
        } else if ($("#ent_cod").val() == "") {
            condicion = "i";
        } else if ($("#ent_razonsocial").val() == "") {
            condicion = "i";
        } else if ($("#cobrcheq_fechaven").val() == "") {
            condicion = "i";
        }
    } else if ($("#forcob_descri").val() == "") {
        condicion = "i";
    } else if ($("#cobrdet_monto").val() == "") {
        condicion = "i";
    }

    if (condicion === "i") {
        swal({
            title: "RESPUESTA!!",
            text: "Cargue todos los campos en blanco",
            type: "error",
        });
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
    $(".abono").attr("style", "");
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
                    tabla += "<td style='text-align:right;'>"+ objeto.ven_cod +"</td>";
                    tabla += "<td>"+ objeto.ven_nrofac +"</td>";
                    tabla += "<td>"+ objeto.cliente +"</td>";
                    tabla += "<td style='text-align:right;'>"+ objeto.cobrdet_nrocuota +"</td>";
                    tabla += "<td style='text-align:right;'>"+ new Intl.NumberFormat('us-US').format(objeto.cobrdet_monto)  +"</td>";
                    tabla += "<td>"+ objeto.forcob_descri +"</td>";
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

    $(".focus").attr("class", "form-line focus focused");
    $(".tbldet").removeAttr("style", "display:none;");
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
                    tabla += "<td>"+ objeto.cobr_fecha +"</td>";
                    tabla += "<td>"+ objeto.usu_login +"</td>";
                    tabla += "<td>"+ objeto.suc_descri +"</td>";
                    tabla += "<td>"+ objeto.caj_descri +"</td>";
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

//funcion para seleccionar la forma de cobro y mostrar los botones y formularios correspondientes
let setForcobCod = (cod) => {
    $("#forcob_cod").val(cod); // Establece el valor del input hidden según el botón seleccionado
    if (cod == 1) {
        $(".abono").attr("style", "display: none");
        $(".tblcheq").attr("style", "");
        $(".tbltarj").attr("style", "display: none");
        $(".abono input").each(function() {
            $(this).val("")
        });
        $(".tbltarj input").each(function() {
            $(this).val("")
        });
    } else if (cod == 2) {
        $(".abono").attr("style", "");
        $(".tbltarj").attr("style", "display: none");
        $(".tblcheq").attr("style", "display: none");
        $(".tbltarj input").each(function() {
            $(this).val("")
        });
        $(".tblcheq input").each(function() {
            $(this).val("")
        });
    } else if (cod == 3) {
        $(".abono").attr("style", "display: none");
        $(".tblcheq").attr("style", "display: none");
        $(".tbltarj").attr("style", "");
        $(".abono input").each(function() {
            $(this).val("")
        });
        $(".tblcheq input").each(function() {
            $(this).val("")
        });
    }
}


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
        data: {
            ven_cod: $("#ven_cod").val() ? $("#ven_cod").val() : 0,
            cobr_cod: $("#cobr_cod").val() ? $("#cobr_cod").val() : 0
        }
    }).done(function(lista) {
        if (lista.true === true) {
            // Muestra un mensaje de error si no hay datos
            swal("ERROR", lista.fila, "error");
        } else {
            // Limpia todos los botones primero
            $(".icon-button-demo button").hide();
            // Muestra los botones según el valor de forcob_cod
            $.each(lista, function(i, item) {
                $(".formaDeCobro").attr("style", "");
                if (item.forcob_descri == 'CHEQUE') {
                    $("#btnCheque").show().attr("onclick", "setForcobCod("+item.forcob_cod+")");
                } else if (item.forcob_descri == 'EFECTIVO') {
                    $("#btnEfectivo").show().attr("onclick", "setForcobCod("+item.forcob_cod+")");
                } else if (item.forcob_descri == 'TARJETA') {
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
    $(".foc").attr("class", "form-line foc focused");
    cuotaNro();
}