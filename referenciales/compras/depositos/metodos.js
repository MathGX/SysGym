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

let getDepCod = () => {
    $.ajax({
        method: "POST",
        url: "controlador.php",
        data: {consulCod: 1}
    }).done(function (respuesta){
        $("#dep_cod").val(respuesta.codigo);
    });
}

//funcion agregar
let agregar = () => {
    $("#operacion").val(1);
    $("#transaccion").val('INSERCION');
    $(".disabledno").removeAttr("disabled");
    $(".focus").attr("class", "form-line focus focused");
    $("#emp_cod, #emp_razonsocial, #suc_cod, #suc_descri, #dep_descri, #ciu_cod, #ciu_descripcion").val("");
    $("#dep_estado").val('ACTIVO');
    $(".tbl").attr("style", "display:none");
    getDepCod();
    habilitarBotones(true);
    window.scroll(0, -100);
};

//funcion modificar
let modificar = () => {
    $("#operacion").val(2);
    $("#dep_estado").val('ACTIVO');
    $("#transaccion").val('MODIFICACION');
    $(".disabledno").removeAttr("disabled");
    habilitarBotones(true);
    window.scroll(0, -100);
};

//funcion eliminar
let eliminar = () => {
    $("#operacion").val(3);
    $("#transaccion").val('BORRADO');
    $("#dep_estado").val('INACTIVO');
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
            dep_cod: $("#dep_cod").val(),
            suc_cod: $("#suc_cod").val(),
            emp_cod: $("#emp_cod").val(),
            ciu_cod: $("#ciu_cod").val(),
            dep_descri: $("#dep_descri").val(),
            dep_estado: $("#dep_estado").val(),
            operacion: $("#operacion").val(),
            usu_cod: $("#usu_cod").val(),
            usu_login: $("#usu_login").val(),
            transaccion: $("#transaccion").val(),
            ciu_descripcion: $("#ciu_descripcion").val(),
            emp_razonsocial: $("#emp_razonsocial").val(),
            suc_descri: $("#suc_descri").val(),
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

    if ($("#dep_cod").val() == "") {
        condicion = "i";
    } else if ($("#suc_descri").val() == "") {
        condicion = "i";
    } else if ($("#emp_razonsocial").val() == "") {
        condicion = "i";
    } else if ($("#dep_descri").val() == "") {
        condicion = "i";
    } else if ($("#ciu_descripcion").val() == "") {
        condicion = "i";
    } else if ($("#dep_estado").val() == "") {
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
                        tabla += objeto.dep_cod;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.emp_razonsocial;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.suc_descri;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.dep_descri;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.ciu_descripcion;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.dep_estado;
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

//capturamos los datos de la tabla sucursales en un JSON a través de POST para listarlo
function getEmpresaSuc() {
    $.ajax({
        method: "POST",
        url: "/SysGym/referenciales/compras/depositos/listas/listaEmpresaSuc.php",
        data: {
            emp_razonsocial:$("#emp_razonsocial").val()
        }
        //en base al JSON traído desde el listaEmpresaSuc arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionEmpresaSuc("+JSON.stringify(item)+")'>"+item.emp_razonsocial+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulEmpresaSuc").html(fila);
        //le damos un estilo a la lista de EmpresaSuc
        $("#listaEmpresaSuc").attr("style", "display:block; position:absolute; z-index:3000; width:100%");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos la empresa por su key y enviamos el dato al input correspondiente
function seleccionEmpresaSuc (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    getSucursalEmp(datos);
    $("#ulEmpresaSuc").html();
    $("#listaEmpresaSuc").attr("style", "display:none;");
    $(".focus").attr("class", "form-line focus focused");
}

//capturamos los datos de la tabla sucursales en un JSON a través de POST para listarlo
function getSucursalEmp() {
    $.ajax({
        method: "POST",
        url: "/SysGym/referenciales/compras/depositos/listas/listaSucursalEmp.php",
        data: {
            emp_razonsocial:$("#emp_razonsocial").val()
        }
        //en base al JSON traído desde el listaSucursalEmp arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        $.each(lista,function(i, item) {
            fila += "<li class='list-group-item' onclick='seleccionSucursalEmp("+JSON.stringify(item)+")'>"+item.suc_descri+"</li>";
        });
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulSucursalEmp").html(fila);
        //le damos un estilo a la lista de SucursalEmp
        $("#listaSucursalEmp").attr("style", "display:block; position:absolute; z-index:3000; width:100%");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos la sucursal por su key y enviamos el dato al input correspondiente
function seleccionSucursalEmp (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulSucursalEmp").html();
    $("#listaSucursalEmp").attr("style", "display:none;");
    $(".focus").attr("class", "form-line focus focused");
}

//capturamos los datos de la tabla Ciudad en un JSON a través de POST para listarlo
function getCiudades() {
    $.ajax({
        method: "POST",
        url: "/SysGym/referenciales/compras/depositos/listas/listaCiudades.php",
        data: {
            ciu_descripcion:$("#ciu_descripcion").val()
        }
        //en base al JSON traído desde el listaCiudades arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionCiudades("+JSON.stringify(item)+")'>"+item.ciu_descripcion+"</li>";
            });
        }
        //enviamos a los input correspondientes del conjunto de filas
        $("#ulCiudades").html(fila);
        //le damos un estilo a la lista de Ciudades
        $("#listaCiudades").attr("style", "display:block; position:absolute; z-index:3000; width:100%");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos la Ciudad por su key y enviamos el dato al input correspondiente
function seleccionCiudades (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulCiudades").html();
    $("#listaCiudades").attr("style", "display:none;");
    $(".focus").attr("class", "form-line focus focused");
}
