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
        $("#param_cod").val(respuesta.codigo);
    });
}

//funcion agregar
let agregar = () => {
    $("#operacion").val(1);
    $("#transaccion").val('INSERCION');
    $(".disabledno").removeAttr("disabled");
    $(".focus").attr("class", "form-line focus focused");
    $("#param_descri, #param_formula, #uni_descri, #uni_cod").val("");
    $("#param_estado").val('ACTIVO');
    $(".tbl").attr("style", "display:none");
    getCod();
    habilitarBotones(true);
    window.scroll(0, -100);
};

//funcion modificar
let modificar = () => {
    $("#operacion").val(2);
    $("#param_estado").val('ACTIVO');
    $("#transaccion").val('MODIFICACION');
    $(".disabledno").removeAttr("disabled");
    habilitarBotones(true);
    window.scroll(0, -100);
};

//funcion eliminar
let eliminar = () => {
    $("#operacion").val(3);
    $("#transaccion").val('BORRADO');
    $("#param_estado").val('INACTIVO');
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
            param_cod: $("#param_cod").val(),
            param_descri: $("#param_descri").val(),
            param_estado: $("#param_estado").val(),
            uni_cod: $("#uni_cod").val(),
            param_formula: $("#param_formula").val(),
            operacion: $("#operacion").val(),
            usu_cod: $("#usu_cod").val(),
            usu_login: $("#usu_login").val(),
            uni_descri: $("#uni_descri").val(),
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

    if ($("#param_cod").val() == "") {
        condicion = "i";
    } else if ($("#param_descri").val() == "") {
        condicion = "i";
    } else if ($("#uni_descri").val() == "") {
        condicion = "i";
    } else if ($("#param_estado").val() == "") {
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
                tabla += "<td>" + objeto.param_cod + "</td>";
                tabla += "<td>"+ objeto.param_descri + "</td>";
                tabla += "<td>"+ objeto.uni_simbolo + "</td>";
                tabla += "<td>"+ objeto.param_formula + "</td>";
                tabla += "<td>"+ objeto.param_estado+ "</td>";
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

//capturamos los datos de la tabla unidad de medida en un JSON a través de POST para listarlo
function getUniMedida() {
    $.ajax({
        method: "POST",
        url: "/SysGym/referenciales/servicios/parametros_medicion/listas/listaUniMedida.php",
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