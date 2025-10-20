//funcion hablitarCampos
let habilitarCampos = () => {
    if ($("#informe").val() == "CAJA Y RECAUDACIONES") {
        //se ocultan los campos que no corresponden
        $(".libro_venta, .cuentas_cobrar").attr("style", "display:none");
        //se limpian todos los campos innecesarios por si se cambia de informe
        $(".libro_venta input, .cuentas_cobrar input").each(function(){
            $(this).val('');
        });
        //se muestran los campos correspondientes
        $(".caja_rec").removeAttr("style");
    } else if ($("#informe").val() == "LIBRO DE VENTAS") {
        //se ocultan los campos que no corresponden
        $(".caja_rec, .cuentas_cobrar").attr("style", "display:none");
        //se limpian todos los campos innecesarios por si se cambia de informe
        $(".caja_rec input, .cuentas_cobrar input").each(function(){
            $(this).val('');
        });
        //se muestran los campos correspondientes
        $(".libro_venta").removeAttr("style");
    } else if ($("#informe").val() == "CUENTAS A COBRAR") {
        //se ocultan los campos que no corresponden
        $(".caja_rec, .libro_venta").attr("style", "display:none");
        //se limpian todos los campos innecesarios por si se cambia de informe
        $(".caja_rec input, .libro_venta input").each(function(){
            $(this).val('');
        });
        //se muestran los campos correspondientes
        $(".cuentas_cobrar").removeAttr("style");
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
    let cli_cod = $("#cli_cod").val() || 0;
    let tiprov_cod = $("#tiprov_cod").val() || 0;
    let tipcomp_cod = $("#tipcomp_cod").val() || 0;
    let cuencob_estado = $("#cuencob_estado").val() || '';

    if($("#informe").val() == "CAJA Y RECAUDACIONES"){
        window.open("presupuesto_prov_reporte/reportePresupuesto.php?pedcom_cod="+pedcom_cod+"&itm_cod="+itm_cod+"&tipitem_cod="+tipitem_cod+"&tipimp_cod="+tipimp_cod);
    } else if($("#informe").val() == "CUENTAS A COBRAR"){
        window.open("cuentasCobrar_reporte/reporteCuentasCobrar.php?desde="+desde+"&hasta="+hasta+"&cli_cod="+cli_cod+"&tiprov_cod="+tiprov_cod+"&cuencob_estado="+cuencob_estado);
    } else if($("#informe").val() == "LIBRO DE VENTAS"){
        window.open("libroVentas_reporte/reporteLibroVentas.php?desde="+desde+"&hasta="+hasta+"&cli_cod="+cli_cod+"&tiprov_cod="+tiprov_cod+"&tipcomp_cod="+tipcomp_cod);
    }
}

//funcion control vacio
let controlVacio = () => {
    let camposVacios = [];
    let informeActual = $("#informe").val().trim().toUpperCase();
    let desde = new Date($("#desde").val());
    let hasta = new Date($("#hasta").val());

    // Selecciona todos los inputs relevantes en todas las secciones (caja_rec, libro_ventas, cuentas_cobrar)
    $(".obligatorio").each(function () {
        let $input = $(this);
        let valor = $input.val().trim();
        let $formLine = $input.closest('.form-line');
        let nombreInput = $formLine.find('.form-label').text().trim();

        // Determina si el campo es de la sección 'caja_rec', 'libro_ventas' o 'cuentas_cobrar'
        let esPresup = $input.closest('.caja_rec').length > 0;
        let esLibro = $input.closest('.libro_venta').length > 0;
        let esCuenta = $input.closest('.cuentas_cobrar').length > 0;

        // Reglas de validación:
        // 1. Validar si el input no está deshabilitado.
        // 2. Validar siempre el campo de reporte.
        // 3. Validar los campos de presupuesto proveedor solo si se encuentra seleccionado en el campo de reporte.
        // 4. Validar los campos de libro de ventas solo si se encuentra seleccionado en el campo de reporte.
        // 5. Validar los campos de cuentas a cobrar solo si se encuentra seleccionado en el campo de reporte.
        if (valor === "") {
            if ($input.closest('.mod').length > 0) {
                camposVacios.push(nombreInput);
            } else if (esPresup && informeActual === "CAJA Y RECAUDACIONES") {
                camposVacios.push(nombreInput);
            } else if (esLibro && informeActual === "LIBRO DE VENTAS") {
                camposVacios.push(nombreInput);
            } else if (esCuenta && informeActual === "CUENTAS A COBRAR") {
                camposVacios.push(nombreInput);
            }
        }
    });

    if (camposVacios.length > 0) {
        alertaLabel("Complete los siguientes campos: <b>" + camposVacios.join(", ") + "</b>.");
    } else if (informeActual === "LIBRO DE VENTAS" && formatoFecha(desde) !== formatoFecha(hasta)) {
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
        url: "/SysGym/Informes/modulos/ventas/controlador.php",
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
    
//capturamos los datos de la tabla clientes en un JSON a través de POST para listarlo
function getPedido() {
    $.ajax({
        method: "POST",
        url: "/SysGym/modulos/ventas/ventas/ventaItem/listas/listaPedido.php",
        data: {
            cliente:$("#cliente").val()
        }
        //en base al JSON traído desde el listaPedido arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{    
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionPedido("+JSON.stringify(item)+")'>"+item.cliente+"</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulPedido").html(fila);
        //le damos un estilo a la lista de Pedido
        $("#listaPedido").attr("style", "display:block; position:absolute; z-index:3000; width:100%;");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el Pedido de VENTA por su key y enviamos el dato al input correspondiente
function seleccionPedido (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulPedido").html();
    $("#listaPedido").attr("style", "display:none;");
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