<?php

/*---------------------------------------------------------------------------------------------------------------
Función para obtener la configuración de la sucursal y empresa
Recibe el código de la sucursal, el código de la empresa y opcionalmente el código de la configuración*/
function obtenerConfig($suc_cod, $emp_cod, $conf_cod = null) {
    // Se incluye la conexión a la base de datos
    global $conexion;

    // Se consulta la configuración de la sucursal y empresa
    $sql = "select 
                c.conf_validacion
            from suc_config sc
                join configuraciones c on c.conf_cod = sc.conf_cod 
            where sc.suc_cod = $suc_cod and sc.emp_cod = $emp_cod";

    // Si se proporciona un código de configuración, se agrega a la consulta
    if ($conf_cod !== null) {
        $sql.=" and sc.conf_cod = $conf_cod";
        // Se ejecuta la consulta y se obtiene el resultado
        $resultado = pg_query($conexion, $sql);
        // Se guarda el resultado en un array asociativo
        $configuracion = pg_fetch_assoc($resultado);
        // Se retorna el valor de la configuración
        return $configuracion['conf_validacion'];

    // Si no se proporciona un código de configuración, se obtienen todas las configuraciones
    } else {
        // Se ejecuta la consulta y se obtiene el resultado
        $resultado = pg_query($conexion, $sql);
        // Se guarda el resultado en un array asociativo
        $configuraciones = pg_fetch_all($resultado);
        // Se retorna el array de configuraciones
        return $configuraciones;
    }
}

/*---------------------------------------------------------------------------------------------------------------
Funcion para obtener los datos del pais de origen de la ip que accede al sistema*/
function capturarPaisIP($ip) {
    $url = "https://ipwhois.app/json/$ip?lang=es";  // API que devuelve datos en JSON
    $respuesta = file_get_contents($url);
    $datos = json_decode($respuesta, true);
    if($datos && isset($datos['country'])) {
        return [$datos['country'], $datos['region'], $datos['city']]; // Devuelve país, región y ciudad asociado a la IP
    } else {
        return ["País no encontrado", "Región no encontrada", "Ciudad no encontrada"]; // En caso de error
    }
}

?>