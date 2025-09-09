
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
$("#notacom_fecha").val(formatoFecha(ahora));

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
    $("#notacom_estado").val('ANULADO');
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
            notacom_cod: $("#notacom_cod").val(),
            notacom_fecha: $("#notacom_fecha").val(),
            notacom_nronota: $("#notacom_nronota").val(),
            notacom_concepto: $("#notacom_concepto").val(),
            notacom_estado: $("#notacom_estado").val(),
            pro_cod: $("#pro_cod").val(),
            tiprov_cod: $("#tiprov_cod").val(),
            suc_cod: $("#suc_cod").val(),
            emp_cod: $("#emp_cod").val(),
            usu_cod: $("#usu_cod").val(),
            com_cod: $("#com_cod").val(),
            tipcomp_cod: $("#tipcomp_cod").val(),
            operacion_cab: $("#operacion_cab").val(),
            pro_razonsocial: $("#pro_razonsocial").val(),
            usu_login: $("#usu_login").val(),
            suc_descri: $("#suc_descri").val(),
            emp_razonsocial: $("#emp_razonsocial").val(),
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

    if ($("#notacom_cod").val() == "") {
        condicion = "i";
    } else if ($("#usu_login").val() == "") {
        condicion = "i";
    } else if ($("#suc_descri").val() == "") {
        condicion = "i";
    } else if ($("#emp_razonsocial").val() == "") {
        condicion = "i";
    } else if ($("#com_nrofac").val() == "") {
        condicion = "i";
    } else if ($("#pro_razonsocial").val() == "") {
        condicion = "i";
    } else if ($("#tipcomp_descri").val() == "") {
        condicion = "i";
    } else if ($("#notacom_nronota").val() == "") {
        condicion = "i";
    } else if ($("#notacom_fecha").val() == "") {
        condicion = "i";
    } else if ($("#notacom_estado").val() == "") {
        condicion = "i";
    }

    if (condicion === "i") {
        swal({
            title: "RESPUESTA!!",
            text: "CARGUE TODOS LOS CAMPOS EN BLANCO",
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
    $(".disabledno").removeAttr("disabled");
    $("#itm_cod, #itm_descri, #tipitem_cod, #tipimp_cod, #dep_cod, #dep_descri, #notacomdet_cantidad, #uni_descri, #notacomdet_precio").val('');
    $(".foc").attr("class", "form-line foc focused");
    habilitarBotones2(true);
    window.scroll(0, -100);
};

//funcion eliminar
let eliminar = () => {
    $("#operacion_det").val(2);
    habilitarBotones2(true);
    window.scroll(0, -100);
};

//funcion para actualizar libro de compras
let libro_compras = () => {
    return new Promise((resolve, reject) => {
        $.ajax({
            method: "POST",
            url: "controladorDetalles.php",
            data: {
                com_cod: $("#com_cod").val(),
                operacion_det: $("#operacion_det").val(),
                tipimp_cod: $("#tipimp_cod").val(),
                tipitem_cod: $("#tipitem_cod").val(),
                notacomdet_cantidad: $("#notacomdet_cantidad").val(),
                notacomdet_precio: $("#notacomdet_precio").val(),
                usu_cod: $("#usu_cod").val(),
                usu_login: $("#usu_login").val(),
                notacom_nronota: $("#notacom_nronota").val(),
                tipcomp_cod: $("#tipcomp_cod").val(),
                case: "libro"
            }
        });
        setTimeout(() => {
            resolve(); // Llama a resolve cuando se complete
        }, 1000);
    });
};

//funcion para actualizar cuentas a pagar
let cuentas_pagar = () => {
    return new Promise((resolve, reject) => {
        $.ajax({
            method: "POST",
            url: "controladorDetalles.php",
            data: {
                com_cod: $("#com_cod").val(),
                operacion_det: $("#operacion_det").val(),
                tipitem_cod: $("#tipitem_cod").val(),
                notacomdet_cantidad: $("#notacomdet_cantidad").val(),
                notacomdet_precio: $("#notacomdet_precio").val(),
                usu_cod: $("#usu_cod").val(),
                usu_login: $("#usu_login").val(),
                tipcomp_cod: $("#tipcomp_cod").val(),
                case: "cuentas"
            }
        });
        setTimeout(() => {
            resolve(); // Llama a resolve cuando se complete
        }, 1000);
    });
};

/*enviamos por POST a la base de datos los datos cargados los input para grabar un nuevo detalle de inscripción*/
function grabar2() {
    return new Promise((resolve, reject) => {
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
        setTimeout(() => {
            resolve(); // Llama a resolve cuando se complete
        }, 1000);
    });
};

async function ejecutarFunciones() {
    await libro_compras();
    await cuentas_pagar();
    await grabar2();
}

/*funcion para verificar que no se repita el item en el detalle*/
let aprueba_mov = () => {
    $.ajax({
        type: "POST",
        url: "controladorDetalles.php",
        data: {
            tipcomp_cod: $("#tipcomp_cod").val(),
            itm_cod: $("#itm_cod").val(),
            notacom_cod: $("#notacom_cod").val(),
            operacion_det: $("#operacion_det").val(),
            consulta: "consulItem"
        }
    }).done(
        function (respuesta) {
            if (respuesta.itm == 1) {
                swal({
                    title: "ERROR!!",
                    text: "ESTE ITEM YA ESTÁ CARGADO",
                    type: "error",
                })
            } else if (respuesta.itm == 0) {
                if ($("#tipcomp_cod").val() != 3) {
                    ejecutarFunciones();
                } else {
                    grabar2();
                }
            }
        }
    );
}

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
                if ( parseFloat($("#notacomdet_cantidad").val()) > parseFloat(respuesta.cant)) {
                    swal({
                        title: "ERROR!!",
                        text: "LA CANTIDAD SUPERA LO COMPRADO",
                        type: "error",
                    }),
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
                aprueba_mov();
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

    if ($("#notacom_cod").val() == "") {
        condicion = "i";
    } else if ($("#itm_descri").val() == "") {
        condicion = "i";
    } else if ($("#tipitem_descri").val() == "") {
        condicion = "i";
    } else if ($("#notacomdet_cantidad").val() == "") {
        condicion = "i";
    } else if ($("#uni_descri").val() == "") {
        condicion = "i";
    } else if ($("#notacomdet_precio").val() == "") {
        condicion = "i";
    }

    if (condicion === "i") {
        swal({
            title: "RESPUESTA!!",
            text: "CARGUE TODOS LOS CAMPOS EN BLANCO",
            type: "error",
        });
    } else {
        if (($("#tipitem_cod").val() == "1") && $("#notacomdet_cantidad").val() !== "0") {
            swal({
                title: "RESPUESTA!!",
                text: "LA CANTIDAD DEBE SER 0",
                type: "error",
            });
        } else {
            confirmar2();
        }
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
                    tabla += "<td>" + objeto.notacomdet_cantidad + "</td>";
                    tabla += "<td>" + objeto.uni_descri + "</td>";
                    tabla += "<td>" + new Intl.NumberFormat('us-US').format(objeto.notacomdet_precio) + "</td>";
                    tabla += "<td>" + new Intl.NumberFormat('us-US').format(objeto.exenta) + "</td>";
                    tabla += "<td>" + new Intl.NumberFormat('us-US').format(objeto.iva5) + "</td>";
                    tabla += "<td>" + new Intl.NumberFormat('us-US').format(impuesto10) + "</td>";
                tabla += "</tr>";
            }
            discrIva5 = parseFloat (totalI5/21);
            discrIva10 = parseFloat (totalI10/11);
            totalIva = (discrIva5 + discrIva10);
            totalGral = (totalExe + totalI5 + totalI10);

            let subt = "<th colspan='4' style='font-weight: bold;'> SUBTOTAL: </th>";
                subt += "<th>" + new Intl.NumberFormat('us-US').format(totalExe) + "</th>";
                subt += "<th>" + new Intl.NumberFormat('us-US').format(totalI5) + "</th>";
                subt += "<th>" + new Intl.NumberFormat('us-US').format(totalI10) + "</th>";

            let tot = "<th colspan='6' style='font-weight: bold;'> TOTAL A PAGAR: </th>";
                tot += "<th>" + new Intl.NumberFormat('us-US').format(totalGral) + "</th>";

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
    //validar si es nota de debito para mostrar el input de deposito
    if ($("#tipcomp_cod").val() == 2) {
        $(".depo").removeAttr("style", "display:none;");
    } else {
        $(".depo").attr("style", "display:none;");
    }
    //validar si es nota de remision para evitar modificar la cantidad de items
    if ($("#tipcomp_cod").val() == 3) {
        $("#notacomdet_cantidad").attr("readonly","");
    } else {
        $("#notacomdet_cantidad").removeAttr("readonly");
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
                    tabla += "<td>" + objeto.notacom_fecha + "</td>";
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
}

//_________________________________funcion para obtener Concepto_________________________________
function getConcepto () {
    $.ajax({
        //Enviamos datos para poder filtrar
        method: "POST",
        url: "/SysGym/modulos/compras/nota_compra/listas/listaConcepto.php",
        data: {
            tipcomp_cod: $("#tipcomp_cod").val()
        }
    }) //Cargamos la lista
        .done(function (lista) {
            let fila = "";
            //recorremos el array de objetos
            $.each(lista, function (i, objeto) {
                fila +=
                    "<li class='list-group-item' onclick='seleccionConcepto(" + JSON.stringify(objeto) + ")'>" + objeto.notacom_concepto + "</li>";
            });
            //cargamos la lista
            $("#ulConcepto").html(fila);
            //hacemos visible la lista
            $("#listaConcepto").attr("style", "display: block; position:absolute; z-index: 3000; width:100%;");
        })
        .fail(function (a, b, c) {
            swal("ERROR", c, "error");
        });
};

//funcion selecccionar Concepto
function seleccionConcepto (datos) {
    //Enviamos los datos a su respectivo input
    Object.keys(datos).forEach((key) => {
        $("#" + key).val(datos[key]);
    });
    /* Vaciamos y ocultamos la lista */
    $("#ulConcepto").html();
    $("#listaConcepto").attr("style", "display: none;");
    $(".focus").attr("class", "form-line focus focused");
};

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
    $(".disa").removeAttr("disabled");

}

//_________________________________capturamos los datos de la tabla items en un JSON a través de POST para listarlo_________________________________
function getItems() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/compras/nota_compra/listas/listaItems.php",
        data: {
            itm_descri:$("#itm_descri").val(),
            com_cod:$("#com_cod").val(),
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
    if ($("#tipcomp_cod").val() == 3) {
        $("#notacomdet_cantidad").attr("readonly","");
    } else {
        $("#notacomdet_cantidad").removeAttr("readonly");
    }
    $("#ulItems").html();
    $("#listaItems").attr("style", "display:none;");
    $(".foc").attr("class", "form-line foc focused");
    itemServicio();
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
    getConcepto(datos.notven_concepto);
}