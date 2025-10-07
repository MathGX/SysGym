const sesionApcier = () =>{
    if (($("#apcier_cod").val() === '0')  || ($("#apcier_estado").val() === 'CERRADA')) {
        $("#btnApertura").attr("style", "width:12.5%;");
        $("#btnCierre").attr("style", "display : none;");
    } else {
        $("#btnApertura").attr("style", "display : none;");
        $(".btnReabrir").attr("style", "display : none;");
        $("#btnCierre").attr("style", "width:12.5%;");
        $(".arqueo").attr("style", "display : block;");
    }
}

sesionApcier();

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

// fecha y hora actuales
let formatoFecha = (fecha) => {
    let dia = fecha.getDate();
    let mes = fecha.getMonth() + 1;
    let ano = fecha.getFullYear();
    let horas = fecha.getHours();
    let minutos = fecha.getMinutes();
    let segundos = fecha.getSeconds();

    mes = mes < 10 ? '0' + mes : mes;
    dia = dia < 10 ? '0' + dia : dia;
    horas = horas < 10 ? '0' + horas : horas;
    minutos = minutos < 10 ? '0' + minutos : minutos;
    segundos = segundos < 10 ? '0' + segundos : segundos;

    return `${ano}-${mes}-${dia}T${horas}:${minutos}:${segundos}`;
}
let ahora = new Date();

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

