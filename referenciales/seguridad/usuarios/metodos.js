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

let getUsuCod = () => {
    $.ajax({
        method: "POST",
        url: "controlador.php",
        data: {consulCod: 1}
    }).done(function (respuesta){
        $("#usu_cod").val(respuesta.codigo);
    });
}

//funcion agregar
let agregar = () => {
    $("#operacion").val(1);
    $("#transaccion").val('INSERCION');
    $(".disabledno").removeAttr("disabled");
    $(".focus").attr("class", "form-line focus focused");
    $("#usu_login, #usu_contrasena, #per_cod, #per_nrodoc, #fun_cod, #funcionarios, #perf_cod, #perf_descri, #mod_cod, #mod_descri").val("");
    $("#usu_estado").val('ACTIVO');
    $(".tbl").attr("style", "display:none");
    getUsuCod();
    habilitarBotones(true);
    window.scroll(0, -100);
};

//funcion modificar
let modificar = () => {
    $("#operacion").val(2);
    $("#usu_estado").val('ACTIVO');
    $("#transaccion").val('MODIFICACION');
    $("#usu_contrasena").val('');
    $(".disabledno").removeAttr("disabled");
    habilitarBotones(true);
    window.scroll(0, -100);

};

//funcion eliminar
let eliminar = () => {
    $("#operacion").val(3);
    $("#transaccion").val('BORRADO');
    $("#usu_estado").val('INACTIVO');
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
            usu_cod: $("#usu_cod").val(),
            usu_login: $("#usu_login").val(),
            usu_contrasena: $("#usu_contrasena").val(),
            perf_cod: $("#perf_cod").val(),
            mod_cod: $("#mod_cod").val(),
            fun_cod: $("#fun_cod").val(),
            usu_estado: $("#usu_estado").val(),
            operacion: $("#operacion").val(),
            usu_cod_reg: $("#usu_cod_reg").val(),
            usu_login_reg: $("#usu_login_reg").val(),
            transaccion: $("#transaccion").val(),
            perf_descri: $("#perf_descri").val(),
            mod_descri: $("#mod_descri").val(),
            funcionarios: $("#funcionarios").val()
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

    if ($("#usu_cod").val() == "") {
        condicion = "i";
    } else if ($("#usu_login").val() == "") {
        condicion = "i";
    } else if ($("#usu_contrasena").val() == "") {
        condicion = "i";
    } else if ($("#per_nrodoc").val() == "") {
        condicion = "i";
    } else if ($("#funcionarios").val() == "") {
        condicion = "i";
    } else if ($("#perf_descri").val() == "") {
        condicion = "i";
    } else if ($("#mod_descri").val() == "") {
        condicion = "i";
    } else if ($("#usu_estado").val() == "") {
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
                        tabla += objeto.usu_cod;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.usu_login;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.usu_fechacrea;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.funcionarios;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.perf_descri;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.mod_descri;
                    tabla += "</td>";
                    tabla += "<td>";
                        tabla += objeto.usu_estado;
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

//capturamos los datos de la tabla perfiles en un JSON a través de POST para listarlo
function getPerfiles() {
    $.ajax({
        method: "POST",
        url: "/SysGym/referenciales/seguridad/usuarios/listas/listaPerfiles.php",
        data: {
            perf_descri:$("#perf_descri").val()
        }
        //en base al JSON traído desde el listaPerfiles arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionPerfiles("+JSON.stringify(item)+")'>"+item.perf_descri+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulPerfiles").html(fila);
        //le damos un estilo a la lista de perfiles
        $("#listaPerfiles").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el perfil por su key y enviamos el dato al input correspondiente
function seleccionPerfiles (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulPerfiles").html();
    $("#listaPerfiles").attr("style", "display:none;");
    $(".focus").attr("class", "form-line focus focused");
}

//capturamos los datos de la tabla modulos en un JSON a través de POST para listarlo
function getModulos() {
    $.ajax({
        method: "POST",
        url: "/SysGym/referenciales/seguridad/usuarios/listas/listaModulos.php",
        data: {
            mod_descri:$("#mod_descri").val()
        }
        //en base al JSON traído desde el listaModulos arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionModulos("+JSON.stringify(item)+")'>"+item.mod_descri+"</li>";
            });
        }
        //enviamos a los input correspondientes del conjunto de filas
        $("#ulModulos").html(fila);
        //le damos un estilo a la lista de modulos
        $("#listaModulos").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el modulo por su key y enviamos el dato al input correspondiente
function seleccionModulos (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulModulos").html();
    $("#listaModulos").attr("style", "display:none;");
    $(".focus").attr("class", "form-line focus focused");
}

//capturamos los datos de la tabla funcionarios en un JSON a través de POST para listarlo
function getFuncionarios() {
    $.ajax({
        method: "POST",
        url: "/SysGym/referenciales/seguridad/usuarios/listas/listaFuncionarios.php",
        data: {
            per_nrodoc:$("#per_nrodoc").val(),
            funcionarios:$("#funcionarios").val()
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
                fila += "<li class='list-group-item' onclick='seleccionFuncionarios("+JSON.stringify(item)+")'>"+item.funcionarios+"</li>";
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