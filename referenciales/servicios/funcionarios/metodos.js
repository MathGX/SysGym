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
        $("#fun_cod").val(respuesta.codigo);
    });
}

//funcion agregar
let agregar = () => {
    $("#operacion").val(1);
    $("#transaccion").val('INSERCION');
    $(".disabledno").removeAttr("disabled");
    $(".focus").attr("class", "form-line focus focused");
    $("#fun_fechaingreso, #per_cod, #per_nrodoc, #persona, #ciu_cod, #ciu_descripcion, #car_cod, #car_descri, #emp_cod, #emp_razonsocial, #suc_cod, #suc_descri").val("");
    $("#fun_estado").val('ACTIVO');
    $(".tbl").attr("style", "display:none");
    getCod();
    habilitarBotones(true);
    window.scroll(0, -100);
};

//funcion modificar
let modificar = () => {
    $("#operacion").val(2);
    $("#fun_estado").val('ACTIVO');
    $("#transaccion").val('MODIFICACION');
    $(".disabledno").removeAttr("disabled");
    habilitarBotones(true);
    window.scroll(0, -100);
};

//funcion eliminar
let eliminar = () => {
    $("#operacion").val(3);
    $("#transaccion").val('ANULACION');
    $("#fun_estado").val('INACTIVO');
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
            fun_cod: $("#fun_cod").val(),
            fun_fechaingreso: $("#fun_fechaingreso").val(),
            per_cod: $("#per_cod").val(),
            ciu_cod: $("#ciu_cod").val(),
            car_cod: $("#car_cod").val(),
            suc_cod: $("#suc_cod").val(),
            emp_cod: $("#emp_cod").val(),
            fun_estado: $("#fun_estado").val(),
            operacion: $("#operacion").val(),
            usu_cod: $("#usu_cod").val(),
            usu_login: $("#usu_login").val(),
            transaccion: $("#transaccion").val(),
            persona: $("#persona").val(),
            ciu_descripcion: $("#ciu_descripcion").val(),
            car_descri: $("#car_descri").val(),
            suc_descri: $("#suc_descri").val(),
            emp_razonsocial: $("#emp_razonsocial").val(),
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

    if ($("#fun_cod").val() == "") {
        condicion = "i";
    } else if ($("#fun_fechaingreso").val() == "") {
        condicion = "i";
    } else if ($("#per_nrodoc").val() == "") {
        condicion = "i";
    } else if ($("#persona").val() == "") {
        condicion = "i";
    } else if ($("#ciu_descripcion").val() == "") {
        condicion = "i";
    } else if ($("#car_descri").val() == "") {
        condicion = "i";
    } else if ($("#emp_razonsocial").val() == "") {
        condicion = "i";
    } else if ($("#suc_descri").val() == "") {
        condicion = "i";
    } else if ($("#fun_estado").val() == "") {
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
                        tabla += objeto.fun_cod;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.fun_fechaingreso;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.persona;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.ciu_descripcion;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.car_descri;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.suc_descri;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.emp_razonsocial;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.fun_estado;
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

//capturamos los datos de la tabla Personas en un JSON a través de POST para listarlo
function getPersonas() {
    $.ajax({
        method: "POST",
        url: "/SysGym/referenciales/servicios/funcionarios/listas/listaPersonas.php",
        data: {
            per_nrodoc:$("#per_nrodoc").val()
        }
        //en base al JSON traído desde el listaPersonas arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionPersonas("+JSON.stringify(item)+")'>"+item.persona+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulPersonas").html(fila);
        //le damos un estilo a la lista de Personas
        $("#listaPersonas").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos la Persona por su key y enviamos el dato al input correspondiente
function seleccionPersonas (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulPersonas").html();
    $("#listaPersonas").attr("style", "display:none;");
    $(".focus").attr("class", "form-line focus focused");
}

//capturamos los datos de la tabla Ciudad en un JSON a través de POST para listarlo
function getCiudades() {
    $.ajax({
        method: "POST",
        url: "/SysGym/referenciales/servicios/funcionarios/listas/listaCiudades.php",
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
        $("#listaCiudades").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
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

//capturamos los datos de la tabla Cargos en un JSON a través de POST para listarlo
function getCargos() {
    $.ajax({
        method: "POST",
        url: "/SysGym/referenciales/servicios/funcionarios/listas/listaCargos.php",
        data: {
            car_descri:$("#car_descri").val()
        }
        //en base al JSON traído desde el listaCargos arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionCargos("+JSON.stringify(item)+")'>"+item.car_descri+"</li>";
            });
        }
        //enviamos a los input correspondientes del conjunto de filas
        $("#ulCargos").html(fila);
        //le damos un estilo a la lista de Cargos
        $("#listaCargos").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el cargo por su key y enviamos el dato al input correspondiente
function seleccionCargos (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulCargos").html();
    $("#listaCargos").attr("style", "display:none;");
    $(".focus").attr("class", "form-line focus focused");
}

//capturamos los datos de la tabla sucursales en un JSON a través de POST para listarlo
function getEmpresaSuc() {
    $.ajax({
        method: "POST",
        url: "/SysGym/referenciales/servicios/funcionarios/listas/listaEmpresaSuc.php",
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
        $("#listaEmpresaSuc").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
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
        url: "/SysGym/referenciales/servicios/funcionarios/listas/listaSucursalEmp.php",
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
        $("#listaSucursalEmp").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
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