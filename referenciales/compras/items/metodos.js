//funcion habilitar inputs
let habilitarBotones = (operacion) => {
    /* Segun el parametro sea verdadero o falso deshabilitamos o habilitamos los botones */
    if (operacion) {
        $(".btnOperacion1").attr("style", "display:none");
        $(".btnOperacion2").attr("style", "display:inline-block");
    } else {
        $(".btnOperacion2").attr("style", "display:none");
        $(".btnOperacion1").attr("style", "display:inline-block");
    }
};

let getCod = () => {
    $.ajax({
        method: "POST",
        url: "controlador.php",
        data: {consulCod: 1}
    }).done(function (respuesta){
        $("#itm_cod").val(respuesta.codigo);
    });
}

//funcion agregar
let agregar = () => {
    $("#operacion").val(1);
    $("#transaccion").val('INSERCION');
    $(".disabledno").removeAttr("disabled");
    $(".focus").attr("class", "form-line focus focused");
    $("#tipitem_cod, #tipitem_descri, #itm_descri, #itm_costo, #itm_precio, #uni_cod, #uni_descri, #tipimp_cod, #tipimp_descri").val("");
    $("#itm_estado").val('ACTIVO');
    $(".tbl").attr("style", "display:none");
    getCod();
    habilitarBotones(true);
    window.scroll(0, -100);
};

//funcion modificar
let modificar = () => {
    $("#operacion").val(2);
    $("#itm_estado").val('ACTIVO');
    $("#transaccion").val('MODIFICACION');
    $(".disabledno").removeAttr("disabled");
    habilitarBotones(true);
    window.scroll(0, -100);
};

//funcion eliminar
let eliminar = () => {
    $("#operacion").val(3);
    $("#transaccion").val('BORRADO');
    $("#itm_estado").val('INACTIVO');
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
            itm_cod: $("#itm_cod").val(),
            tipitem_cod: $("#tipitem_cod").val(),
            itm_descri: $("#itm_descri").val(),
            itm_costo: $("#itm_costo").val(),
            itm_precio: $("#itm_precio").val(),
            uni_cod: $("#uni_cod").val(),
            tipimp_cod: $("#tipimp_cod").val(),
            itm_estado: $("#itm_estado").val(),
            operacion: $("#operacion").val(),
            usu_cod: $("#usu_cod").val(),
            usu_login: $("#usu_login").val(),
            transaccion: $("#transaccion").val(),
            uni_descri: $("#uni_descri").val(),
            tipitem_descri: $("#tipitem_descri").val(),
            tipimp_descri: $("#tipimp_descri").val(),
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
    var oper = $("#operacion").val();

    preg = "¿Desea agregar el registro?";

    /* De acuerdo si la operacion es 2 o 3 modificamos la pregunta */
    if (oper == 2) {
        preg = "¿Desea modificar el registro?";
    }

    if (oper == 3) {
        preg = "¿Desea desactivar el registro?";
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

    if ($("#itm_cod").val() == "") {
        condicion = "i";
    } else if ($("#tipitem_descri").val() == "") {
        condicion = "i";
    } else if ($("#itm_descri").val() == "") {
        condicion = "i";
    } else if ($("#itm_costo").val() == "") {
        condicion = "i";
    } else if ($("#itm_precio").val() == "") {
        condicion = "i";
    } else if ($("#uni_descri").val() == "") {
        condicion = "i";
    } else if ($("#tipimp_descri").val() == "") {
        condicion = "i";
    } else if ($("#itm_estado").val() == "") {
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

//funcion seleccionar Fila
let seleccionarFila = (objetoJSON) => {
    // Enviamos los datos a sus respectivos inputs
    Object.keys(objetoJSON).forEach(function (propiedad) {
        $("#" + propiedad).val(objetoJSON[propiedad]);
    });

    $(".focus").attr("class", "form-line focus focused");
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
                        tabla += objeto.itm_cod;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.tipitem_descri;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.itm_descri;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.uni_descri;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.itm_costo;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.itm_precio;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.tipimp_descri;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.itm_estado;
                    tabla += "</td>";
                tabla += "</tr>";
            }
            $("#grilla_datos").html(tabla);
            formatoTabla();
        })
        .fail(function (a, b, c) {
            swal("ERROR", c, "error");
        });
};

listar();

//capturamos los datos de la tabla TipoItem en un JSON a través de POST para listarlo
function getTipoItem() {
    $.ajax({
        method: "POST",
        url: "/SysGym/referenciales/compras/items/listas/listaTipoItem.php",
        data: {
            tipitem_descri:$("#tipitem_descri").val()
        }
        //en base al JSON traído desde el listaTipoItem arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionTipoItem("+JSON.stringify(item)+")'>"+item.tipitem_descri+"</li>";
            });
        }
        //enviamos a los input correspondientes del conjunto de filas
        $("#ulTipoItem").html(fila);
        //le damos un estilo a la lista de TipoItem
        $("#listaTipoItem").attr("style", "display:block; position:absolute; z-index:3000; width:100%");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el tipo de item por su key y enviamos el dato al input correspondiente
function seleccionTipoItem (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulTipoItem").html();
    $("#listaTipoItem").attr("style", "display:none;");
    $(".focus").attr("class", "form-line focus focused");
}

//capturamos los datos de la tabla TipoImpuesto en un JSON a través de POST para listarlo
function getTipoImpuesto() {
    $.ajax({
        method: "POST",
        url: "/SysGym/referenciales/compras/items/listas/listaTipoImpuesto.php",
        data: {
            tipimp_descri:$("#tipimp_descri").val()
        }
        //en base al JSON traído desde el listaTipoImpuesto arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionTipoImpuesto("+JSON.stringify(item)+")'>"+item.tipimp_descri+"</li>";
            });
        }
        //enviamos a los input correspondientes del conjunto de filas
        $("#ulTipoImpuesto").html(fila);
        //le damos un estilo a la lista de TipoImpuesto
        $("#listaTipoImpuesto").attr("style", "display:block; position:absolute; z-index:3000; width:100%");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el modulo por su key y enviamos el dato al input correspondiente
function seleccionTipoImpuesto (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulTipoImpuesto").html();
    $("#listaTipoImpuesto").attr("style", "display:none;");
    $(".focus").attr("class", "form-line focus focused");
}

//capturamos los datos de la tabla unidad de medida en un JSON a través de POST para listarlo
function getUniMedida() {
    $.ajax({
        method: "POST",
        url: "/SysGym/referenciales/compras/items/listas/listaUniMedida.php",
        data: {
            uni_descri:$("#uni_descri").val()
        }
        //en base al JSON traído desde el listaUniMedida arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionUniMedida("+JSON.stringify(item)+")'>"+item.uni_descri+"</li>";
            });
        }
        //enviamos a los input correspondientes del conjunto de filas
        $("#ulUniMedida").html(fila);
        //le damos un estilo a la lista de UniMedida
        $("#listaUniMedida").attr("style", "display:block; position:absolute; z-index:3000; width:100%");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el modulo por su key y enviamos el dato al input correspondiente
function seleccionUniMedida (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulUniMedida").html();
    $("#listaUniMedida").attr("style", "display:none;");
    $(".focus").attr("class", "form-line focus focused");
}