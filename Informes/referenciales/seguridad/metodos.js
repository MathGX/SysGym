//funcion habilitar inputs
const habilitarCampos = () => {
    if ($("#tabla").val() == "ACCESO" || $("#tabla").val()== "USUARIOS") {
        $("#desde").attr("type", "date");
        $("#hasta").attr("type", "date");
    } else {
        $("#desde").attr("type", "text");
        $("#hasta").attr("type", "text");
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
        url: "/SysGym/Informes/referenciales/seguridad/controlador.php",
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

//función para reportes
const reporte = () => {
    let desde = $("#desde").val();
    let hasta = $("#hasta").val();

    if ($("#tabla").val() == "MODULOS") {
        window.location.href = "modulos_reporte/reporteModulo.php?desde="+desde+"&hasta="+hasta;
    } else if($("#tabla").val() == "ACCESO"){
        window.location.href = "acceso_reporte/reporteAcceso.php?desde="+desde+"&hasta="+hasta;
    } else if($("#tabla").val() == "PERFILES"){
        window.location.href = "perfiles_reporte/reportePerfiles.php?desde="+desde+"&hasta="+hasta;
    } else if($("#tabla").val() == "PERMISOS"){
        window.location.href = "permisos_reporte/reportePermisos.php?desde="+desde+"&hasta="+hasta;
    } else if($("#tabla").val() == "GUI"){
        window.location.href = "gui_reporte/reporteGUI.php?desde="+desde+"&hasta="+hasta;
    } else if($("#tabla").val() == "USUARIOS"){
        window.location.href = "usuarios_reporte/reporteUsuarios.php?desde="+desde+"&hasta="+hasta;
    } else if($("#tabla").val() == "ASIGNACIÓN DE PERMISOS"){
        window.location.href = "asignacion_reporte/reporteAsignacion.php?desde="+desde+"&hasta="+hasta;
    } else if($("#tabla").val() == "GUI POR PERFIL"){
        window.location.href = "guiPerfiles_reporte/reporteGuiPerfiles.php?desde="+desde+"&hasta="+hasta;
    } else if($("#tabla").val() == "PERMISO POR PERFIL"){
        window.location.href = "perfilPermisos_reporte/reportePerfilPermisos.php?desde="+desde+"&hasta="+hasta;
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