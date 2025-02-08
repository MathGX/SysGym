//funcion hablitarCampos
const habilitarCampos = () => {
    if ($('#tabla').val()=='STOCK') {
        $('.depo').attr('style', 'display:inline');
        $('.codigo').attr('style', 'display:none');
    }else{
        $('.depo').attr('style', 'display:none');
        $('.codigo').attr('style', 'display:inline');
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

//funcion para seleccionar tabla
let getTabla = () => {
    $.ajax({
        //Enviamos datos para poder filtrar
        method: "GET",
        url: "/SysGym/Informes/modulos/compras/controlador.php",
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
    habilitarCampos();
};
    
//capturamos los datos de la tabla Deposito en un JSON a través de POST para listarlo
function getDeposito() {
    $.ajax({
        method: "POST",
        url: "/SysGym/Informes/modulos/compras/listaDeposito.php",
        data: {
            dep_descri: $("#dep_descri").val(),
            suc_cod: $("#suc_cod").val(),
            emp_cod: $("#emp_cod").val(),
        }
        //en base al JSON traído desde el listaDeposito arrojamos un resultado
    }).done(function (lista) {
        //el JSON de respuesta es mostrado en una lista
        var fila = "";
        //consultamos si el dato tipeado el front-end existe en la base de datos, si es así, se muestra en la lista
        if (lista.true == true) {
            fila = "<li class='list-group-item' >" + lista.fila + "</li>";
        } else {
            $.each(lista, function (i, item) {
                fila += "<li class='list-group-item' onclick='seleccionDeposito(" + JSON.stringify(item) + ")'>" + item.dep_descri + "</li>";
            });
        }
        //enviamos a los input correspondientes de el conjunto de filas
        $("#ulDeposito").html(fila);
        //le damos un estilo a la lista de Deposito
        $("#listaDeposito").attr("style", "display:block; position:absolute; z-index:3000;");
    }).fail(function (a, b, c) {
        swal("ERROR", c, "error");
    });
}
    
    //seleccionamos el deposito por su key y enviamos el dato al input correspondiente
    function seleccionDeposito(datos) {
    Object.keys(datos).forEach(key => {
        $("#" + key).val(datos[key]);
    });
    $("#ulDeposito").html();
    $("#listaDeposito").attr("style", "display:none;");
    $(".foc").attr("class", "form-line foc focused");
}



//función para reportes
const reporte = () => {
    let desde = $("#desde").val();
    let hasta = $("#hasta").val();
    let deposito = $("#dep_descri").val();

    if($("#tabla").val() == "PEDIDOS DE COMPRA"){
        window.location.href = "pedido_compra_reporte/reportePedidos.php?desde="+desde+"&hasta="+hasta;
    } else if($("#tabla").val() == "PRESUPUESTO DE PORVEEDORES"){
        window.location.href = "presupuesto_prov_reporte/reportePresupuesto.php?desde="+desde+"&hasta="+hasta;
    } else if($("#tabla").val() == "ORDENES DE COMPRA"){
        window.location.href = "orden_compra_reporte/reporteOrdenes.php?desde="+desde+"&hasta="+hasta;
    } else if($("#tabla").val() == "COMPRAS"){
        window.location.href = "compra_reporte/reporteCompras.php?desde="+desde+"&hasta="+hasta;
    } else if($("#tabla").val() == "CUENTAS A PAGAR"){
        window.location.href = "cuentasPagar_reporte/reporteCuentasPagar.php?desde="+desde+"&hasta="+hasta;
    } else if($("#tabla").val() == "LIBRO DE COMPRAS"){
        window.location.href = "libroCompras_reporte/reporteLibroCompras.php?desde="+desde+"&hasta="+hasta;
    } else if($("#tabla").val() == "AJUSTES DE INVENTARIO"){
        window.location.href = "ajuste_inven_reporte/reporteaAjuste.php?desde="+desde+"&hasta="+hasta;
    } else if($("#tabla").val() == "NOTAS DE COMPRA"){
        window.location.href = "nota_compra_reporte/reporteNotas.php?desde="+desde+"&hasta="+hasta;
    } else if($("#tabla").val() == "STOCK"){
        window.location.href = "stock_reporte/reporteStock.php?deposito="+deposito;
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

    if (($("#tabla").val() === "STOCK") && ($("#dep_descri").val() !== "")) {
        reporte();
    }else if (condicion === "i") {
        swal({
            title: "RESPUESTA!!",
            text: "Cargue todos los campos en blanco",
            type: "error",
        });
    } else {
        reporte();
    }
};