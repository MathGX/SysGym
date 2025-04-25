
// document
//   .getElementById("notven_codigo")
//   .addEventListener("keydown", function (e) {
//     e.preventDefault(); // Bloquea edición por teclado
//   });

// // Opcional: Bloquear cambios vía consola
// Object.defineProperty(document.getElementById("notven_codigo"), "value", {
//   writable: false,
// });

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

let formatoFecha = (fecha) => {
    let dia = fecha.getDate();
    let mes = fecha.getMonth() + 1;
    let ano = fecha.getFullYear();

    mes = mes < 10 ? '0' + mes : mes;
    dia = dia < 10 ? '0' + dia : dia;

    return `${dia}/${mes}/${ano}`;
}

let ahora = new Date();
$("#notven_fecha").val(formatoFecha(ahora));

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

//funcion nuevo
let nuevo = () => {
    limpiarCab();
    $("#operacion_cab").val(1);
    $(".disabledno").removeAttr("disabled");
    $(".focus").attr("class", "form-line focus focused");
    $("#notven_cod").val(0);
    $("#notven_estado").val('ACTIVO');
    $(".tbl, .tbldet").attr("style", "display:none");
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
            notven_cod: $("#notven_cod").val(),
            notven_fecha: $("#notven_fecha").val(),
            notven_nronota: $("#notven_nronota").val(),
            notven_concepto: $("#notven_concepto").val(),
            notven_estado: $("#notven_estado").val(),
            tipcomp_cod: $("#tipcomp_cod").val(),
            ven_cod: $("#ven_cod").val(),
            suc_cod: $("#suc_cod").val(),
            emp_cod: $("#emp_cod").val(),
            usu_cod: $("#usu_cod").val(),
            cli_cod: $("#cli_cod").val(),
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
    let condicion = "c";

    if ($("#notven_cod").val() == "") {
        condicion = "i";
    } else if ($("#usu_login").val() == "") {
        condicion = "i";
    } else if ($("#suc_descri").val() == "") {
        condicion = "i";
    } else if ($("#emp_razonsocial").val() == "") {
        condicion = "i";
    } else if ($("#per_nrodoc").val() == "") {
        condicion = "i";
    } else if ($("#ven_cod").val() == "") {
        condicion = "i";
    } else if ($("#ven_nrofac").val() == "") {
        condicion = "i";
    } else if ($("#cli_cod").val() == "") {
        condicion = "i";
    } else if ($("#cliente").val() == "") {
        condicion = "i";
    } else if ($("#tipcomp_cod").val() == "") {
        condicion = "i";
    } else if ($("#notven_nronota").val() == "") {
        condicion = "i";
    } else if ($("#notven_fecha").val() == "") {
        condicion = "i";
    } else if ($("#notven_estado").val() == "") {
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

let reporteNota = () => {
    if ($("#notven_cod").val() == '') {
        swal({
            title: "RESPUESTA!!",
            text: "SELECCIONE UN REGISTRO",
            type: "error",
        });
    } else {
        window.open ("/SysGym/modulos/ventas/nota_venta/reporteNota.php?notven_cod=" + $("#notven_cod").val());
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
    $(".foc").attr("class", "form-line foc focused");
    $(".grilla_det1").attr("style", "display:none");
    habilitarBotones2(true);
    window.scroll(0, -100);
};

//funcion eliminar
let eliminar = () => {
    $("#operacion_det").val(2);
    habilitarBotones2(true);
    window.scroll(0, -100);
};

function notDebito () {
    $.ajax({
        method: "POST",
        url: "ctrlLibCobrDeb.php",
        data: {
            notven_cod: $("#notven_cod").val(),
            ven_cod: $("#ven_cod").val(),
            ven_tipfac: $("#ven_tipfac").val(),
            ven_montocuota: $("#ven_montocuota").val(),
            tipcomp_cod: $("#tipcomp_cod").val(),
            tipimp_cod: $("#tipimp_cod").val(),
            notvendet_cantidad: $("#notvendet_cantidad").val(),
            notvendet_precio: $("#notvendet_precio").val(),
            tipitem_cod: $("#tipitem_cod").val(),
            operacion_cab: $("#operacion_cab").val(),
            operacion_det: $("#operacion_det").val()
        }
    })
}

function notCredito () {
    $.ajax({
        method: "POST",
        url: "ctrlLibCobrCred.php",
        data: {
            notven_cod: $("#notven_cod").val(),
            ven_cod: $("#ven_cod").val(),
            ven_tipfac: $("#ven_tipfac").val(),
            ven_montocuota: $("#ven_montocuota").val(),
            tipcomp_cod: $("#tipcomp_cod").val(),
            tipimp_cod: $("#tipimp_cod").val(),
            notvendet_cantidad: $("#notvendet_cantidad").val(),
            notvendet_precio: $("#notvendet_precio").val(),
            tipitem_cod: $("#tipitem_cod").val(),
            notven_concepto: $("#notven_concepto").val(),
            itm_cod: $("#itm_cod").val(),
            operacion_cab: $("#operacion_cab").val(),
            operacion_det: $("#operacion_det").val()
        }
    })
}

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
            if (respuesta.tipo == "success") {
                if ($("#tipcomp_cod").val() == "1") {
                    notCredito();
                } else if ($("#tipcomp_cod").val() == "2") {
                    notDebito();
                }
            }
            location.reload(true);
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

let exceso = () => {
    $.ajax({
        method: "POST",
        url: "ctrlMonto.php",
        data: {
            tipitem_cod: $("#tipitem_cod").val(),
            ven_cod: $("#ven_cod").val(),
            notvendet_cantidad: $("#notvendet_cantidad").val(),
            notvendet_precio: $("#notvendet_precio").val(),
            operacion_det: $("#operacion_det").val()
        }
    }).done(function (respuesta) {
        if (respuesta.tipo == "error") {
            swal({
                    title: "RESPUESTA!!",
                    text: respuesta.mensaje,
                    type: respuesta.tipo,
                })
        } else  if (respuesta.tipo == "success"){
            grabar2();
        }
    });
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
                exceso();
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

    if ($("#notven_cod").val() == "") {
        condicion = "i";
    } else if ($("#itm_descri").val() == "") {
        condicion = "i";
    } else if ($("#tipitem_descri").val() == "") {
        condicion = "i";
    } else if ($("#notvendet_cantidad").val() == "") {
        condicion = "i";
    } else if ($("#notvendet_precio").val() == "") {
        condicion = "i";
    }

    if (condicion === "i") {
        swal({
            title: "RESPUESTA!!",
            text: "Cargue todos los campos en blanco",
            type: "error",
        });
    } else {
        if (($("#tipitem_cod").val() == "1") && $("#notvendet_cantidad").val() !== "0") {
            swal({
                title: "RESPUESTA!!",
                text: "La cantidad debe ser 0",
                type: "error",
            });
        } else {
            confirmar2();
        }
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
                totalExe += parseFloat(objeto.excenta);
                totalI5 += parseFloat(objeto.iva5);
                totalI10 += parseFloat(impuesto10);
                tabla += "<tr onclick='seleccionarFila2(" + JSON.stringify(objeto).replace(/'/g, '&#39;') + ")'>";
                    tabla += "<td>";
                        tabla += objeto.itm_descri;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.notvendet_cantidad;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.notvendet_precio;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.excenta;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.iva5;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += impuesto10;
                    tabla += "</td>";
                tabla += "</tr>";
            }
            discrIva5 = parseFloat (totalI5/21);
            discrIva10 = parseFloat (totalI10/11);
            totalIva = (discrIva5 + discrIva10);
            totalGral = (totalExe + totalI5 + totalI10);

            let subt = "<th colspan='3' style='font-weight: bold;'>";
                    subt += "SUBTOTAL: ";
                subt += "</th>";
                subt += "<th>";
                    subt += totalExe;
                subt += "</th>";
                subt += "<th>";
                    subt += totalI5;
                subt += "</th>";
                subt += "<th>";
                    subt += totalI10;
                subt += "</th>";

            let tot = "<th colspan='5' style='font-weight: bold;'>";
                    tot += "TOTAL A PAGAR: ";
                tot += "</th>";
                tot += "<th>";
                    tot += totalGral;
                tot += "</th>";

            let imp = "<th colspan='2' style='font-weight: bold;'>";
                    imp += "IVA 5%: "+discrIva5;
                imp += "</th>";
                imp += "<th colspan='2' style='font-weight: bold;'>";
                    imp += "IVA 10%: "+discrIva10;
                imp += "</th>";
                imp += "<th colspan='2' style='font-weight: bold;'>";
                    imp += "TOTAL IVA: "+totalIva;
                imp += "</th>";

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
    listar2();
    //validar si es nota de debito para mostrar el input de deposito
    if ($("#tipcomp_cod").val() == 2) {
        $(".depo").removeAttr("style", "display:none;");
    } else {
        $(".depo").attr("style", "display:none;");
    }
    //validar si es nota de remision para evitar modificar la cantidad de items
    if ($("#tipcomp_cod").val() == 3) {
        $("#notvendet_cantidad").attr("readonly","");
    } else {
        $("#notvendet_cantidad").removeAttr("readonly");
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
                    tabla += "<td>";
                        tabla += objeto.notven_cod;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.notven_fecha;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.usu_login;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.suc_descri;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.notven_nronota;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.cliente;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.ven_cod;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.ven_nrofac;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.tipcomp_descri;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.notven_concepto;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.notven_estado;
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

/*---------------------------------------------------- AUTOCOMPLETADOS ----------------------------------------------------*/


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
    if ($("#tipcomp_cod").val() == 3) {
        $("#notvendet_cantidad").attr("readonly","");
    } else {
        $("#notvendet_cantidad").removeAttr("readonly");
    }
    $("#ulItems").html();
    $("#listaItems").attr("style", "display:none;");
    $(".foc").attr("class", "form-line foc focused");
    itemServicio();
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