let soloNumeros = (nombreInput, idInput) => {
    caracteres = /[-'_¡´°/\!@#$%^&*(),.¿?":{}|<>;~`+]/;
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

/*-------------------------------------------- METODOS --------------------------------------------*/

//funcion habilitar inputs
let habilitarBotones = (operacion) => {
    /* Segun el parametro sea verdadero o falso deshabilitamos o habilitamos los botones */
    if (operacion) {
        $(".btnOperacion1").attr("style", "display:none;");
        $(".btnOperacion2").attr("style", "width:12.5%;");
    } else {
        $(".btnOperacion2").attr("style", "display:none;");
        $(".btnOperacion1").attr("style", "width:12.5%;");
    }
};

//funcion para obtener el siguiente codigo
const getCod = () => {
    $.ajax({
        method: "GET",
        url: "controlador.php"
    }).done(function (respuesta){
        $("#apcier_cod").val(respuesta.cod_apcier);
    });
}

//funcion abrir
let abrir = () => {
    $("#operacion").val(1);
    $("#apcier_estado").val('ABIERTA');
    $(".disabledno").val('');
    $(".aper").attr("style", "display:block"); 
    $(".disabledno").removeAttr("disabled");
    $("#apcier_fechahora_aper").val(formatoFecha(ahora));
    getCod();
    habilitarBotones(true);
    datusUsuarios();
    window.scroll(0, -100);
};

//funcion cerrar
let cerrar = () => {
    $("#operacion").val(2);
    $("#apcier_estado").val('CERRADA');
    $(".cier").attr("style", "display:block");
    $("#apcier_fechahora_cierre").val(formatoFecha(ahora));
    habilitarBotones(true);
    datusUsuarios();
    window.scroll(0, -100);
    $.ajax({
        method: "POST",
        url: "ctrlCierre.php",
        data: {
            caso: "cierre",
            motivo: "nada",
            apcier_fechahora_cierre: $("#apcier_fechahora_cierre").val(),
            apcier_cod: $("#apcier_cod").val()
        }
    }).done(function (monto) {  
        $("#apcier_monto_cierre").val(monto.cobrdet_monto);
    });
};

//funcion cancelar
let cancelar = () => {
    window.location.reload();
};

//funcion salir
let salir = () => {
    window.location = "/SysGym/menu.php";
};

let recaudaciones = () => {
    $.ajax({
        method: "POST",
        url: "ctrlCierre.php",
        data: {
            caso: "cierre",
            motivo: "recaudacion",
            apcier_fechahora_cierre: $("#apcier_fechahora_cierre").val(),
            caj_cod: $("#caj_cod").val(),
            suc_cod: $("#suc_cod").val(),
            emp_cod: $("#emp_cod").val(),
            usu_cod: $("#usu_cod").val(),
            apcier_cod: $("#apcier_cod").val()
        },
    });
};

//funcion grabar
let grabar = () => {
    $.ajax({
        //Enviamos datos al controlador
        method: "POST",
        url: "controlador.php",
        data: {
            caj_cod: $("#caj_cod").val().trim(),
            caj_descri: $("#caj_descri").val().trim(),
            suc_cod: $("#suc_cod").val().trim(),
            emp_cod: $("#emp_cod").val().trim(),
            usu_cod: $("#usu_cod").val().trim(),
            apcier_cod: $("#apcier_cod").val().trim(),
            apcier_fechahora_aper: $("#apcier_fechahora_aper").val(),
            apcier_fechahora_cierre: $("#apcier_fechahora_cierre").val(),
            apcier_monto_aper: $("#apcier_monto_aper").val().trim(),
            apcier_monto_cierre: $("#apcier_monto_cierre").val().trim(),
            apcier_estado: $("#apcier_estado").val().trim(),
            operacion: $("#operacion").val().trim()
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

    preg = "¿Es correcto el monto de "+$("#apcier_monto_aper").val()+" en la apertura caja?";

    /* De acuerdo si la operacion es 2 o 3 modificamos la pregunta */
    if (oper == 2) {
        preg = "¿Desea cerrar la caja?";
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
                if ($("#operacion").val() == '2') {
                    recaudaciones();
                }
                grabar();
            } else {
                //Si cancelamos la operacion realizamos un reload
                cancelar();
            }
        }
    );
};

//funcion control vacio de apertura y cierre
let controlVacio = () => {
    let camposVacios = [];
    let estadoActual = $("#apcier_estado").val().trim().toUpperCase();

    // Selecciona todos los inputs relevantes en todas las secciones (cab, aper, cier)
    $(".cab .form-control, .aper .form-control, .cier .form-control").each(function () {
        let $input = $(this);
        let valor = $input.val().trim();
        let $formLine = $input.closest('.form-line');
        let nombreInput = $formLine.find('.form-label').text().trim();

        // Determina si el campo es de la sección 'aper' o 'cier'
        let esDeApertura = $input.closest('.aper').length > 0;
        let esDeCierre = $input.closest('.cier').length > 0;

        // Reglas de validación:
        // 1. Validar si el input no está deshabilitado.
        // 2. Validar siempre los campos del encabezado (.cab).
        // 3. Validar los campos de apertura solo si el estado es "ABIERTA".
        // 4. Validar los campos de cierre solo si el estado es "CERRADA".
        if (!$input.prop('disabled') && valor === "") {
            if ($input.closest('.cab').length > 0) {
                camposVacios.push(nombreInput);
            } else if (esDeApertura && estadoActual === "ABIERTA") {
                camposVacios.push(nombreInput);
            } else if (esDeCierre && estadoActual === "CERRADA") {
                camposVacios.push(nombreInput);
            }
        }
    });

    if (camposVacios.length > 0) {
        let mensaje = "Complete los siguientes campos: <b>" + camposVacios.join(", ") + "</b>.";
        swal({
            html: true,
            title: "RESPUESTA!!",
            text: mensaje,
            type: "error"
        });
    } else {
        confirmar();
    }
};

let reapertura = () => {
    $(".reapertura").attr("style", "");
    $(".btnOperacion1").attr("style", "display:none");
    getAperturas();
}

let reabrir = () => {
    $.ajax({
        method: "POST",
        url: "ctrlCierre.php",
        data: {
            caj_cod: $("#caj_cod").val(),
            caj_descri: $("#caj_descri").val(),
            apcier_cod: $("#apcier_cod").val(),
            apcier_estado: $("#apcier_estado").val(),
            caso: 'reapertura'
        }
    }).done(function (respuesta) {
        //Si la respuesta devuelve un success recargamos la pagina
        if (respuesta.tipo == "success") {
            location.reload(true);
        }
    });
}

//funcion para validar que no haya campos vacios al grabar
let controlVacioReabrir = () => {
    // Obtener todos los ids de los elementos con clase disabledno
    let campos = $(".reapertura, .cab").find('.form-control').map(function() {
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
    if (camposVacios.length > 0 ) {
        alertaLabel("Complete los siguientes campos: <b>" + camposVacios.join(", ") + "</b>");
    } else {
        reabrir();
    }
};

/*---------------------------------------------------- ARQUEO CONTROL ----------------------------------------------------*/

//metodo para controlar los checkbox seleccionados
let check = () => {
    document.addEventListener('DOMContentLoaded', function() {
        const todosMedios = document.getElementById('todos_medios');
        const efectivo = document.getElementById('efectivo');
        const tarjeta = document.getElementById('tarjeta');
        const cheque = document.getElementById('cheque');
    
        todosMedios.addEventListener('change', function() {
            if (this.checked) {
                // Si "todos_medios" se selecciona, deseleccionar los otros 3 checkboxes
                efectivo.checked = false;
                tarjeta.checked = false;
                cheque.checked = false;
            }
        });
    
        efectivo.addEventListener('change', function() {
            if (this.checked) {
                // Si "efectivo" se selecciona, deseleccionar "todos_medios"
                todosMedios.checked = false;
            } 
            if (this.checked && cheque.checked && tarjeta.checked) {
                todosMedios.checked = true;
                this.checked = false;
                tarjeta.checked = false;
                cheque.checked = false;
            }
        });
    
        tarjeta.addEventListener('change', function() {
            if (this.checked) {
                // Si "tarjeta" se selecciona, deseleccionar "todos_medios"
                todosMedios.checked = false;
            } 
            if (this.checked && efectivo.checked && cheque.checked) {
                todosMedios.checked = true;
                efectivo.checked = false;
                this.checked = false;
                cheque.checked = false;
            }
        });
    
        cheque.addEventListener('change', function() {
            if (this.checked) {
                // Si "cheque" se selecciona, deseleccionar "todos_medios"
                todosMedios.checked = false;
            } 
            if (this.checked && efectivo.checked && tarjeta.checked) {
                todosMedios.checked = true;
                efectivo.checked = false;
                tarjeta.checked = false;
                this.checked = false;
            }
        });
    });
}
check();

//metodo para capturar los checkbox seleccionados y enviarlos al backend
let arqueo = () => {
    let seleccionados = [''];
    $('input[name="efectivo"]:checked').each(function() {
        seleccionados[0] = ($(this).attr('id'));
    });
    $('input[name="cheque"]:checked').each(function() {
        seleccionados[1] = ($(this).attr('id'));
    });
    $('input[name="tarjeta"]:checked').each(function() {
        seleccionados[2] = ($(this).attr('id'));
    });
    $('input[name="todos_medios"]:checked').each(function() {
        seleccionados[3] = ($(this).attr('id'));
    });
    
    $.ajax({
        method: "POST",
        url: "ctrlCierre.php",
        data: {
            caj_cod: $("#caj_cod").val(),
            suc_cod: $("#suc_cod").val(),
            emp_cod: $("#emp_cod").val(),
            usu_cod: $("#usu_cod").val(),
            apcier_cod: $("#apcier_cod").val(),
            arq_obs: $("#arq_obs").val(),
            fun_cod: $("#fun_cod").val(),
            caso: "arqueo"
        }
    }).done(function(respuesta){
        if (respuesta.msj == "ok"){
            window.open ('/SysGym/modulos/ventas/apertura_cierre/reporteArqueo.php?\
            seleccionados='+seleccionados.join(',')+'&caj_cod='+$("#caj_cod").val()+'&caj_descri='+$("#caj_descri").val()+'\
            &funcionarios='+$("#funcionarios").val()+'&apcier_cod='+$("#apcier_cod").val());
        }
    });
}

//funcion para validar que no haya campos vacios al grabar
let controlVacioArqueo = () => {
    //Verificar si por lo menos un check está seleccionado
    let medPago = false;
    
    $('input[type="checkbox"]').each(function() {
        if ($(this).is(':checked')) {
            medPago = true;
        }
    });
    
    // Obtener todos los ids de los elementos hijos de arqueo
    let campos = $(".arqueo").find('.form-control').map(function() {
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
    if (camposVacios.length > 0 ) {
        alertaLabel("Complete los siguientes campos: <b>" + camposVacios.join(", ") + "</b>");
    } else if (medPago == false ) {
        alertaLabel("Seleccione por lo menos un <b>Medio de pago</b>");
    } else {
        arqueo();
    }
};

/*---------------------------------------------------- AUTOCOMPLETADOS ----------------------------------------------------*/

//capturamos los datos de la tabla Apertura y cierre en un JSON a través de POST para listarlo
function getAperturas() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/ventas/apertura_cierre/listas/listaAperturas.php",
        data: {
            usu_cod1:$("#usu_cod1").val(),
        }
        //en base al JSON traído desde el listaAperturas arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>";
        }else{
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionAperturas("+JSON.stringify(item)+")'>"+item.usu_login1+" - "+item.caj_descri+" - "+item.apcier_fechahora_aper+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulAperturas").html(fila);
        //le damos un estilo a la lista de GUI
        $("#listaAperturas").attr("style", "display:block; position:absolute;  z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos la apertura por su key y enviamos el dato al input correspondiente
function seleccionAperturas (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulAperturas").html();
    $("#listaAperturas").attr("style", "display:none;");
    $(".focus").attr("class", "form-line focus focused");
}

//capturamos los datos de la tabla Cajas en un JSON a través de POST para listarlo
function getCajas() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/ventas/apertura_cierre/listas/listaCajas.php",
        data: {
            suc_cod:$("#suc_cod").val(),
            emp_cod:$("#emp_cod").val(),
            caj_descri:$("#caj_descri").val()
        }
        //en base al JSON traído desde el listaCajas arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>";
        }else{
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionCajas("+JSON.stringify(item)+")'>"+item.caj_descri+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulCajas").html(fila);
        //le damos un estilo a la lista de GUI
        $("#listaCajas").attr("style", "display:block; position:absolute;  z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos la caja por su key y enviamos el dato al input correspondiente
function seleccionCajas (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulCajas").html();
    $("#listaCajas").attr("style", "display:none;");
    $(".focus").attr("class", "form-line focus focused");
}

//capturamos los datos de la tabla funcionarios en un JSON a través de POST para listarlo
function getFuncionarios() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/ventas/apertura_cierre/listas/listaFuncionarios.php",
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
        $("#listaFuncionarios").attr("style", "display:block; position:absolute;  z-index:3000; width:100%;");
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