<?php

header('Content-type: application/json; charset=utf-8');

//importar la clase conexion.php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

// Importar la función para capturar el país, región y ciudad de la IP
include "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/extension/importPHP.php"; 

$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$actpas_usu = $_POST['actpas_usu'];
$actpas_clave = $_POST['actpas_clave'];
// $actpas_fecha = $_POST['actpas_fecha'];
// $actpas_hora = $_POST['actpas_hora'];
$actpas_obs = $_POST['actpas_obs'];
$actpas_ip = file_get_contents('https://api.ipify.org');
$correo = $_POST['correo'];

// Llamar a la función para capturar el país, región y ciudad de la IP
list($actpas_pais_ip, $actpas_region_ip, $actpas_ciudad_ip) = capturarPaisIP($actpas_ip);


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Generar la consulta SQL para insertar los datos en la tabla 'acceso'
$sql = "insert into actualizar_pass_user (
            actpas_cod, 
            actpas_usu, 
            actpas_clave,
            actpas_fecha, 
            actpas_hora, 
            actpas_obs,
            actpas_intentos,
            actpas_ip,
            actpas_pais_ip,
            actpas_region_ip,
            actpas_ciudad_ip) 
        values (
            (select coalesce(max(actpas_cod), 0) + 1 from actualizar_pass_user), 
            '$actpas_usu', 
            '$actpas_clave',
            current_date, 
            current_time, 
            '$actpas_obs',
            0,
            '$actpas_ip',
            upper('$actpas_pais_ip'),
            upper('$actpas_region_ip'),
            upper('$actpas_ciudad_ip'))";

// Ejecutar la consulta
$resultado = pg_query($conexion, $sql);

// Comprobar si la consulta se ejecutó correctamente
if ($resultado) {
    //-------------------------------------------------CORREO-------------------------------------------------//
    require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/vendor/autoload.php"; // Incluir el archivo de autoloading de Dompdf

    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'orbus.gym0@gmail.com'; // Tu dirección de correo
        $mail->Password = 'ahop dcgg wdze uloy'; // La contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Habilitar SSL
        $mail->Port = 465; // Puerto para SSL

        // Remitente y destinatario
        $mail->setFrom('orbus.gmail0@gmail.com', 'Orbus Gym');
        $mail->addAddress($correo, 'User');

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Actualizacion de credenciales';
        $mail->Body = '<html lang="es">
                        <head>
                            <style>
                                body {
                                    font-family: Arial, sans-serif;
                                    background-color: #f4f4f4;
                                    color: #333;
                                    padding: 20px;
                                }
                                .container {
                                    background-color: #fff;
                                    border-radius: 5px;
                                    padding: 20px;
                                    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                                }
                                h1 {
                                    color: #007BFF;
                                }
                                .footer {
                                    margin-top: 20px;
                                    font-size: 0.9em;
                                    color: #777;
                                }
                            </style>
                        </head>
                        <body>
                            <div class="container">
                                <h1>¡Clave de Recuperación Recibida!</h1>
                                <p>Hemos recibido su solicitud para el cambio de contraseña del usuario <strong>'.$actpas_usu.'</strong> </p>
                                <p>Para ello, ingrese la siguiente clave: <h2 style="color: #28a745;"><strong>'.$actpas_clave.'</strong></h2> </p>
                                <p>Esta clave no debe ser divulgada.</p>
                                <p>Tenga en cuenta que solo tiene con 3 intentos.</p>
                                
                                <div class="footer">
                                    <p>Saludos cordiales,</p>
                                    <p>Administración</p>
                                </div>
                            </div>
                        </body>
                        </html>';

        // Enviar el correo
        $mail->send();

        $response = array(
            "mensaje" => "Clave de recuperación enviada exitosamente!",
            "tipo" => "success"
        );
        echo json_encode($response);

    } catch (PHPMailerException $e) {
        $response = array(
            "mensaje" => "Error al enviar el correo electrónico: " . $e->getMessage(),
            "tipo" => "error"
        );
        echo json_encode($response);
    }
} else {
    echo json_encode(array("status" => "error", "message" => "Error de registro"));
}

?>