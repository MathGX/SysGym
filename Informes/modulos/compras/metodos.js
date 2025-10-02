//funcion hablitarCampos
let habilitarCampos = () => {
    if ($("#informe").val() == "PRESUPUESTOS DEL PROVEEDOR") {
        //se ocultan los campos que no corresponden
        $(".libro_compra, .cuentas_pagar").attr("style", "display:none");
        //se limpian todos los campos innecesarios por si se cambia de informe
        $(".libro_compra input, .cuentas_pagar input").each(function(){
            $(this).val('');
        });
        //se muestran los campos correspondientes
        $(".presup_compras").removeAttr("style");
    } else if ($("#informe").val() == "LIBRO DE COMPRAS") {
        //se ocultan los campos que no corresponden
        $(".presup_compras, .cuentas_pagar").attr("style", "display:none");
        //se limpian todos los campos innecesarios por si se cambia de informe
        $(".presup_compras input, .cuentas_pagar input").each(function(){
            $(this).val('');
        });
        //se muestran los campos correspondientes
        $(".libro_compra").removeAttr("style");
    } else if ($("#informe").val() == "CUENTAS A PAGAR") {
        //se ocultan los campos que no corresponden
        $(".presup_compras, .libro_compra").attr("style", "display:none");
        //se limpian todos los campos innecesarios por si se cambia de informe
        $(".presup_compras input, .libro_compra input").each(function(){
            $(this).val('');
        });
        //se muestran los campos correspondientes
        $(".cuentas_pagar").removeAttr("style");
    }
};

let formatoFecha = (fecha) => {
    let dia = fecha.getDate();
    let mes = fecha.getUTCMonth() + 1;
    let ano = fecha.getUTCFullYear();

    mes = mes < 10 ? '0' + mes : mes;
    dia = dia < 10 ? '0' + dia : dia;

    return `${mes}-${ano}`;
}

//funcion cancelar
let cancelar = () => {
    window.location.reload();
};

//funcion salir
let salir = () => {
    window.location = "/SysGym/menu.php";
};

//funcion para mostrar alertas con label en el mensaje
let alertaLabel = (msj) => {
    swal({
        html: true,
        title: "ATENCIÓN!!",
        text: msj,
        type: "error",
    });
}

//función para reportes
let reporte = () => {
    let desde = $("#desde").val();
    let hasta = $("#hasta").val();
    let pedcom_cod = $("#pedcom_cod").val() || 0;
    let pro_cod = $("#pro_cod").val() || 0;
    let tiprov_cod = $("#tiprov_cod").val() || 0;
    let tipcomp_cod = $("#tipcomp_cod").val() || 0;
    let cuenpag_estado = $("#cuenpag_estado").val() || '';

    if($("#informe").val() == "PRESUPUESTOS DEL PROVEEDOR"){
        window.open("presupuesto_prov_reporte/reportePresupuesto.php?pedcom_cod="+pedcom_cod+"&itm_cod="+itm_cod+"&tipitem_cod="+tipitem_cod+"&tipimp_cod="+tipimp_cod);
    } else if($("#informe").val() == "CUENTAS A PAGAR"){
        window.open("cuentasPagar_reporte/reporteCuentasPagar.php?desde="+desde+"&hasta="+hasta+"&pro_cod="+pro_cod+"&tiprov_cod="+tiprov_cod+"&cuenpag_estado="+cuenpag_estado);
    } else if($("#informe").val() == "LIBRO DE COMPRAS"){
        window.open("libroCompras_reporte/reporteLibroCompras.php?desde="+desde+"&hasta="+hasta+"&pro_cod="+pro_cod+"&tiprov_cod="+tiprov_cod+"&tipcomp_cod="+tipcomp_cod);
    }
}

