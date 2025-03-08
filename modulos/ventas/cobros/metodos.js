
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

let getCobrCod = () => {
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
    getCobrCod();
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
    } else if ($("#usu_login").val() == "") {
        condicion = "i";
    } else if ($("#suc_descri").val() == "") {
        condicion = "i";
    } else if ($("#emp_razonsocial").val() == "") {
        condicion = "i";
    } else if ($("#cobr_fecha").val() == "") {
        condicion = "i";
    } else if ($("#apcier_cod").val() == "") {
        condicion = "i";
    } else if ($("#caj_cod").val() == "") {
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
    if ($("#notven_cod").val() == '') {
        swal({
            title: "RESPUESTA!!",
            text: "SELECCIONE UN REGISTRO",
            type: "error",
        });
    } else {
        window.open ("/SysGym/modulos/ventas/cobros/reporteCobro.php?notven_cod=" + $("#cobr_cod").val());
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

const getCod = () => {
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
    $(".tbl input").each(function(){
        $(this).val('');
    });
    $(".tbl .header .foc").each(function() {
        $(this).attr("class", "form-line foc")
    });
    $(".tbl .body .foc").each(function() {
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
    habilitarBotones2(true);
    getCod();
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
            cobrdet_monto: $("#cobrdet_monto").val(),
            cobrdet_nrocuota: $("#cobrdet_nrocuota").val(),
            forcob_cod: $("#forcob_cod").val(),       
            cobrtarj_num: $("#cobrtarj_num").val(),
            entahd_cod: $("#entahd_cod").val(),       
            cobrcheq_num: $("#cobrcheq_num").val(),
            ent_cod: $("#ent_cod").val(),
            operacion_det: $("#operacion_det").val(),
            cobrcheq_monto: $("#cobrcheq_monto").val(),
            cobrtarj_monto: $("#cobrtarj_monto").val(),
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
            cuencob_montotal: $("#cuencob_montotal").val(),
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
        if ($("#cobrtarj_cod").val() == "") {
            condicion = "i";
        } else if ($("#cobrtarj_num").val() == "") {
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
        }
    } else if ($("#forcob_cod").val() == "1") {
        if ($("#cobrcheq_cod").val() == "") {
            condicion = "i";
        } else if ($("#cobrcheq_num").val() == "") {
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
                    tabla += "<td>";
                        tabla += objeto.ven_cod;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.ven_nrofac;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.cliente;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.cobrdet_nrocuota;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.cobrdet_monto;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.forcob_descri;
                    tabla += "</td>";
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
                    tabla += "<td>";
                        tabla += objeto.cobr_cod;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.cobr_fecha;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.usu_login;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.suc_descri;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.caj_descri;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.cobr_estado;
                    tabla += "</td>";
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

let formaCobro = () => {
    if ($("#forcob_cod").val() == "1") {
        $(".tbl").attr("class", "col-lg-6 tbl");
        $(".tblcheq").attr("style", "display: block");
        $(".tbltarj").attr("style", "display: none");
        $(".abono").attr("style", "display: none");
    } else if ($("#forcob_cod").val() == "3") {
        $(".tbl").attr("class", "col-lg-6 tbl");
        $(".tbltarj").attr("style", "display: block");
        $(".tblcheq").attr("style", "display: none");
        $(".abono").attr("style", "display: none");
    } else {
        $(".tbl").attr("class", "col-lg-12 tbl");
        $(".abono").attr("style", "display: block");
        $(".tbltarj").attr("style", "display: none");
        $(".tblcheq").attr("style", "display: none");
    }
}

/*---------------------------------------------------- AUTOCOMPLETADOS ----------------------------------------------------*/

//capturamos los datos de la tabla entidad_adherida en un JSON a través de POST para listarlo
function getEntAd() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/ventas/cobros/listas/listaEntAd.php",
        data: {
            ent_cod: $("#ent_cod_tarj").val(),
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
            ent_cod:$("#ent_cod").val(),
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
            ven_cod: $("#ven_cod").val(),
            cobr_cod:$("#cobr_cod").val(),
            forcob_cod:$("#forcob_cod").val(),
            forcob_descri:$("#forcob_descri").val()
        }
        //en base al JSON traído desde el listaForcob arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{    
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionForcob("+JSON.stringify(item)+")'>"+item.forcob_descri+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulForcob").html(fila);
        //le damos un estilo a la lista de Forcob
        $("#listaForcob").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos la forma de cobro por su key y enviamos el dato al input correspondiente
function seleccionForcob (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulForcob").html();
    $("#listaForcob").attr("style", "display:none;");
    $(".foc").attr("class", "form-line foc focused");
    formaCobro();
}

//funcion para seleccionar TipCheq
function getTipCheq () {
    $.ajax({
        //Enviamos datos para poder filtrar
        method: "GET",
        url: "/SysGym/modulos/ventas/cobros/listas/listaTipCheq.php",
    }) //Cargamos la lista
        .done(function (lista) {
            let fila = "";
            //recorremos el array de objetos
            $.each(lista, function (i, objeto) {
                fila +=
                    "<li class='list-group-item' onclick='seleccionTipCheq(" + JSON.stringify(objeto) + ")'>" + objeto.cobrcheq_tipcheq + "</li>";
            });
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
        method: "GET",
        url: "/SysGym/modulos/ventas/cobros/listas/listaTipTarj.php",
    }) //Cargamos la lista
        .done(function (lista) {
            let fila = "";
            //recorremos el array de objetos
            $.each(lista, function (i, objeto) {
                fila +=
                    "<li class='list-group-item' onclick='seleccionTipTarj(" + JSON.stringify(objeto) + ")'>" + objeto.cobrtarj_tiptarj + "</li>";
            });
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
    $(".foc").attr("class", "form-line foc focused");
    cuotaNro();
}