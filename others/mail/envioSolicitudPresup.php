<?php
session_start();
$u = $_SESSION['usuarios'];

//Establecemos el retorno del documento en formato json
header("Content-type: application/json; charset=utf-8");

require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

//Datos de empresa
$sqlEmp = "select
e.emp_razonsocial,
e.emp_ruc,
s.suc_descri,
s.suc_telefono,
s.suc_email,
s.suc_direccion
from sucursales s 
join empresa e on e.emp_cod = s.emp_cod 
where s.emp_cod = {$u['emp_cod']} and s.suc_cod = {$u['suc_cod']}";

$resEmp = pg_query($conexion, $sqlEmp);
$datosEmp = pg_fetch_all($resEmp);

//Datos de la solicitud
//- Cabecera
$solpre_cod = $_POST['solpre_cod'];
$solpre_email = $_POST['solpre_email'];

$sqlCab = "select 
            spc.solpre_cod,
            spc.solpre_fecha,
            spc.pedcom_cod,
            spc.pro_cod,
            p.pro_ruc,
            p.pro_razonsocial
        from solicitud_presup_cab spc
            join proveedor p on p.pro_cod = spc.pro_cod and p.tiprov_cod = spc.tiprov_cod
        where spc.solpre_cod = $solpre_cod;";

$resCab = pg_query($conexion, $sqlCab);
$datosCab = pg_fetch_assoc($resCab);

//- Detalle

$sqlDet = "select 
                spd.itm_cod,
                i.itm_descri,
                spd.solpredet_cantidad 
            from solicitud_presup_det spd 
                join items i on i.itm_cod = spd.itm_cod
            where spd.solpre_cod = $solpre_cod;";

$resDet = pg_query($conexion, $sqlDet);

require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/vendor/autoload.php"; // Incluir el archivo de autoloading de Dompdf

