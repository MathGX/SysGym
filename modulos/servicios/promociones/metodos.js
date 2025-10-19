
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

//funcion para establecer la fecha actual
let formatoFecha = (fecha) => {
    let dia = fecha.getDate();
    let mes = fecha.getMonth() + 1;
    let ano = fecha.getFullYear();

    mes = mes < 10 ? '0' + mes : mes;
    dia = dia < 10 ? '0' + dia : dia;

    return `${ano}-${mes}-${dia}`;
}
let ahora = new Date();
$("#promdes_fecha").val(formatoFecha(ahora));

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
    if (idInput == "#promdes_valor") {
        caracteres = /[-'_¡´°/\!@#$%^&(),.¿?":{}|<>;~`+]/;// acepta que asterisco (*)
    } else {
        caracteres = /[-'_¡´°/\!@#$%^&*(),.¿?":{}|<>;~`+]/;
    }
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
    if (idInput == "#promdes_descri") {
        caracteres = /[-'_¡´°/\!@#$^&*(),.¿?":{}|<>;~`]/;// acepta que porcentaje (%)
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

//funcion para vaciar los campos de la cabecera
let limpiarCab = () =>{
    $(".tblcab input").each(function(){
        $(this).val('');
    });
    $(".tblcab .body #promdes_fecha").each(function(){
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
        $("#promdes_cod").val(respuesta.codigo);
    });
}

//funcion nuevo
let nuevo = () => {
    limpiarCab();
    $("#operacion_cab").val(1);
    $("#transaccion").val('INSERCION');
    $(".disabledno").removeAttr("disabled");
    $(".focus").attr("class", "form-line focus focused");
    $("#promdes_estado").val('ACTIVO');
    $(".tbl, .tbldet").attr("style", "display:none");
    getCod();
    habilitarBotones(true);
    datusUsuarios();
    window.scroll(0, -100);
};

//anular anular
let anular = () => {
    $("#operacion_cab").val(2);
    $("#transaccion").val('ANULACION');
    $("#promdes_estado").val('ANULADO');
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
            promdes_cod: $("#promdes_cod").val().trim(),
            promdes_fecha: $("#promdes_fecha").val().trim(),
            promdes_valor: $("#promdes_valor").val().trim(),
            promdes_descri: $("#promdes_descri").val().trim(),
            promdes_estado: $("#promdes_estado").val().trim(),
            suc_cod: $("#suc_cod").val().trim(),
            emp_cod: $("#emp_cod").val().trim(),
            usu_cod: $("#usu_cod").val().trim(),
            tiprom_cod: $("#tiprom_cod").val().trim(),
            operacion_cab: $("#operacion_cab").val().trim(),
            suc_descri: $("#suc_descri").val().trim(),
            emp_razonsocial: $("#emp_razonsocial").val().trim(),
            usu_login: $("#usu_login").val().trim(),
            tiprom_descri: $("#tiprom_descri").val().trim(),
            transaccion: $("#transaccion").val().trim()
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
    var oper = $("#operacion_cab").val().trim();

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
    // Obtener todos los ids de los elementos con clase disabledno
    let campos = $(".focus").find('.form-control').map(function() {
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
    $(".foc").find(".form-control").val('');
    $(".disabledno2").removeAttr("disabled");
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

/*enviamos por POST a la base de datos los datos cargados los input para grabar un nuevo detalle de inscripción*/
function grabar2() {
    $.ajax({
        method: "POST",
        url: "controladorDetalles.php",
        data: {
            promdes_cod: $("#promdes_cod").val().trim(),      
            itm_cod: $("#itm_cod").val().trim(),
            tipitem_cod: $("#tipitem_cod").val().trim(),
            promdesdet_cantidad: $("#promdesdet_cantidad").val().trim(),      
            operacion_det: $("#operacion_det").val().trim(),
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
};

//funcion confirmar SweetAlert
let confirmar2 = () => {
    //solicitamos el value del input operacion
    var oper = $("#operacion_det").val().trim();

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
            promdes_cod: $("#promdes_cod").val().trim(),
        }
    }).done(function (respuesta) {
            let tabla = "";
            for (objeto of respuesta) {
                tabla += "<tr onclick='seleccionarFila2(" + JSON.stringify(objeto).replace(/'/g, '&#39;') + ")'>";
                    tabla += "<td>" + objeto.itm_descri + "</td>";
                    tabla += "<td>" + objeto.promdesdet_cantidad + "</td>";
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
    window.scroll(0, -100);

    $(".focus").attr("class", "form-line focus focused");
    $(".tbldet").removeAttr("style", "display:none;");
    listar2();
    datusUsuarios();
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
                    tabla += "<td>" + objeto.promdes_cod + "</td>";
                    tabla += "<td>" + objeto.promdes_fecha2 + "</td>";
                    tabla += "<td>" + objeto.usu_login + "</td>";
                    tabla += "<td>" + objeto.suc_descri + "</td>";
                    tabla += "<td>" + objeto.promocion + "</td>";
                    tabla += "<td>" + objeto.promdes_estado + "</td>";
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

//capturamos los datos de la tabla items en un JSON a través de POST para listarlo
function getItems() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/servicios/promociones/listas/listaItems.php",
        data: {
            itm_descri:$("#itm_descri").val()
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
}

//capturamos los datos de la tabla tipo_promocion en un JSON a través de POST para listarlo
function getTipo() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/servicios/promociones/listas/listaTipo.php",
        data: {
            tiprom_descri:$("#tiprom_descri").val(),
        }
        //en base al JSON traído desde el listaTipo arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{    
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionTipo("+JSON.stringify(item)+")'>"+item.tiprom_descri+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulTipo").html(fila);
        //le damos un estilo a la lista de tipo
        $("#listaTipo").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el item por su key y enviamos el dato al input correspondiente
function seleccionTipo (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulTipo").html();
    $("#listaTipo").attr("style", "display:none;");
    $(".focus").attr("class", "form-line focus focused");
}