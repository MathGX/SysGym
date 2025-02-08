
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
        url: "/SysGym/Informes/referenciales/ventas/controlador.php",
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

    if ($("#tabla").val() == "CLIENTES") {
        window.location.href = "clientes_reporte/reporteClientes.php?desde="+desde+"&hasta="+hasta;
    } else if ($("#tabla").val() == "ENTIDADES FINANCIERAS"){
        window.location.href = "entidadEmisora_reporte/reporteEntidadEmisora.php?desde="+desde+"&hasta="+hasta;
    } else if ($("#tabla").val() == "MARCAS DE TARJETA"){
        window.location.href = "marcaTarjeta_reporte/reporteMarcaTarjeta.php?desde="+desde+"&hasta="+hasta;
    } else if ($("#tabla").val() == "ENTIDADES ADHERIDAS"){
        window.location.href = "entidadAdherida_reporte/reporteEntidadAdherida.php?desde="+desde+"&hasta="+hasta;
    } else if ($("#tabla").val() == "CAJAS"){
        window.location.href = "cajas_reporte/reporteCajas.php?desde="+desde+"&hasta="+hasta;
    } else if ($("#tabla").val() == "FORMAS DE COBRO"){
        window.location.href = "formaCobro_reporte/reporteFormaCobro.php?desde="+desde+"&hasta="+hasta;
    } else if ($("#tabla").val() == "TIPOS DE DOCUMENTO"){
        window.location.href = "tipoDoc_reporte/reporteTipoDoc.php?desde="+desde+"&hasta="+hasta;
    } else if ($("#tabla").val() == "TIPOS DE COMPROBANTE"){
        window.location.href = "tipoComp_reporte/tipoComp_reporte.php?desde="+desde+"&hasta="+hasta;
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