
//funcion cancelar
let cancelar = () => {
    window.location.reload();
};

//funcion salir
let salir = () => {
    window.location = "/SysGym/menu.php";
};

//funcion para seleccionar tabla
let getTabla = () => {
    $.ajax({
        //Enviamos datos para poder filtrar
        method: "GET",
        url: "/SysGym/Informes/referenciales/compras/controlador.php",
    }) //Cargamos la lista
        .done(function (lista) {
            let fila = "";
            //recorremos el array de objetos
            $.each(lista, function (i, objeto) {
                fila +=
                    "<li class='list-group-item' onclick='seleccionTabla(" + JSON.stringify(objeto) + ")'>" + objeto.tabla + "</li>";
            });
            //cargamos la lista
            $("#ulTabla").html(fila);
            //hacemos visible la lista
            $("#listaTabla").attr("style", "display: block; position:absolute; z-index: 3000;");
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
    $(".mod").attr("class", "form-line mod focused");
};

//función para reportes
const reporte = () => {
    let desde = $("#desde").val();
    let hasta = $("#hasta").val();

    if ($("#tabla").val() == "EMPRESAS") {
        window.location.href = "empresas_reporte/reporteEmpresa.php?desde="+desde+"&hasta="+hasta;
    } else if ($("#tabla").val() == "SUCURSALES"){
        window.location.href = "sucursal_reporte/reporteSucursal.php?desde="+desde+"&hasta="+hasta;
    } else if ($("#tabla").val() == "DEPOSITOS"){
        window.location.href = "depositos_reporte/reporteDepositos.php?desde="+desde+"&hasta="+hasta;
    } else if ($("#tabla").val() == "CIUDADES"){
        window.location.href = "ciudades_reporte/reporteCiudades.php?desde="+desde+"&hasta="+hasta;
    } else if ($("#tabla").val() == "TIPOS DE IMPUESTO"){
        window.location.href = "tipoImpuesto_reporte/reporteTipoImpuesto.php?desde="+desde+"&hasta="+hasta;
    } else if ($("#tabla").val() == "TIPOS DE ITEM"){
        window.location.href = "tipoItem_reporte/reporteTipoItem.php?desde="+desde+"&hasta="+hasta;
    } else if ($("#tabla").val() == "ITEMS"){
        window.location.href = "items_reporte/reporteItems.php?desde="+desde+"&hasta="+hasta;
    } else if ($("#tabla").val() == "TIPOS DE PROVEEDOR"){
        window.location.href = "tipoProveedor_reporte/reporteTipoProveedor.php?desde="+desde+"&hasta="+hasta;
    } else if ($("#tabla").val() == "PROVEEDORES"){
        window.location.href = "proveedores_reporte/reporteProveedores.php?desde="+desde+"&hasta="+hasta;
    }
}

//funcion control vacio
const controlVacio = () => {
    let condicion = "c";

    if ($("#tabla").val() == "") {
        condicion = "i";
    } else if ($("#desde").val() == "") {
        condicion = "i";
    } else if ($("#hasta").val() == "") {
        condicion = "i";
    }

    if (condicion === "i") {
        swal({
            title: "RESPUESTA!!",
            text: "Cargue todos los campos en blanco",
            type: "error",
        });
    } else {
        reporte();
    }
};