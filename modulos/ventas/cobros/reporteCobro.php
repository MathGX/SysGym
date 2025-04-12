<?php
session_start();
$u = $_SESSION['usuarios'];

$fechaActual = date('d-m-Y');

$letras = new NumberFormatter('es', NumberFormatter::SPELLOUT);

ob_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RECIBO DE PAGO</title>
</head>
<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$sqlEmp = "select
e.emp_razonsocial,
e.emp_ruc,
s.suc_descri,
s.suc_telefono,
s.suc_email,
s.suc_direccion,
initcap(c.ciu_descripcion) ciudad
from sucursales s
join empresa e on e.emp_cod = s.emp_cod
join ciudad c on c.ciu_cod = s.ciu_cod
where s.emp_cod = {$u['emp_cod']} and s.suc_cod = {$u['suc_cod']}";

$resEmp = pg_query($conexion, $sqlEmp);
$datosEmp = pg_fetch_all($resEmp);


$cobr_cod = $_GET['cobr_cod'];

$sql = "select
    cd.cobr_cod codigo,
    sum(cd.cobrdet_monto) monto,
    to_char(to_date(max(cc.cobr_fecha),'dd-mm-yyyy'),'DD \"de\" TMMonth \"de\" YYYY') fecha,
    cd.cliente cliente,
    cd.cobrdet_nrocuota||'/'||cd.cuencob_cuotas cuotas,
    cd.ven_nrofac factura
from v_cobros_det cd
    join v_cobros_cab cc on cd.cobr_cod = cc.cobr_cod
where cd.cobr_cod = $cobr_cod
group by 1,4,5,6;";


$resultado = pg_query($conexion, $sql);
$datos = pg_fetch_all($resultado);


?>

<body>

    <style>

        .contenedor {
            width: calc(100% - 30px); /* Compensa los 15px de margen izquierdo y derecho */
            margin: 0 auto; /* Centra el contenedor */
            padding: 20px;
            border: 1px solid black;
            border-radius: 30px;
            box-sizing: border-box; /* Incluye padding y border en el ancho total */
        }

        .titulo_izq {
            width: 33.3%; 
            border: 1px solid black;  
            border-radius: 10px;  
            text-align: center;
        }

        .titulo_med {
            position: absolute; 
            top: 30; 
            left: 50%; 
            text-align: center; 
            transform: translateX(-50%); 
            space-blank: normal; 
            width:30%;
        }

        .titulo_der {
            position: absolute; 
            top: 20px; 
            right: 20px; 
            text-align: right;  
            width: 33.3%;
        }

        .cuerpo {
            margin-top: 25px; 
            font-size:25px;
            width: 100%;
            cellspacing: 0;
            cellpadding: 0;
        }

        .cuerpo_td_izq {
            vertical-align: bottom; 
            padding-right: 5px; 
            font-weight: bold;
        }

        .cuerpo_td_der {
            vertical-align: bottom; 
            background-color: #d3d3d3; 
            padding: 5px; 
            border-radius: 10px;
        }

        .firma {
            margin-top: 25px; 
            margin-left: auto; 
            width: 300px; 
            text-align: center; 
            border:1px solid black;  
            border-radius:10px; 
            padding:5px;
        }

    </style>

    <?php foreach ($datos as $cabecera) {
        foreach ($datosEmp as $emp) { ?>
            <div class="contenedor" style="position: relative;">
                <!-- titulo -->
                <div class="titulo_izq">
                    <div style="font-size:30px; font-weight:bold;"><?php echo $emp['emp_razonsocial'];?></div>
                    <div style="font-size:20px;">RUC: <?php echo $emp['emp_ruc']; ?></div>
                    <div style="font-size:15px; font-weight:bold;"><?php echo $emp['suc_direccion']; ?></div>
                </div>
                <div class="titulo_med">
                    <div style="font-size:30px; font-weight:bold;">RECIBO DE PAGO</div>
                </div>
                <div class="titulo_der">
                    <div style="font-size:30px; font-weight:bold; margin-bottom:5px;">N° <?php echo $cabecera['codigo'];?></div>
                    <span style="font-size:24px; font-weight:bold; background-color:#d3d3d3; padding:5px; border-radius:10px; margin-bottom:5px;">Gs. <?php echo number_format($cabecera['monto'],0,',','.')?></span>
                    <div style="font-size:20px; margin:10px;"><?php echo $emp['ciudad'].', '.$cabecera['fecha'];?></div>
                </div>
                <!-- cuerpo del recibo -->
                <table  class="cuerpo">
                    <tr>
                        <td width="30%" class="cuerpo_td_izq">RECIBI (MOS) DE: </td>
                        <td width="100%" class="cuerpo_td_der">
                            <span style="font-size:25px;"><?php echo $cabecera['cliente']?></span>
                            <div style="border-bottom: 1px solid black;"></div>
                        </td>
                    </tr>
                </table>
                <table class="cuerpo">
                    <tr>
                        <td width="50%" class="cuerpo_td_izq">LA SUMA DE GUARANIES: </td>
                        <td width="100%" class="cuerpo_td_der">
                            <span style="font-size:25px;"><?php echo mb_strtoupper($letras -> format($cabecera['monto']))?></span>
                            <div style="border-bottom: 1px solid black;"></div>
                        </td>
                    </tr>
                </table>
                <table class="cuerpo">
                    <tr>
                        <td width="33%" class="cuerpo_td_izq">EN CONCEPTO DE: </td>
                        <td width="100%" class="cuerpo_td_der">
                            <span style="font-size:25px;"> LA CUOTA <?php echo $cabecera['cuotas']?>, POR LA COMPRA N° <?php echo $cabecera['factura']?></span>
                            <div style="border-bottom: 1px solid black;"></div>
                        </td>
                    </tr>
                </table>
                <!-- firma -->
                <div class="firma">
                    <span style="font-size:15px; margin-bottom:5px; border-bottom:1px solid black; display:inline-block;"><?php echo $u['per_nombres'] . " " . $u['per_apellidos'] ?></span>
                    <div style="font-size:15px; margin:10px;"><?php echo $u['perf_descri']?> </div>
                </div>
                <br>
                <div class="titulo" style="font-size:20px; text-align: center;"><?php echo $emp['suc_descri'].' ------- '.$emp['suc_telefono'].' ------- '.$emp['suc_email']?></div>
            </div>
        <?php }
        } ?>

</body>

</html>

<?php
$html = ob_get_clean();

require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/vendor/autoload.php"; // Incluir el archivo de autoloading de Dompdf

// Crear una instancia de Dompdf
$dompdf = new Dompdf\Dompdf();

// Cargar el contenido HTML en Dompdf
$dompdf->loadHtml($html);

//Dar el formato horizontal y tamaño de hoja A4 al pdf
$dompdf->setPaper('A4', 'landscape', 'default', [
    'margin_top' => 15,
    'margin_right' => 15,
    'margin_bottom' => 15,
    'margin_left' => 15
]);

// Renderizar el contenido HTML a PDF
$dompdf->render();

// Generar el archivo PDF y guardarlo en el servidor o descargarlo
$dompdf->stream(' Recibo Nro '.$cobr_cod, array("Attachment" => false));

// Obtener el contenido del PDF como string
$pdfOutput = $dompdf->output(); // Guardar en variable


?>