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

//funcion cancelar
let cancelar = () => {
    window.location.reload();
};

//funcion salir
let salir = () => {
    window.location = "/SysGym/menu.php";
};

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


//función para reportes
let reporte = () => {
    let desde = $("#desde").val();
    let hasta = $("#hasta").val();
    let pro_cod = $("#pro_cod").val();
    let tiprov_cod = $("#tiprov_cod").val();
    let tipcomp_cod = $("#tipcomp_cod").val();
    let cuenpag_estado = $("#cuentpag_estado").val();

    if($("#informe").val() == "PRESUPUESTOS DEL PROVEEDOR"){
        window.location.href = "presupuesto_prov_reporte/reportePresupuesto.php?pedcom_cod="+pedcom_cod+"&itm_cod="+itm_cod+"&tipitem_cod="+tipitem_cod+"&tipimp_cod="+tipimp_cod;
    } else if($("#informe").val() == "CUENTAS A PAGAR"){
        window.location.href = "cuentasPagar_reporte/reporteCuentasPagar.php?desde="+desde+"&hasta="+hasta+"&pro_cod="+pro_cod+"&tiprov_cod="+tiprov_cod+"&tipcomp_cod="+tipcomp_cod;
    } else if($("#informe").val() == "LIBRO DE COMPRAS"){
        window.location.href = "libroCompras_reporte/reporteLibroCompras.php?desde="+desde+"&hasta="+hasta+"&pro_cod="+pro_cod+"&tiprov_cod="+tiprov_cod+"&cuenpag_estado="+cuenpag_estado;
    }
}

//funcion control vacio
let controlVacio = () => {
    let camposVacios = [];
    let informeActual = $("#informe").val().trim().toUpperCase();

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
        let mensaje = "Complete los siguientes campos: <b>" + camposVacios.join(", ") + "</b>.";
        swal({
            html: true,
            title: "RESPUESTA!!",
            text: mensaje,
            type: "error"
        });
    } else {
        reporte();
    }
};