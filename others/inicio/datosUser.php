<?php
//Establecemos el retorno del documento en formato json
header("Content-type: application/json; charset=utf-8");

//iniciamos variables de sesión
session_start();

$usuario = ['usu_cod' => $_SESSION['usuarios']['usu_cod'],
            'usu_login' => $_SESSION['usuarios']['usu_login'],
            'suc_cod' => $_SESSION['usuarios']['suc_cod'],
            'suc_descri' => $_SESSION['usuarios']['suc_descri'],
            'emp_cod' => $_SESSION['usuarios']['emp_cod'],
            'perf_cod' => $_SESSION['usuarios']['perf_cod'],
            'emp_razonsocial' => $_SESSION['usuarios']['emp_razonsocial'],
            //'emp_timbrado' => $_SESSION['usuarios']['emp_timbrado'],
            'apcier_cod' => $_SESSION['numApcier']['codigo'] ?? '',
            'caj_cod' => $_SESSION['numApcier']['caja'] ?? '',
            'caj_descri' => $_SESSION['numApcier']['cajDescri'] ?? ''
        ];

echo json_encode($usuario);



?>