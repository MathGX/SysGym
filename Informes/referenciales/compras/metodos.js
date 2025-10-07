//Datos de sesion del usuario
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

//funcion cancelar
let cancelar = () => {
    window.location.reload();
};

//funcion salir
let salir = () => {
    window.location = "/SysGym/menu.php";
};

//funcion para mostrar inputs de filtros
let mostrarFiltros = () => {
    let tabla = $("#tabla").val();
    if (tabla == "SUCURSALES") {
        //Se muestran los filtros necesarios
        $(".empresa, .ciudad").attr("style", "").find('.foc').removeClass('focused').find('.form-control').val('');
        //Se ocultan los filtros innecesarios
        $(".sucursal, .tipo_item, .unidad_medida, .impuesto, .tipo_proveedor").attr("style", "display:none;");
    } else if (tabla == "DEPOSITOS") {
        //Se muestran los filtros necesarios
        $(".sucursal").attr("style", "").find('.foc').removeClass('focused').find('.form-control').val('');
        //Se ocultan los filtros innecesarios
        $(".empresa, .ciudad, .tipo_item, .unidad_medida, .impuesto, .tipo_proveedor").attr("style", "display:none;");
    } else if (tabla == "ITEMS") {
        //Se muestran los filtros necesarios
        $(".tipo_item, .impuesto, .unidad_medida").attr("style", "").find('.foc').removeClass('focused').find('.form-control').val('');
        //Se ocultan los filtros innecesarios
        $(".empresa, .ciudad, .sucursal, .tipo_proveedor").attr("style", "display:none;");
    } else if (tabla == "PROVEEDORES") {
        //Se muestran los filtros necesarios
        $(".tipo_proveedor").attr("style", "").find('.foc').removeClass('focused').find('.form-control').val('');
        //Se ocultan los filtros innecesarios
        $(".empresa, .tipo_item, .unidad_medida, .impuesto, .sucursal, .ciudad").attr("style", "display:none;");
    } else {
        $(".empresa, .ciudad, .sucursal, .tipo_item, .unidad_medida, .impuesto, .tipo_proveedor").attr("style", "display:none;");
    }
    $(".disabledno").removeAttr('disabled', '');
    datusUsuarios();
};

//función para reportes
let reporte = () => {
    let desde = $("#desde").val() || 0;
    let hasta = $("#hasta").val() || 0;
    let emp_cod = $("#emp_cod").val() || 0;
    let ciu_cod = $("#ciu_cod").val() || 0;
    let suc_cod = $("#suc_cod").val() || 0;
    let tipitem_cod = $("#tipitem_cod").val() || 0;
    let uni_cod = $("#uni_cod").val() || 0;
    let tipimp_cod = $("#tipimp_cod").val() || 0;
    let tiprov_cod = $("#tiprov_cod").val() || 0;

    if ($("#tabla").val() == "EMPRESAS") {
        window.open("empresas_reporte/reporteEmpresa.php");
    } else if ($("#tabla").val() == "SUCURSALES"){
        window.open("sucursal_reporte/reporteSucursal.php?desde="+desde+"&hasta="+hasta+"&emp_cod="+emp_cod+"&ciu_cod="+ciu_cod);
    } else if ($("#tabla").val() == "DEPOSITOS"){
        window.open("depositos_reporte/reporteDepositos.php?desde="+desde+"&hasta="+hasta+"&suc_cod="+suc_cod);
    } else if ($("#tabla").val() == "CIUDADES"){
        window.open("ciudades_reporte/reporteCiudades.php?desde="+desde+"&hasta="+hasta);
    } else if ($("#tabla").val() == "TIPOS DE IMPUESTO"){
        window.open("tipoImpuesto_reporte/reporteTipoImpuesto.php?desde="+desde+"&hasta="+hasta);
    } else if ($("#tabla").val() == "TIPOS DE ITEM"){
        window.open("tipoItem_reporte/reporteTipoItem.php?desde="+desde+"&hasta="+hasta);
    } else if ($("#tabla").val() == "ITEMS"){
        window.open("items_reporte/reporteItems.php?desde="+desde+"&hasta="+hasta+"&tipitem_cod="+tipitem_cod+"&uni_cod="+uni_cod+"&tipimp_cod="+tipimp_cod);
    } else if ($("#tabla").val() == "TIPOS DE PROVEEDOR"){
        window.open("tipoProveedor_reporte/reporteTipoProveedor.php");
    } else if ($("#tabla").val() == "PROVEEDORES"){
        window.open("proveedores_reporte/reporteProveedores.php?desde="+desde+"&hasta="+hasta+"&tiprov_cod="+tiprov_cod);
    }
}

//funcion control vacio
const controlVacio = () => {

    if ($("#tabla").val() == "") {
        swal({
            title: "RESPUESTA!!",
            text: "Seleccione una tabla",
            type: "error",
        });
    } else {
        reporte();
    }
};

//funcion para seleccionar Tabla
let getTabla = () => {
    $.ajax({
        //Enviamos datos para poder filtrar
        method: "POST",
        url: "/SysGym/Informes/referenciales/compras/controlador.php",
        data: {
            tabla:$("#tabla").val()
        }
    }) //Cargamos la lista
        .done(function (lista) {
            let fila = "";
            //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
            if(lista.true == true){
                fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
            }else{    
                $.each(lista,function(i, item) {
                    fila += "<li class='list-group-item' onclick='seleccionTabla("+JSON.stringify(item)+")'>"+item.tabla+"</li>";
                });
            }
            //cargamos la lista
            $("#ulTabla").html(fila);
            //hacemos visible la lista
            $("#listaTabla").attr("style", "display: block; position:absolute; z-index: 3000; width:100%;");
        })
        .fail(function (a, b, c) {
            swal("ERROR", c, "error");
        });
};