//Se llaman las clases de PhpSpreadsheet 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
// Configurar PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// En caso de que no hayan detalles se envía un mensaje de error
if (pg_num_rows($resDet) < 1) {
    $response = array(
        "mensaje" => "LA SOLICITUD SELECCIONADA NO CUENTA CON ITEMS DETALLADOS ",
        "tipo" => "error"
    );
    echo json_encode($response);
// Si hay detalles se arma el excel y se envía el correo
} else {

    //------------------------------------------------------------------ EXCEL -----------------------------------------------------------------------

    // Crear Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Escribir cabecera en dos filas
    // Primera fila
    $sheet->setCellValue('A2', 'Pedido Nro:');
    $sheet->setCellValue('B2', $datosCab['pedcom_cod']);
    $sheet->setCellValue('C2', 'Proveedor');
    $sheet->setCellValue('D2', $datosCab['pro_razonsocial']);

    // Segunda fila
    $sheet->setCellValue('A3', 'RUC:');
    $sheet->setCellValue('B3', $datosCab['pro_ruc']);
    $sheet->setCellValue('C3', 'Fecha de Emisión:');
    $sheet->setCellValue('E3', 'Fecha de Vencimiento:');

    //Detalle fila por fila
    //Titulos
    $sheet->setCellValue('A5', 'CODIGO');
    $sheet->setCellValue('B5', 'ITEM O ARTICULO');
    $sheet->setCellValue('C5', 'CANTIDAD');
    $sheet->setCellValue('D5', 'PRECIO UNITARIO');
    $sheet->setCellValue('E5', 'TOTAL');

    // Escribir detalle desde fila 6
    $fila = 6;
    while ($row = pg_fetch_assoc($resDet)) {
        $sheet->setCellValue('A' . $fila, $row['itm_cod']);
        $sheet->setCellValue('B' . $fila, $row['itm_descri']);
        $sheet->setCellValue('C' . $fila, $row['solpredet_cantidad']);
        $fila++;
    }

    // Ajustar ancho automático para columnas usadas
    foreach (range('A', 'E') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Estilo para títulos (negrita + izquierda)
    $styleTitulo = [
        'font' => [
            'underline' => Font::UNDERLINE_SINGLE,
            'bold' => true,
            'color' => ['argb' => 'FF000000'], // negro
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_LEFT,
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
    ];

    //Celdas de titulos de cabecera
    $celdasTitulos = ['A2', 'C2', 'A3', 'C3', 'E3']; 
    // Aplicar estilos a la cabecera
    foreach ($celdasTitulos as $celda) {
        $sheet->getStyle($celda)->applyFromArray($styleTitulo);
    }

    //Estilos para contenido de cabecera
    $styleCabecera =[
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_LEFT,
        ],
    ];

    //Celdas de contenido de cabecera
    $celdasCabecera = ['B2', 'D2', 'B3', 'D3', 'F3']; 
    // Aplicar estilos a la cabecera
    foreach ($celdasCabecera as $celda) {
        $sheet->getStyle($celda)->applyFromArray($styleCabecera);
    }

    // Estilo para títulos (negrita + centro)
    $styleTitDet = [
        'font' => [
            'bold' => true,
            'color' => ['argb' => 'FF000000'], // negro
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
    ];

    // Aplicar estilos a la cabecera
    $sheet->getStyle('A5:E5')->applyFromArray($styleTitDet);

    // Estilo para celdas del detalle (bordes delgados)
    $styleDetalle = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => 'FF000000'],
            ],
        ],
    ];

    // Suponiendo que $fila es la última fila + 1 después del detalle:
    $ultFilaDet = $fila - 1;
    if ($ultFilaDet >= 5) {
        // Aplica el borde a todas las celdas con datos en detalle
        $sheet->getStyle('A5:E' . $ultFilaDet)->applyFromArray($styleDetalle);
    }

    // Guardar Excel en memoria
    $writer = new Xlsx($spreadsheet);
    ob_start();
    $writer->save('php://output');
    $excelData = ob_get_clean();

    //------------------------------------------------------------------ CORREO -----------------------------------------------------------------------

    // Crear una instancia de PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'orbus.gym0@gmail.com'; // Tu dirección de correo
        $mail->Password = 'nhrv fdgu fhjb gcio'; // La contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilitar SSL
        $mail->Port = 587; // Puerto para SSL

        // Remitente y destinatario
        $mail->setFrom('orbus.gym0@gmail.com', 'Orbus Gym');
        $mail->addAddress($solpre_email, $datosCab['pro_razonsocial']);      // Destinatario

        // Adjuntar el PDF generado desde la variable
        $mail->addStringAttachment($excelData, 
                                'Solicitud de presupuesto - pedido nro '.$datosCab['pedcom_cod'].'.xlsx', 
                                'base64', 
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Solicitud de presupuesto'; //asunto del correo
        $mail->Body = '<html lang="es">
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                line-height: 1.6;
                                margin: 20px;
                                color: #333;
                            }
                            .footer {
                                margin-top: 20px;
                                font-size: 0.9em;
                                color: #777;
                            }
                        </style>
                        <body>
                            <p>Estimado '.$datosCab['pro_razonsocial'].'</p>

                            <p>
                                Por medio de la presente, se adjunta a este correo la planilla donde se detallan
                                los artículos en base a los cuales se solicita el presupuesto.
                            </p>

                            <p>
                                De ser posible gustaría que completen los campos vacíos de dicha planilla, y la
                                envíen nuevamente como adjunto al responder a esta solicitud.
                            </p>

                            <p>Esperando su respuesta en la brevedad posible, le deseo un buen resto de jornada.</p>

                            <p>
                                Saludos cordiales,<br />
                                '.$u['per_nombres'].' '.$u['per_apellidos'].'<br />
                                '.$u['perf_descri'].'<br />
                                '.$u['emp_razonsocial'].'<br />
                                '.$u['suc_telefono'].'<br />
                                orbus.gym0@gmail.com
                            </p>
                        </body>';

        //Enviar el correo
        $mail->send();

        $response = array(
            "mensaje" => "Correo enviado con éxito!",
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
}
?>