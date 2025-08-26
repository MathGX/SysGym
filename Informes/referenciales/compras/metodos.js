
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
        $(".empresa, .ciudad").attr("style", "");
        $(".sucursal, .tipo_item, .unidad_medida, .impuesto, .tipo_proveedor").attr("style", "display:none;");
    } else if (tabla == "DEPOSITOS") {
        $(".sucursal").attr("style", "");
        $(".empresa, .ciudad, .tipo_item, .unidad_medida, .impuesto, .tipo_proveedor").attr("style", "display:none;");
    } else if (tabla == "ITEMS") {
        $(".tipo_item, .impuesto, .unidad_medida").attr("style", "");
        $(".empresa, .ciudad, .sucursal, .tipo_proveedor").attr("style", "display:none;");
    } else if (tabla == "PROVEEDORES") {
        $(".tipo_proveedor").attr("style", "");
        $(".empresa, .tipo_item, .unidad_medida, .impuesto, .sucursal").attr("style", "display:none;");
    } else {
        $(".empresa, .ciudad, .sucursal, .tipo_item, .unidad_medida, .impuesto, .tipo_proveedor").attr("style", "display:none;");
    }
    $(".disabledno").removeAttr('disabled', '');
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

//función para reportes
let reporte = () => {
    let emp_cod = $("#emp_cod").val();
    let ciu_cod = $("#ciu_cod").val();
    let suc_cod = $("#suc_cod").val();
    let tipitem_cod = $("#tipitem_cod").val();
    let uni_cod = $("#uni_cod").val();
    let tipimp_cod = $("#tipimp_cod").val();
    let tiprov_cod = $("#tiprov_cod").val();

    if ($("#tabla").val() == "EMPRESAS") {
        window.location.href = "empresas_reporte/reporteEmpresa.php";
    } else if ($("#tabla").val() == "SUCURSALES"){
        window.location.href = "sucursal_reporte/reporteSucursal.php?emp_cod="+emp_cod+"&ciu_cod="+ciu_cod;
    } else if ($("#tabla").val() == "DEPOSITOS"){
        window.location.href = "depositos_reporte/reporteDepositos.php?suc_cod="+suc_cod;
    } else if ($("#tabla").val() == "CIUDADES"){
        window.location.href = "ciudades_reporte/reporteCiudades.php";
    } else if ($("#tabla").val() == "TIPOS DE IMPUESTO"){
        window.location.href = "tipoImpuesto_reporte/reporteTipoImpuesto.php";
    } else if ($("#tabla").val() == "TIPOS DE ITEM"){
        window.location.href = "tipoItem_reporte/reporteTipoItem.php";
    } else if ($("#tabla").val() == "ITEMS"){
        window.location.href = "items_reporte/reporteItems.php?tipitem_cod="+tipitem_cod+"&uni_cod="+uni_cod+"&tipimp_cod="+tipimp_cod;
    } else if ($("#tabla").val() == "TIPOS DE PROVEEDOR"){
        window.location.href = "tipoProveedor_reporte/reporteTipoProveedor.php";
    } else if ($("#tabla").val() == "PROVEEDORES"){
        window.location.href = "proveedores_reporte/reporteProveedores.php?tiprov_cod="+tiprov_cod;
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