//funcion selecccionar Tabla
let seleccionTabla = (datos) => {
    //Enviamos los datos a su respectivo input
    Object.keys(datos).forEach((key) => {
        $("#" + key).val(datos[key]);
    });
    /* Vaciamos y ocultamos la lista */
    $("#ulTabla").html();
    $("#listaTabla").attr("style", "display: none;");
    $(".focus").attr("class", "form-line focus focused");
    //Llamamos a la funcion para mostrar los filtros
    mostrarFiltros();
};

//capturamos los datos de la tabla Ciudad en un JSON a través de POST para listarlo
function getCiudades() {
    $.ajax({
        method: "POST",
        url: "/SysGym/referenciales/compras/sucursales/listas/listaCiudades.php",
        data: {
            ciu_descripcion: $("#ciu_descripcion").val()
        }
    }).done(function(lista) {
        var fila = "";
        if (lista.true == true) {
            fila = "<li class='list-group-item'>" + lista.fila + "</li>";
        } else {
            $.each(lista, function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionCiudades(" + JSON.stringify(item) + ")'>" + item.ciu_descripcion + "</li>";
            });
        }
        $("#ulCiudades").html(fila);
        $("#listaCiudades").attr("style", "display:block; position:absolute; z-index:3000; width:100%");
    }).fail(function(a, b, c) {
        swal("ERROR", c, "error");
    });
}

//seleccionamos la Ciudad por su key y enviamos el dato al input correspondiente
function seleccionCiudades (datos) {
    Object.keys(datos).forEach(key => {
        $("#" + key).val(datos[key]);
    });
    $("#ulCiudades").html();
    $("#listaCiudades").attr("style", "display:none;");
    $("#ciu_descripcion").closest(".form-line").attr("class", "form-line foc focused");
}

//capturamos los datos de la tabla TipoItem en un JSON a través de POST para listarlo
function getTipoItem() {
    $.ajax({
        method: "POST",
        url: "/SysGym/referenciales/compras/items/listas/listaTipoItem.php",
        data: {
            tipitem_descri:$("#tipitem_descri").val()
        }
        //en base al JSON traído desde el listaTipoItem arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionTipoItem("+JSON.stringify(item)+")'>"+item.tipitem_descri+"</li>";
            });
        }
        //enviamos a los input correspondientes del conjunto de filas
        $("#ulTipoItem").html(fila);
        //le damos un estilo a la lista de TipoItem
        $("#listaTipoItem").attr("style", "display:block; position:absolute; z-index:3000; width:100%");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el tipo de item por su key y enviamos el dato al input correspondiente
function seleccionTipoItem (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulTipoItem").html();
    $("#listaTipoItem").attr("style", "display:none;");
    $("#tipitem_descri").closest(".form-line").attr("class", "form-line foc focused");
}

//capturamos los datos de la tabla TipoImpuesto en un JSON a través de POST para listarlo
function getTipoImpuesto() {
    $.ajax({
        method: "POST",
        url: "/SysGym/referenciales/compras/items/listas/listaTipoImpuesto.php",
        data: {
            tipimp_descri:$("#tipimp_descri").val()
        }
        //en base al JSON traído desde el listaTipoImpuesto arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionTipoImpuesto("+JSON.stringify(item)+")'>"+item.tipimp_descri+"</li>";
            });
        }
        //enviamos a los input correspondientes del conjunto de filas
        $("#ulTipoImpuesto").html(fila);
        //le damos un estilo a la lista de TipoImpuesto
        $("#listaTipoImpuesto").attr("style", "display:block; position:absolute; z-index:3000; width:100%");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el modulo por su key y enviamos el dato al input correspondiente
function seleccionTipoImpuesto (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulTipoImpuesto").html();
    $("#listaTipoImpuesto").attr("style", "display:none;");
    $("#tipimp_descri").closest(".form-line").attr("class", "form-line foc focused");
}

//capturamos los datos de la tabla unidad de medida en un JSON a través de POST para listarlo
function getUniMedida() {
    $.ajax({
        method: "POST",
        url: "/SysGym/referenciales/compras/items/listas/listaUniMedida.php",
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
    $("#uni_descri").closest(".form-line").attr("class", "form-line foc focused");
}

//capturamos los datos de la tabla Tipo Proveedor en un JSON a través de POST para listarlo
function getTipoProv() {
    $.ajax({
        method: "POST",
        url: "/SysGym/referenciales/compras/proveedores/listas/listaTipoProv.php",
        data: {
            tiprov_descripcion:$("#tiprov_descripcion").val()
        }
        //en base al JSON traído desde el listaProveedores arrojamos un resultado
    }).done(function(lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if(lista.true == true){
            fila = "<li class='list-group-item' >"+lista.fila+"</li>"; 
        }else{    
            $.each(lista,function(i, item) {
                fila += "<li class='list-group-item' onclick='seleccionTipoProv("+JSON.stringify(item)+")'>"+item.tiprov_descripcion+"</li>";
            });
        }
        //enviamos a los input correspondientes del conjunto de filas
        $("#ulTipoProv").html(fila);
        //le damos un estilo a la lista de TipoProv
        $("#listaTipoProv").attr("style", "display:block; position:absolute; z-index:3000; width:100%");
    }).fail(function (a,b,c) {
        swal("ERROR",c,"error");
    })
}

//seleccionamos el modulo por su key y enviamos el dato al input correspondiente
function seleccionTipoProv (datos) {
    Object.keys(datos).forEach(key =>{
        $("#"+key).val(datos[key]);
    });
    $("#ulTipoProv").html();
    $("#listaTipoProv").attr("style", "display:none;");
    $("#tiprov_descripcion").closest(".form-line").attr("class", "form-line foc focused");
}