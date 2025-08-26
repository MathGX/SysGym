<?php

header('Content-type: application/json; charset=utf-8');

//importar la clase conexion.php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

// Importar la función para capturar el país, región y ciudad de la IP
include "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/extension/importPHP.php"; 

$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$accon_usu = $_POST['accon_usu'];
$accon_clave = $_POST['accon_clave'];
$accon_fecha = $_POST['accon_fecha'];
// $accon_hora = $_POST['accon_hora'];
$accon_obs = $_POST['accon_obs'];
$accon_ip = file_get_contents('https://api.ipify.org');
$correo = $_POST['correo'];

// Llamar a la función para capturar el país, región y ciudad de la IP
list($accon_pais_ip, $accon_region_ip, $accon_ciudad_ip) = capturarPaisIP($accon_ip);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// Generar la consulta SQL para insertar los datos en la tabla 'acceso'
$sql = "insert into acceso_control (
            accon_cod, 
            accon_usu, 
            accon_clave,
            accon_fecha, 
            accon_hora, 
            accon_obs,
            accon_intentos,
            accon_ip,
            accon_pais_ip,
            accon_region_ip,
            accon_ciudad_ip) 
        values (
            (select coalesce(max(accon_cod), 0) + 1 from acceso_control), 
            '$accon_usu', 
            '$accon_clave',
            '$accon_fecha', 
            current_time, 
            '$accon_obs',
            0,
            '$accon_ip',
            upper('$accon_pais_ip'),
            upper('$accon_region_ip'),
            upper('$accon_ciudad_ip'))";

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
        $mail->setFrom('orbus.gmail0@gmail.com', 'Orbus');
        $mail->addAddress($correo, 'User');

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Acceso al sistema';
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
                                    <h1>¡Clave de Acceso Recibida!</h1>
                                    <p>Hemos recibido su solicitud de acceso al sistema de Orbus Gym, ingrese la siguiente clave:</p>
                                    <h2 style="color: #28a745;"><strong>'.$accon_clave.'</strong></h2>
                                    <p>Asegúrese de mantener esta clave en un lugar seguro.</p>
                                    <p>No olvide que solo cuenta con 3 intentos.</p>
                                    
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
            "mensaje" => "Correo enviado con éxito al mail del usuario!",
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