//funcion control vacio
let controlVacio = () => {
    let camposVacios = [];
    let informeActual = $("#informe").val().trim().toUpperCase();
    let desde = new Date($("#desde").val());
    let hasta = new Date($("#hasta").val());

    // Selecciona todos los inputs relevantes en todas las secciones (presup_compras, libro_compras, cuentas_pagar)
    $(".obligatorio").each(function () {
        let $input = $(this);
        let valor = $input.val().trim();
        let $formLine = $input.closest('.form-line');
        let nombreInput = $formLine.find('.form-label').text().trim();

        // Determina si el campo es de la sección 'presup_compras', 'libro_compras' o 'cuentas_pagar'
        let esPresup = $input.closest('.presup_compras').length > 0;
        let esLibro = $input.closest('.libro_compra').length > 0;
        let esCuenta = $input.closest('.cuentas_pagar').length > 0;

        // Reglas de validación:
        // 1. Validar si el input no está deshabilitado.
        // 2. Validar siempre el campo de reporte.
        // 3. Validar los campos de presupuesto proveedor solo si se encuentra seleccionado en el campo de reporte.
        // 4. Validar los campos de libro de compras solo si se encuentra seleccionado en el campo de reporte.
        // 5. Validar los campos de cuentas a pagar solo si se encuentra seleccionado en el campo de reporte.
        if (valor === "") {
            if ($input.closest('.mod').length > 0) {
                camposVacios.push(nombreInput);
            } else if (esPresup && informeActual === "PRESUPUESTOS DEL PROVEEDOR") {
                camposVacios.push(nombreInput);
            } else if (esLibro && informeActual === "LIBRO DE COMPRAS") {
                camposVacios.push(nombreInput);
            } else if (esCuenta && informeActual === "CUENTAS A PAGAR") {
                camposVacios.push(nombreInput);
            }
        }
    });

    if (camposVacios.length > 0) {
        alertaLabel("Complete los siguientes campos: <b>" + camposVacios.join(", ") + "</b>.");
    } else if (informeActual === "LIBRO DE COMPRAS" && formatoFecha(desde) !== formatoFecha(hasta)) {
        alertaLabel("Las fechas seleccionadas deben pertenecer al mismo peridodo fiscal");
        console.log(formatoFecha(desde), formatoFecha(hasta));
    } else {
        reporte();
    }
};

//----------------------------------------------------------------- AUTOCOMPLETADOS -----------------------------------------------------------------//

//funcion para seleccionar el informe
let getInforme = () => {
    $.ajax({
        //Enviamos datos para poder filtrar
        method: "POST",
        url: "/SysGym/Informes/modulos/compras/controlador.php",
        data: {
            informe:$("#informe").val()
        }
    }) //Cargamos la lista
        .done(function (lista) {
            let fila = "";
            //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
            if(lista.true == true){
                fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
            }else{    
                $.each(lista, function (i, objeto) {
                    fila +="<li class='list-group-item' onclick='seleccionInforme(" + JSON.stringify(objeto) + ")'>" + objeto.informe + "</li>";
                });
            }
            //cargamos la lista
            $("#ulInforme").html(fila);
            //hacemos visible la lista
            $("#listaInforme").attr("style", "display: block; position:absolute; z-index: 3000; width:100%;");
        })
        .fail(function (a, b, c) {
            swal("ERROR", c, "error");
        });
};

//funcion selecccionar Informe
let seleccionInforme = (datos) => {
    //Enviamos los datos a su respectivo input
    Object.keys(datos).forEach((key) => {
        $("#" + key).val(datos[key]);
    });
    /* Vaciamos y ocultamos la lista */
    $("#ulInforme").html();
    $("#listaInforme").attr("style", "display: none;");
    $(".mod").attr("class", "form-line mod focused");
    habilitarCampos();
};

//capturamos los datos de la tabla pedido_compra_cab en un JSON a través de POST para listarlo
function getPedCom() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/compras/presupuesto_proveedor/listas/listaPedCom.php",
        data: {
            pedcom_cod:$("#pedcom_cod").val()
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
                fila += "<li class='list-group-item' onclick='seleccionPedCom("+JSON.stringify(item)+")'>"+item.pedcom_cod+"</li>";
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
    $(".focus").attr("class", "form-line focus focused");
}

//capturamos los datos de la tabla items en un JSON a través de POST para listarlo
function getItems() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/compras/presupuesto_proveedor/listas/listaItems.php",
        data: {
            pedcom_cod:$("#pedcom_cod").val(),
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
    
//capturamos los datos de la tabla proveedor en un JSON a través de POST para listarlo
function getProveedor() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/compras/pedidos_compra/listas/listaProveedor.php",
        data: {
            pro_razonsocial:$("#pro_razonsocial").val().trim()
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
    $(".focus").attr("class", "form-line focus focused");
}

//capturamos los datos de la tabla tipo_comprobante en un JSON a través de POST para listarlo
function getNota() {
    $.ajax({
        method: "POST",
        url: "/SysGym/Informes/modulos/compras/listas/listaNota.php",
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
}

//capturamos los datos de la tabla cuentas_pagar en un JSON a través de POST para listarlo
function getEstado() {
    $.ajax({
        method: "POST",
        url: "/SysGym/Informes/modulos/compras/listas/listaEstado.php",
        data: {
            cuenpag_estado:$("#cuenpag_estado").val()
        }
        //en base al JSON traído desde el listaEstado arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{    
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionEstado("+JSON.stringify(item)+")'>"+item.cuenpag_estado+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulEstado").html(fila);
        //le damos un estilo a la lista de Estado
        $("#listaEstado").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el item por su key y enviamos el dato al input correspondiente
function seleccionEstado (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulEstado").html();
    $("#listaEstado").attr("style", "display:none;");
    $(".focus").attr("class", "form-line focus focused");
}