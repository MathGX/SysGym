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
$("#pedcom_fecha").val(formatoFecha(ahora));

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
$(".disabledno").each(function() {
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

//funcion para alertar campos que no acepten caracteres especiales
let sinCaracteres = (nombreInput, idInput) => {
    if (idInput === "#pro_razonsocial") {
        caracteres = /['_¡´°/\!@#$%^&*(),.¿?":{}|<>;~`+]/; 
    } else {
        caracteres = /[-'_¡´°/\!@#$%^&*(),.¿?":{}|<>;~`+]/;
    }
    valor = $(idInput).val().trim();
    mensaje = "";
    //si el input no está vacío y contiene letras o caracteres especiales mostramos la alerta
    if ( valor !== "" && caracteres.test(valor)) {
        mensaje = "El campo <b>" + nombreInput + "</b> no puede aceptar caracteres especiales.",
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

//funcion para alertar campos que solo acepten numeros
let soloNumeros = (nombreInput, idInput) => {
    caracteres = /[-'_¡´°/\!@#$%^&*()¿?":{}|<>;~`+]/;
    letras = /[a-zA-Z]/;
    valor = $(idInput).val().trim();
    mensaje = "";
    //si el input no está vacío y contiene letras o caracteres especiales mostramos la alerta
    if ( valor !== "" && (caracteres.test(valor) || letras.test(valor))) {
        mensaje = "El campo <b>" + nombreInput + "</b> solo puede aceptar valores numericos.",
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

//funcion para obtener el siguiente codigo
let getCod = () => {
    $.ajax({
        method: "POST",
        url: "controlador.php",
        data: {consulCod: 1}
    }).done(function (respuesta){
        $("#pedcom_cod").val(respuesta.codigo);
    });
}

//funcion nuevo
let nuevo = () => {
    $("#operacion_cab").val(1);
    $("#transaccion").val('INSERCION');
    $(".focus").attr("class", "form-line focus focused");
    $("#pedcom_estado").val('ACTIVO');
    $(".tbl, .tbldet, .solicitu_presupuesto, .tbl_soli, .sol_presup_det").attr("style", "display:none");
    getCod();
    habilitarBotones(true);
    datusUsuarios();
    window.scroll(0, -100);
};

//anular anular
let anular = () => {
    $("#operacion_cab").val(2);
    $("#transaccion").val('ANULACION');
    $("#pedcom_estado").val('ANULADO');
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
            pedcom_cod: $("#pedcom_cod").val(),
            usu_cod: $("#usu_cod").val(),
            suc_cod: $("#suc_cod").val(),
            emp_cod: $("#emp_cod").val(),
            pedcom_estado:$("#pedcom_estado").val(),
            operacion_cab: $("#operacion_cab").val(),
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

//funcion para validar que no haya campos vacios al grabar
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
        swal({
            html: true,
            title: "RESPUESTA!!",
            text: "Complete los siguientes campos: <b>" + camposVacios.join(", ") + "</b>.",
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

//funcion para validar si se puede agregar o eliminar un detalle
let validarDetalle = () => {
    return $.ajax({
        method: "POST",
        url: "controladorDetalles.php",
        data: {
            validacion_det: 1,
            pedcom_cod: $("#pedcom_cod").val(),
        }
    });
}

// funcion agregar del detalle
let agregar = () => {
    validarDetalle().done(function(respuesta) {
        if (respuesta.validar == 1) {
            alertaLabel("NO SE PUEDEN AGREGAR MAS ITEMS, EL PEDIDO SE ENCUENTRA ASOCIADO A UN PRESUPUETO");
            return;
        }
        $("#operacion_det").val(1);
        $(".foc").find(".form-control").val('');
        $(".disabledno").removeAttr("disabled");
        $(".foc").attr("class", "form-line foc focused");
        habilitarBotones2(true);
        window.scroll(0, -100);
    });
};

// funcion eliminar del detalle
let eliminar = () => {
    validarDetalle().done(function(respuesta) {
        if (respuesta.validar == 1) {
            alertaLabel("NO SE PUEDEN ELIMINAR ITEMS, EL PEDIDO SE ENCUENTRA ASOCIADO A UN PRESUPUETO");
            return;
        }
        $("#operacion_det").val(2);
        habilitarBotones2(true);
        window.scroll(0, -100);
    });
};


/*enviamos por POST a la base de datos los datos cargados los input para grabar un nuevo detalle de inscripción*/
function grabar2() {
    $.ajax({
        method: "POST",
        url: "controladorDetalles.php",
        data: {
            itm_cod: $("#itm_cod").val(),
            tipitem_cod: $("#tipitem_cod").val(),
            pedcom_cod: $("#pedcom_cod").val(),
            pedcomdet_cantidad: $("#pedcomdet_cantidad").val(),           
            pedcomdet_precio: $("#pedcomdet_precio").val(),
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
            //Si la respuesta devuelve un success recargamos la pagina
            if (respuesta.tipo == "success") {
                listar2(); //actualizamos la grilla
                $(".foc").find(".form-control").val(''); //limpiamos los input
                $(".foc").attr("class", "form-line foc"); //
                $(".disabledno").attr("disabled", "disabled"); //deshabilitamos los input
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
        swal({
            html: true,
            title: "RESPUESTA!!",
            text: "Complete los siguientes campos: <b>" + camposVacios.join(", ") + "</b>.",
            type: "error",
        });
    } else if (($("#tipitem_cod").val() == "1") && $("#pedcomdet_cantidad").val() !== "0") {
        alertaLabel("El campo <b>Cantidad</b> debe ser 0 (cero) para los servicios.");
    } else {
        confirmar2();
    }
};

const itemServicio = () => {
    if ($("#tipitem_cod").val() == "1") {
        $("#pedcomdet_cantidad").val(0);
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
            pedcom_cod: $("#pedcom_cod").val(),
        }
    }).done(function (respuesta) {
        let tabla = "";
        let cosTotal = 0;
        for (objeto of respuesta) {
            cosTotal += parseFloat(objeto.total);
            tabla += "<tr onclick='seleccionarFila2(" + JSON.stringify(objeto).replace(/'/g, '&#39;') + ")'>";
                tabla += "<td>" + objeto.itm_descri + "</td>";
                tabla += "<td>" + objeto.pedcomdet_cantidad + "</td>";
                tabla += "<td>" + objeto.uni_descri + "</td>";
                tabla += "<td>" + new Intl.NumberFormat('us-US').format(objeto.pedcomdet_precio) + "</td>";
                tabla += "<td>" + new Intl.NumberFormat('us-US').format(objeto.total) + "</td>";
            tabla += "</tr>";
        }

        let tot = "<th colspan='4' style='font-weight: bold;'>COSTOS TOTALES: </th>";
        tot += "<th>" + new Intl.NumberFormat('us-US').format(cosTotal) + "</th>";

        $("#grilla_det").html(tabla);
        $("#total").html(tot);
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
                    tabla += "<td>" + objeto.pedcom_cod + "</td>";
                    tabla += "<td>" + objeto.pedcom_fecha + "</td>";
                    tabla += "<td>" + objeto.usu_login + "</td>";
                    tabla += "<td>" + objeto.suc_descri + "</td>";
                    tabla += "<td>" + objeto.pedcom_estado + "</td>";
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
        url: "/SysGym/modulos/compras/pedidos_compra/listas/listaItems.php",
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
        $("#listaItems").attr("style", "display:block; position:absolute; z-index:3000; width:100%");
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
    itemServicio();
}

//----------------------------------------------------------METODOS DE LA SOLICITUD DE PRESUPUESTO----------------------------------------------------------

//-------------------------------------------------------> Metodos de la Cabecera de Solicitud

//funcion habilitar inputs
let habilitarBotones3 = (operacion_cab) => {
    /* Segun el parametro sea verdadero o falso deshabilitamos o habilitamos los botones */
    if (operacion_cab) {
        $(".btnOperacion5").attr("style", "display:none;");
        $(".btnOperacion6").attr("style", "width:12.5%;");
    } else {
        $(".btnOperacion6").attr("style", "display:none;");
        $(".btnOperacion5").attr("style", "width:12.5%;");
    }
};

//funcion para obtener el siguiente codigo
let getCodSol = () => {
    $.ajax({
        method: "POST",
        url: "controladorSolici.php",
        data: {
                consulCod: 1,
                cab_det: 'cab'
            }
    }).done(function (respuesta){
        $("#solpre_cod").val(respuesta.codigo);
    });
}

//funcion nuevo de la solicitud cabecera
let nuevaSolici = () => {
    $("#operacion_cab").val(1);
    $(".solfocus").find(".form-control").val('');
    $(".solfocus").find(".disabledno").removeAttr("disabled");
    $(".solfocus").attr("class", "form-line solfocus focused");
    $(".tbl_soli, .sol_presup_det").attr("style", "display:none");
    getCodSol();
    habilitarBotones3(true);
    datusUsuarios();
};

//anular anular
let eliminarSolici = () => {
    $("#operacion_cab").val(2);
    habilitarBotones3(true);
};

//funcion grabar solicitud cabecera
let grabarSolCab = () => {
    $.ajax({
        //Enviamos datos al controlador
        method: "POST",
        url: "controladorSolici.php",
        data: {
            cab_det : 'cab',
            solpre_cod:$("#solpre_cod").val(),
            pedcom_cod: $("#pedcom_cod_sol").val(),
            pro_cod: $("#pro_cod").val(),
            tiprov_cod: $("#tiprov_cod").val(),
            solpre_email: $("#solpre_email").val(),
            usu_cod: $("#usu_cod").val(),
            suc_cod: $("#suc_cod").val(),
            emp_cod: $("#emp_cod").val(),
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
                    //Si la respuesta devuelve un success se muestra el detalle de la solicitud
                    if (respuesta.tipo == "success") {
                        //Realizamos operaciones individuales en caso de que sea insercion o eliminacion de cabecera
                        if ($("#operacion_cab").val() == 1) {
                            $(".tbl_soli").attr("style", ""); // se muestra grilla de la cabecera
                            listarSolCab(); //actualizamos la grilla de la cabecera
                            listarSolDet(); //actualizamos la grilla del detalle
                            habilitarBotones3(false); //deshabilitamos los botones
                            $(".sol_presup_det").attr("style", ""); // Se mustra el formulario del detalle
                        } else if ($("#operacion_cab").val() == 2) {
                            cancelar();
                        }
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

//funcion confirmar solicitud cabecera
let confirmarSolCab = () => {
    //solicitamos el value del input operacion
    var oper = $("#operacion_cab").val();

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
                grabarSolCab();
            } else {
                //Si cancelamos la operacion realizamos un reload
                cancelar();
            }
        }
    );
};

//funcion para validar que no haya campos vacios al grabar
let controlVacio3 = () => {
    // Obtener todos los ids de los elementos con clase disabledno
    let campos = $(".solfocus").find('.form-control').map(function() {
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
        swal({
            html: true,
            title: "RESPUESTA!!",
            text: "Complete los siguientes campos: <b>" + camposVacios.join(", ") + "</b>.",
            type: "error",
        });
    } else {
        confirmarSolCab();
    }
};

//-------------------------------------------------------> Metodos del Detalle de Solicitud

//funcion habilitar inputs
let habilitarBotones4 = (operacion_det) => {
    /* Segun el parametro sea verdadero o falso deshabilitamos o habilitamos los botones */
    if (operacion_det) {
        $(".btnOperacion7").attr("style", "display:none;");
        $(".btnOperacion8").attr("style", "width:12.5%;");
    } else {
        $(".btnOperacion8").attr("style", "display:none;");
        $(".btnOperacion7").attr("style", "width:12.5%;");
    }
};

// funcion agregar del detalle
let agregarSoliciDet = () => {
    $("#operacion_det").val(1);
    $(".solfoc").find(".form-control").val('');
    $(".solfoc").find(".disabledno").removeAttr("disabled");
    $(".solfoc").attr("class", "form-line solfoc focused");
    habilitarBotones4(true);
};

// funcion eliminar del detalle
let eliminarSolicDet = () => {
    $("#operacion_det").val(2);
    habilitarBotones4(true);
};

/*enviamos por POST a la base de datos los datos cargados los input para grabar un nuevo detalle de inscripción*/
function grabarSolDet() {
    $.ajax({
        method: "POST",
        url: "controladorSolici.php",
        data: {
            cab_det : 'det',
            solpre_cod : $("#solpre_cod").val(),
            pedcom_cod: $("#pedcom_cod_sol").val(),
            pro_cod: $("#pro_cod").val(),
            tiprov_cod: $("#tiprov_cod").val(),
            itm_cod: $("#itm_cod_sol").val(),
            tipitem_cod: $("#tipitem_cod_sol").val(),
            solpredet_cantidad: $("#solpredet_cantidad").val(),           
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
            //Si la respuesta devuelve un success limpiamos el detalle
            if (respuesta.tipo == "success") {
                listarSolDet(); //actualizamos la grilla
                $(".solfoc").find(".form-control").val(''); //limpiamos los input
                $(".solfoc").attr("class", "form-line solfoc"); //
                $(".solfoc").find(".disabledno").attr("disabled", "disabled"); //deshabilitamos los input
                habilitarBotones4(false); //deshabilitamos los botones
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
let confirmarSolDet = () => {
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
                grabarSolDet();
            } else {
                //Si cancelamos la operacion realizamos un reload
                cancelar();
            }
        }
    );
};

//funcion para validar que no haya campos vacios al grabar
let controlVacio4 = () => {
    // Obtener todos los ids de los elementos con clase disabledno
    let campos = $(".solfoc").find('.form-control').map(function() {
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
        swal({
            html: true,
            title: "RESPUESTA!!",
            text: "Complete los siguientes campos: <b>" + camposVacios.join(", ") + "</b>.",
            type: "error",
        });
    } else if (($("#tipitem_cod_sol").val() == "1") && $("#pedcomdet_cantidad").val() !== "0") {
        alertaLabel("El campo <b>Cantidad</b> debe ser 0 (cero) para los servicios.");
    } else {
        confirmarSolDet();
    }
};

let enviarDoc = () => {
    if ($("#solpre_cod").val() == '') {
        swal({
            title: "RESPUESTA!!",
            text: "SELECCIONE UN REGISTRO",
            type: "error",
        });
    } else {
        $.ajax({
            method: "POST",
            url: "/SysGym/others/mail/envioSolicitudPresup.php",
            data: { 
                solpre_cod: $("#solpre_cod").val(),
                solpre_email: $("#solpre_email").val(),
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

//---------------------------------------------------------------------> Listas de  solicitud

//funcion seleccionar Fila
let seleccionarFilaSolDet = (objetoJSON) => {
    // Enviamos los datos a sus respectivos inputs
    Object.keys(objetoJSON).forEach(function (propiedad) {
        $("#" + propiedad).val(objetoJSON[propiedad]);
    });
    $(".solfoc").attr("class", "form-line solfoc focused");
};

//funcion listar
let listarSolDet = () => {
    $.ajax({
        method: "POST",
        url: "controladorSolici.php",
        data: {
            cab_det: 'det',
            solpre_cod: $("#solpre_cod").val(),
        }
    }).done(function (respuesta) {
        let tabla = "";
        let cosTotal = 0;
        for (objeto of respuesta) {
            cosTotal += parseFloat(objeto.total);
            tabla += "<tr onclick='seleccionarFilaSolDet(" + JSON.stringify(objeto).replace(/'/g, '&#39;') + ")'>";
                tabla += "<td>" + objeto.itm_cod_sol + "</td>";
                tabla += "<td>" + objeto.itm_descri_sol + "</td>";
                tabla += "<td>" + objeto.solpredet_cantidad + "</td>";
            tabla += "</tr>";
        }

        $("#grilla_det_sol").html(tabla);
    })
    .fail(function (a, b, c) {
        swal("ERROR", c, "error");
    });
};

//funcion seleccionar Fila
let seleccionarFilaSolCab = (objetoJSON) => {
    // Enviamos los datos a sus respectivos inputs
    Object.keys(objetoJSON).forEach(function (propiedad) {
        $("#" + propiedad).val(objetoJSON[propiedad]);
    });

    $(".solfocus").attr("class", "form-line solfocus focused");
    $(".sol_presup_det").removeAttr("style", "display:none;");
    datusUsuarios();
    listarSolDet();
};

//funcion listar
let listarSolCab = () => {
    $.ajax({
        method: "POST",
        url: "controladorSolici.php",
        data: {cab_det: "cab"}
    }).done(function (respuesta) {
            let tabla = "";
            for (objeto of respuesta) {
                tabla += "<tr onclick='seleccionarFilaSolCab(" + JSON.stringify(objeto).replace(/'/g, '&#39;') + ")'>";
                    tabla += "<td>" + objeto.solpre_cod + "</td>";
                    tabla += "<td>" + objeto.solpre_fecha + "</td>";
                    tabla += "<td>" + objeto.usu_login_sol + "</td>";
                    tabla += "<td>" + objeto.suc_descri_sol + "</td>";
                    tabla += "<td>" + objeto.pedcom_cod_sol + "</td>";
                    tabla += "<td>" + objeto.pro_razonsocial +"</td>";
                tabla += "</tr>";
            }
            $("#grilla_cab_sol").html(tabla);
            //formatoTabla();
        })
        .fail(function (a, b, c) {
            swal("ERROR", c, "error");
        });
};

listarSolCab();

//---------------------------------------------------------------------> Autocompletados solicitud

//capturamos los datos de la tabla proveedor en un JSON a través de POST para listarlo
function getProveedor() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/compras/pedidos_compra/listas/listaProveedor.php",
        data: {
            pro_razonsocial:$("#pro_razonsocial").val()
        }
        //en base al JSON traído desde el listaProveedor arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{    
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionProveedor("+JSON.stringify(item)+")'>"+item.pro_razonsocial+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulProveedor").html(fila);
        //le damos un estilo a la lista de Proveedor
        $("#listaProveedor").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el Proveedor de compra por su key y enviamos el dato al input correspondiente
function seleccionProveedor (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulProveedor").html();
    $("#listaProveedor").attr("style", "display:none;");
    $(".solfocus").attr("class", "form-line solfocus focused");
}

//capturamos los datos de la tabla pedido_compra_cab en un JSON a través de POST para listarlo
function getPedCom() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/compras/pedidos_compra/listas/listaPedCom.php",
        data: {
            pedcom_cod:$("#pedcom_cod_sol").val()
        }
        //en base al JSON traído desde el listaPedCom arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{    
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionPedCom("+JSON.stringify(item)+")'>"+item.pedcom_cod_sol+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulPedCom").html(fila);
        //le damos un estilo a la lista de PedCom
        $("#listaPedCom").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el pedido de compra por su key y enviamos el dato al input correspondiente
function seleccionPedCom (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulPedCom").html();
    $("#listaPedCom").attr("style", "display:none;");
    $(".solfocus").attr("class", "form-line solfocus focused");
}

//capturamos los datos de la tabla items en un JSON a través de POST para listarlo
function getItemsPed() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/compras/pedidos_compra/listas/listaItemsPed.php",
        data: {
            pedcom_cod:$("#pedcom_cod_sol").val(),
            itm_descri:$("#itm_descri_sol").val()
        }
        //en base al JSON traído desde el listaItemsPed arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{    
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionItemsPed("+JSON.stringify(item)+")'>"+item.itm_descri_sol+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulItemsPed").html(fila);
        //le damos un estilo a la lista de ItemsPed
        $("#listaItemsPed").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el item por su key y enviamos el dato al input correspondiente
function seleccionItemsPed (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulItemsPed").html();
    $("#listaItemsPed").attr("style", "display:none;");
    $(".solfoc").attr("class", "form-line solfoc focused");
}