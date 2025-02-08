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
    $estado = $_POST['fun_estado'];
    $per = $_POST['persona'];
    $ciudad = $_POST['ciu_descripcion'];
    $cargo = $_POST['car_descri'];
    $sucursal = $_POST['suc_descri'];
    $empresa = $_POST['emp_razonsocial'];

    //escapar los datos para que acepte comillas simples
    $fun_estado = pg_escape_string($conexion, $estado);
    $persona = pg_escape_string($conexion, $per);
    $ciu_descripcion = pg_escape_string($conexion, $ciudad);
    $car_descri = pg_escape_string($conexion, $cargo);
    $suc_descri = pg_escape_string($conexion, $sucursal);
    $emp_razonsocial = pg_escape_string($conexion, $empresa);

    //si existe ejecutamos el procedimiento almacenado con los parametros brindados por el post
    $sql = "select sp_abm_funcionarios(
        {$_POST['fun_cod']},
        '{$_POST['fun_fechaingreso']}',
        '$fun_estado',
        {$_POST['per_cod']},
        {$_POST['ciu_cod']},
        {$_POST['car_cod']},
        {$_POST['suc_cod']},
        {$_POST['emp_cod']},
        {$_POST['operacion']},
        {$_POST['usu_cod']},
        '{$_POST['usu_login']}',
        '{$_POST['transaccion']}',
        '$persona',
        '$ciu_descripcion',
        '$car_descri',
        '$suc_descri',
        '$emp_razonsocial'
    );";

    pg_query($conexion, $sql);
    $error = pg_last_error($conexion);
    //Si ocurre un error lo capturamos y lo enviamos al front-end
    if (strpos($error, "1") !== false) {
        $response = array(
            "mensaje" => "ESTE FUNCIONARIO YA EXISTE",
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
    $funCod = "select coalesce (max(fun_cod),0)+1 as codigo from funcionarios;";

    $codigo = pg_query($conexion, $funCod);
    $codigoFun = pg_fetch_assoc($codigo);
    echo json_encode($codigoFun);

} else {
    //Si el post no recibe la operacion realizamos una consulta
    $sql = "select 
            f.fun_cod,
            f.fun_fechaingreso,
            f.per_cod,
            p.per_nrodoc,
            p.per_nombres||' '||p.per_apellidos as persona,
            f.ciu_cod,
            c.ciu_descripcion,
            f.car_cod,
            c2.car_descri,
            f.suc_cod,
            s.suc_descri,
            f.emp_cod,
            e.emp_razonsocial,
            f.fun_estado 
            from funcionarios f
            join personas p on p.per_cod = f.fun_cod 
            join ciudad c on c.ciu_cod = f.ciu_cod 
            join cargos c2 on c2.car_cod = f.car_cod 
            join sucursales s on s.suc_cod = f.suc_cod and s.emp_cod = f.emp_cod 
            join empresa e on e.emp_cod = s.emp_cod
            order by fun_cod;";
    $resultado = pg_query($conexion, $sql);
    $datos = pg_fetch_all($resultado);
    echo json_encode($datos);
}


?>