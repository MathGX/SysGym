
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

    return `${ano}-${mes}-${dia}`;
}
let ahora = new Date();
$("#alim_fecha").val(formatoFecha(ahora));

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
    caracteres = /[-'_¡´°/\!@#$%^&*()¿?":{}|<>;~`+]/; // acepta punto(.) y coma (,)
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
    caracteres = /[-'_¡´°/\!@#$%^&*(),.¿?":{}|<>;~`+]/;
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

let limpiarCab = () =>{
    $(".tblcab input").each(function(){
        $(this).val('');
    });
    $(".tblcab .body #alim_fecha").each(function(){
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
        $("#alim_cod").val(respuesta.codigo);
    });
}

//funcion nuevo
let nuevo = () => {
    limpiarCab();
    $("#operacion_cab").val(1);
    $("#transaccion").val('INSERCION');
    $(".disabledno").removeAttr("disabled");
    $(".focus").attr("class", "form-line focus focused");
    $("#alim_estado").val('ACTIVO');
    $(".tbldet, .tbl").attr("style", "display:none");
    getCod();
    habilitarBotones(true);
    datusUsuarios();
    window.scroll(0, -100);
};

//anular anular
let anular = () => {
    $("#operacion_cab").val(2);
    $("#transaccion").val('ANULACION');
    $("#alim_estado").val('ANULADO');
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
            alim_cod: $("#alim_cod").val().trim(),
            alim_estado: $("#alim_estado").val().trim(),
            tiplan_cod: $("#tiplan_cod").val().trim(),
            cli_cod: $("#cli_cod").val().trim(),
            fun_cod: $("#fun_cod").val().trim(),
            usu_cod: $("#usu_cod").val().trim(),
            suc_cod: $("#suc_cod").val().trim(),
            emp_cod: $("#emp_cod").val().trim(),
            alim_fecha: $("#alim_fecha").val().trim(),
            prpr_cod: $("#prpr_cod").val().trim(),
            operacion_cab: $("#operacion_cab").val().trim(),
            tiplan_descri: $("#tiplan_descri").val().trim(),
            per_nrodoc: $("#per_nrodoc").val().trim(),
            cliente: $("#cliente").val().trim(),
            funcionario: $("#funcionario").val().trim(),
            usu_login: $("#usu_login").val().trim(),
            suc_descri: $("#suc_descri").val().trim(),
            emp_razonsocial: $("#emp_razonsocial").val().trim(),
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

let docPlanAlim = () => {
    if ($("#alim_cod").val() == '') {
        swal({
            title: "RESPUESTA!!",
            text: "SELECCIONE UN REGISTRO",
            type: "error",
        });
    } else {
        window.open ("/SysGym/modulos/servicios/plan_alimenticio/docPlanAlim.php?alim_cod=" + $("#alim_cod").val());
    }
}

let enviarDoc = () => {
    if ($("#alim_cod").val() == '') {
        swal({
            title: "RESPUESTA!!",
            text: "SELECCIONE UN REGISTRO",
            type: "error",
        });
    } else {
        $.ajax({
            method: "POST",
            url: "/SysGym/others/mail/envioPlanAlim.php",
            data: { 
                alim_cod: $("#alim_cod").val(),
                per_email: $("#per_email").val(),
                cliente: $("#cliente").val(),
            }
        }).done(function (respuesta) {
            swal({
                title: "RESPUESTA!!",
                text: respuesta.mensaje,
                type: respuesta.tipo,
            });
        })
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

let limpiarDet = () =>{
    $(".tbldet input").each(function(){
        $(this).val('');
    });
    $(".tbldet .header .focus").each(function() {
        $(this).attr("class", "form-line focus")
    });
    $(".tbldet .body .focus").each(function() {
        $(this).attr("class","form-line focus" )
    });
}

//funcion agregar
let agregar = () => {
    limpiarDet();
    $("#operacion_det").val(1);
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

/*enviamos por POST a la base de datos los datos cargados los input para grabar un nuevo detalle de alimcripción*/
function grabar2() {
    $.ajax({
        method: "POST",
        url: "controladorDetalles.php",
        data: {
            alim_cod: $("#alim_cod").val().trim(),
            comi_cod: $("#comi_cod").val().trim(),
            alimdet_proteina: $("#alimdet_proteina").val().trim(),
            alimdet_calorias: $("#alimdet_calorias").val().trim(),
            alimdet_carbohidratos: $("#alimdet_carbohidratos").val().trim(),
            dia_cod: $("#dia_cod").val().trim(),           
            hrcom_cod: $("#hrcom_cod").val().trim(),           
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
            listar2(); //actualizamos la grilla
            $(".foc").find(".form-control").val(''); //limpiamos los input
            $(".foc").attr("class", "form-line foc"); //se bajan los labels quitando el focused
            $(".disabledno2").attr("disabled", "disabled"); //deshabilitamos los input
            habilitarBotones2(false); //deshabilitamos los botones
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
            alim_cod: $("#alim_cod").val(),
        }
    }).done(function (respuesta) {
            let tabla = "";
            for (objeto of respuesta) {
                tabla += "<tr onclick='seleccionarFila2(" + JSON.stringify(objeto).replace(/'/g, '&#39;') + ")'>";
                    tabla += "<td>" + objeto.dia_descri + "</td>";
                    tabla += "<td>" + objeto.hrcom_descri + "</td>";
                    tabla += "<td>" + objeto.comi_descri + "</td>";
                    tabla += "<td style='text-align:right;'>" + objeto.alimdet_proteina + "</td>";
                    tabla += "<td style='text-align:right;'>" + objeto.alimdet_calorias + "</td>";
                    tabla += "<td style='text-align:right;'>" + objeto.alimdet_carbohidratos + "</td>";
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
                    tabla += "<td>" + objeto.alim_cod + "</td>";
                    tabla += "<td>" + objeto.alim_fecha2 + "</td>";
                    tabla += "<td>" + objeto.usu_login + "</td>";
                    tabla += "<td>" + objeto.suc_descri + "</td>";
                    tabla += "<td>" + objeto.cliente + "</td>";
                    tabla += "<td>" + objeto.tiplan_descri + "</td>";
                    tabla += "<td>" + objeto.funcionario + "</td>";
                    tabla += "<td>" + objeto.alim_estado + "</td>";
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

//capturamos los datos de la tabla Clientes en un JSON a través de POST para listarlo
function getClientes() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/servicios/plan_alimenticio/listas/listaClientes.php",
        data: {
            cliente:$("#cliente").val()
        }
        //en base al JSON traído desde el listaClientes arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionClientes("+JSON.stringify(item)+")'>"+item.cliente+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulClientes").html(fila);
        //le damos un estilo a la lista de GUI
        $("#listaClientes").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el funcionario por su key y enviamos el dato al input correspondiente
function seleccionClientes (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulClientes").html();
    $("#listaClientes").attr("style", "display:none;");
    $(".focus").attr("class", "form-line focus focused");
}

//capturamos los datos de la tabla Comidas en un JSON a través de POST para listarlo
function getComidas() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/servicios/plan_alimenticio/listas/listaComidas.php",
        data: {
            comi_descri:$("#comi_descri").val()
        }
        //en base al JSON traído desde el listaComidas arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{    
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionComidas("+JSON.stringify(item)+")'>"+item.comi_descri+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulComidas").html(fila);
        //le damos un estilo a la lista de Comidas
        $("#listaComidas").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el item por su key y enviamos el dato al input correspondiente
function seleccionComidas (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });

    $("#ulComidas").html();
    $("#listaComidas").attr("style", "display:none;");
    $(".foc").attr("class", "form-line foc focused");
}

//capturamos los datos de la tabla Dias en un JSON a través de POST para listarlo
function getDias() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/servicios/plan_alimenticio/listas/listaDias.php",
        data: {
            dia_descri:$("#dia_descri").val()
        }
        //en base al JSON traído desde el listaDias arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{    
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionDias("+JSON.stringify(item)+")'>"+item.dia_descri+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulDias").html(fila);
        //le damos un estilo a la lista de Dias
        $("#listaDias").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el item por su key y enviamos el dato al input correspondiente
function seleccionDias (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulDias").html();
    $("#listaDias").attr("style", "display:none;");
    $(".foc").attr("class", "form-line foc focused");
}

//capturamos los datos de la tabla funcionarios en un JSON a través de POST para listarlo
function getFuncionarios() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/servicios/plan_alimenticio/listas/listaFuncionarios.php",
        data: {
            funcionario:$("#funcionario").val()
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
                fila += "<li class='list-group-item' onclick='seleccionFuncionarios("+JSON.stringify(item)+")'>"+item.funcionario+"</li>";
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

//capturamos los datos de la tabla Horario en un JSON a través de POST para listarlo
function getHorario() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/servicios/plan_alimenticio/listas/listaHorario.php",
        data: {
            hrcom_descri:$("#hrcom_descri").val()
        }
        //en base al JSON traído desde el listaHorario arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{    
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionHorario("+JSON.stringify(item)+")'>"+item.hrcom_descri+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulHorario").html(fila);
        //le damos un estilo a la lista de Horario
        $("#listaHorario").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el item por su key y enviamos el dato al input correspondiente
function seleccionHorario (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulHorario").html();
    $("#listaHorario").attr("style", "display:none;");
    $(".foc").attr("class", "form-line foc focused");
}

//capturamos los datos de la tabla TipPlan en un JSON a través de POST para listarlo
function getTipPlan() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/servicios/plan_alimenticio/listas/listaTipPlan.php",
        data: {
            tiplan_descri:$("#tiplan_descri").val()
        }
        //en base al JSON traído desde el listaTipPlan arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{    
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionTipPlan("+JSON.stringify(item)+")'>"+item.tiplan_descri+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulTipPlan").html(fila);
        //le damos un estilo a la lista de TipPlan
        $("#listaTipPlan").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el item por su key y enviamos el dato al input correspondiente
function seleccionTipPlan (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulTipPlan").html();
    $("#listaTipPlan").attr("style", "display:none;");
    $(".focus").attr("class", "form-line focus focused");
}
