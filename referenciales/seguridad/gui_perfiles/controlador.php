<?php
//Establecemos el retorno del documento en formato json
header("Content-type: application/json; charset=utf-8");
//Solicitamos la clase de Conexion
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

//Consultamos si existe la variable operacion
if (isset($_POST['operacion'])) {

    //captura de datos desde el front-end
    $estado = $_POST['guiperf_estado'];
    $perfil = $_POST['perf_descri'];
    $gui = $_POST['gui_descri'];
    $modulo = $_POST['mod_descri'];

    //escapar los datos para que acepte comillas simples
    $guiperf_estado = pg_escape_string($conexion, $estado);
    $perf_descri = pg_escape_string($conexion, $perfil);
    $gui_descri = pg_escape_string($conexion, $gui);
    $mod_descri = pg_escape_string($conexion, $modulo);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_guiPerfiles(
        {$_POST['guiperf_cod']},
        {$_POST['perf_cod']},
        {$_POST['gui_cod']},
        {$_POST['mod_cod']},
        '$guiperf_estado',
        {$_POST['operacion']},
        {$_POST['usu_cod']},
        '{$_POST['usu_login']}',
        '{$_POST['transaccion']}',
        '$perf_descri',
        '$gui_descri',
        '$mod_descri'
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "1") !== false) {
        $response = array(
            "mensaje" => "ESTE PERFIL YA CUENTA CON EL GUI",
            "tipo" => "error"
        );
    } else {
        $response = array(
            "mensaje" => pg_last_notice($conexion),
            "tipo" => "success"
        );
    }
    echo json_encode($response);

} else if (isset($_POST['consulCod']) == 1) {
    //Se obtiene el valor para asignar al codigo
    $guiperfCod = "select coalesce (max(guiperf_cod),0)+1 as codigo from gui_perfiles;";

    $codigo = pg_query($conexion, $guiperfCod);
    $codigoGuiPerf = pg_fetch_assoc($codigo);
    echo json_encode($codigoGuiPerf);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select
            gp.guiperf_cod,
            gp.perf_cod,
            p.perf_descri,
            gp.gui_cod,
            g.gui_descri,
            gp.mod_cod,
            m.mod_descri,
            gp.guiperf_estado 
            from gui_perfiles gp 
            join perfiles p on p.perf_cod = gp.perf_cod 
            join gui g on g.gui_cod = gp.gui_cod and g.mod_cod = gp.mod_cod
            join modulos m on m.mod_cod = g.mod_cod
            order by guiperf_cod;";
    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>