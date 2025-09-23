<?php
session_start();
$u = $_SESSION['usuarios'];

date_default_timezone_set('America/Asuncion');
$horaActual = date("H:i:s");
$fechaActual = date('d/m/Y');

ob_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte cajas</title>
</head>
<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/conexion/conexion.php";

//Creamos la instancia de la clase Conexion
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

$seleccionados = $_GET['seleccionados'];
$idsSeleccionados = explode(',', $seleccionados);
$caj_descri = $_GET['caj_descri'];
$caj_cod = $_GET['caj_cod'];
$funcionarios = $_GET['funcionarios'];
$apcier_cod = $_GET['apcier_cod'];

$sqlMonto = "select
    (select apcier_monto_aper from apertura_cierre where apcier_cod = 1 and caj_cod = 1) monto_aper,
    sum((case when cd.forcob_cod = 1 then cd.cobrdet_monto else 0 end)) as cheque,
    sum((case when cd.forcob_cod = 2 then cd.cobrdet_monto else 0 end)) as efectivo,
    sum((case when cd.forcob_cod = 3 then cd.cobrdet_monto else 0 end)) as tarjeta
from cobros_det cd
    join cobros_cab cc on cd.cobr_cod = cc.cobr_cod 
        join apertura_cierre ac on cc.apcier_cod = ac.apcier_cod 
where ac.apcier_cod = $apcier_cod and cc.caj_cod = $caj_cod
and cc.cobr_estado ilike 'ACTIVO';";

$resultado = pg_query($conexion, $sqlMonto);
$datos = pg_fetch_all($resultado);

$sqlEmp = "select
e.emp_razonsocial,
s.suc_descri,
s.suc_telefono,
s.suc_email,
s.suc_direccion
from sucursales s 
join empresa e on e.emp_cod = s.emp_cod 
where s.emp_cod = {$u['emp_cod']} and s.suc_cod = {$u['suc_cod']}";

$resEmp = pg_query($conexion, $sqlEmp);
$datosEmp = pg_fetch_all($resEmp);

?>

<body>

<style>

    .contenedor {
        display: flex;               /* Activa Flexbox */
        flex-direction: column;      /* Alinea los elementos en columna */
        justify-content: center;      /* Centra verticalmente */
        align-items: center;          /* Centra horizontalmente */
        height: 100vh;               /* Altura del contenedor igual a la altura de la ventana */
    }

    .titulo {
        text-align: center;
    }

    .subrayar {
        border-bottom: 1px solid black;
        font-size:15px; 
        font-weight: bold;
    }

    .grilla {
        page-break-inside: avoid;
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        white-space:nowrap;
    }

    .right {
        text-align: right;
        padding-right: 5px;
    }

    .left{
        text-align: left;
        padding-left: 5px;
    }

    .cabecera table {
        width: 100%;
        border-collapse: collapse;
    }

    .detalle table {
        width: 100%;
        border-collapse: collapse;
    }

    .detalle table thead td {
        text-align: center; 
        font-size:12px; 
        font-weight:bold;
        white-space:nowrap;
        border: 1px solid black;
        background-color: #4d4d4d;
        color: white;
    }

    .detalle tbody td{
        font-size: 10px;
        border: 1px solid black;
    }

    .detalle tfoot td {
        border: 1px solid black;
        font-weight: bold;
        font-size: 10px;
        background-color: #d4d4d4;
    }

    .final table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
    }

    .final table td {
        font-size: 10px;
        white-space:nowrap;
    }
    
</style>

    <?php foreach ($datos as $cabecera) { 
        foreach ($datosEmp as $tit) { ?>
            <div class="contenedor">
                <div class="titulo">
                    <div style="font-size:22px; font-weight:bold;"> <?php echo $tit['emp_razonsocial'];?> </div>
                    <div style="font-size:10px; font-weight:bold;"><?php echo $tit['suc_descri'].' - '.$tit['suc_direccion']; ?> </div>
                    <br>
                    <h3 style="margin:0 auto; border:1px solid black; border-radius:20px; background-color:#4d4d4d; color:white">ARQUEO DE CONTROL </h3>
                </div>
            </div>
            <br><br>
        <?php } ?>
        <table class="grilla">
            <div class="cabecera">
                <table>
                    <tr>
                        <td class="subrayar"  style="width:7%">Solicitante: </td>
                        <td> <?php echo $funcionarios;?> </td>
                    </tr>
                </table>
                <br>
                <table>
                    <tr>
                        <td class="subrayar" style="width:10%;" colspan="1"> Fecha de emisión: </td>
                        <td><?php echo $fechaActual;?> </td>
                    </tr>
                </table>
                <br>
            </div>
            <br>
            <div class="detalle">
                <div style="font-family:'Times New Roman',Times,serif; font-style:italic;">
                    Reporte de los cobros de la <b><?php echo $caj_descri;?></b> correspondientes a la apertura n° <b><?php echo $apcier_cod;?> </b>
                </div>
                <table>
                    <thead>
                        <tr>
                            <td style="width:auto;">MOVIMIENTOS SEGÚN FORMA DE COBRO</td>
                            <td style="width:auto;">IMPORTE</td>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <?php $monto_efectivo = 0; $monto_cheque = 0; $monto_tarjeta = 0;
                        if (in_array('efectivo',$idsSeleccionados) || in_array('todos_medios',$idsSeleccionados)) { ?>
                            <tr>
                                <td>EFECTIVO</td>
                                <td class="right"> 
                                    <?php 
                                        echo number_format($cabecera['efectivo'], 0, ',', '.');
                                        $monto_efectivo = $cabecera['efectivo'];
                                    ?>
                                </td>
                            </tr>
                        <?php }
                        if (in_array('cheque',$idsSeleccionados) || in_array('todos_medios',$idsSeleccionados)) { ?>
                            <tr>
                                <td>CHEQUE</td>
                                <td class="right"> 
                                    <?php 
                                        echo number_format($cabecera['cheque'], 0, ',', '.'); 
                                        $monto_cheque = $cabecera['cheque'];
                                    ?>
                                </td>
                            </tr>
                        <?php }
                        if (in_array('tarjeta',$idsSeleccionados) || in_array('todos_medios',$idsSeleccionados)) { ?>
                            <tr>
                                <td>TARJETA</td>
                                <td class="right"> 
                                    <?php 
                                        echo number_format($cabecera['tarjeta'], 0, ',', '.'); 
                                        $monto_tarjeta = $cabecera['tarjeta'];
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                            <td style="text-align:center;"> IMPORTE TOTAL</td>
                            <td class="right"> <?php echo number_format(($monto_efectivo + $monto_cheque + $monto_tarjeta), 0, ',', '.')?> </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
    <?php } ?>


    <br><br>
    <div class="final">
        <table style="margin:0 auto; width:30%;">
            <tr>
                <td style="font-weight:bold; text-align:right;"> Emitido por:</td>
                <td style="padding-left:5px;"> <?php echo $u['per_nombres'] . " " . $u['per_apellidos']. " a las " .$horaActual. " hs del ".$fechaActual  ?> </td>
            </tr>
        </table>
    </div>

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
$dompdf->setPaper('A4', 'portrait');

// Renderizar el contenido HTML a PDF
$dompdf->render();

// Generar el archivo PDF y guardarlo en el servidor o descargarlo
$dompdf->stream('reporte_cajas.pdf', array("Attachment" => false));
?>