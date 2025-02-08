
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
        url: "/SysGym/Informes/referenciales/servicios/controlador.php",
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

    if ($("#tabla").val() == "PERSONAS") {
        window.location.href = "personas_reporte/reportepersonas.php?desde="+desde+"&hasta="+hasta;
    } else if ($("#tabla").val() == "CARGOS"){
        window.location.href = "cargos_reporte/reporteCargos.php?desde="+desde+"&hasta="+hasta;
    } else if ($("#tabla").val() == "FUNCIONARIOS"){
        window.location.href = "funcionarios_reporte/reporteFuncionarios.php?desde="+desde+"&hasta="+hasta;
    } else if ($("#tabla").val() == "DIAS"){
        window.location.href = "dias_reporte/reporteDias.php?desde="+desde+"&hasta="+hasta;
    } else if ($("#tabla").val() == "TIPOS DE EQUIPO"){
        window.location.href = "tipoEquipo_reporte/reporteTipoEquipo.php?desde="+desde+"&hasta="+hasta;
    } else if ($("#tabla").val() == "EQUIPOS"){
        window.location.href = "equipos_reporte/reporteEquipos.php?desde="+desde+"&hasta="+hasta;    
    } else if ($("#tabla").val() == "PARAMETROS DE MEDICION"){
        window.location.href = "paraMedicion_reporte/reporteParaMedicion.php?desde="+desde+"&hasta="+hasta; 
    } else if ($("#tabla").val() == "UNIDADES DE MEDIDA"){
        window.location.href = "uniMedi_reporte/reporteUniMedi.php?desde="+desde+"&hasta="+hasta;
    } else if ($("#tabla").val() == "EJERCICIOS"){
        window.location.href = "ejercicios_reporte/reporteEjercicioS.php?desde="+desde+"&hasta="+hasta;
    } else if ($("#tabla").val() == "TIPOS DE RUTINA"){
        window.location.href = "tipoRutina_reporte/reporteTipoRutina.php?desde="+desde+"&hasta="+hasta;
    } else if ($("#tabla").val() == "TIPOS DE PLAN ALIMENTICIO"){
        window.location.href = "tipPlanAlim_reporte/reporteTipPlanAlim.php?desde="+desde+"&hasta="+hasta;
    } else if ($("#tabla").val() == "COMIDAS"){
        window.location.href = "comidas_reporte/reporteComidas.php?desde="+desde+"&hasta="+hasta;
    } else if ($("#tabla").val() == "HORARIOS DE COMIDA"){
        window.location.href = "horaComida_reporte/reporteHoraComida.php?desde="+desde+"&hasta="+hasta;
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