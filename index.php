<?php
//inicilizamos variable de sesión y luego la destruimos
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Iniciar Sesión</title>

    <?php include "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/extension/importCSS.php"; ?>
    
    <!-- fondo Css -->
    <link href="imagen.css" rel="stylesheet">
</head>

<body class="login-page bg-indigo wallpaper">
    <div class="login-box">
        <div class="logo">
            <a href="javascript:void(0);" class="bg-indigo"><b>ORBUS GYM</b></a>
            <small class="bg-indigo">"Cumpliendo metas y entrenando atletas"</small>
        </div>
        <div class="card">
            <div class="body">
                <div class="msg acc1"><b>Ingrese sus Credenciales de acceso</b></div>
                <div class="msg acc2" style="display:none;"><b>Ingrese clave de seguridad</b></div>

                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">person</i>
                    </span>
                    <div class="form-line">
                        <input type="hidden" id="boton_ingreso" value="0">
                        <input type="hidden" id="per_email" value="0">
                        <input type="text" class="form-control" id="usu_login" placeholder="Usuario" required autofocus>
                    </div>
                </div>

                <div class="input-group acc1">
                    <span class="input-group-addon">
                        <i class="material-icons">lock</i>
                    </span>
                    <div class="form-line">
                        <input type="password" class="form-control" id="usu_contrasena" placeholder="Contraseña" required>
                    </div>
                </div>
                
                <div class="row acc1">
                    <div class="col-xs-12">
                        <button class="btn btn-block bg-indigo waves-effect" onclick="verificar();" type="button">Verificar</button>
                    </div>
                </div>

                <div class="input-group acc2" style="display:none;">
                    <span class="input-group-addon">
                        <i class="material-icons">check</i>
                    </span>
                    <div class="form-line">
                        <input type="password" class="form-control" id="accon_clave" placeholder="Clave" required>
                    </div>
                </div>

                <div class="alert_fail alert bg-pink alert-dismissible hidden" role="alert" style="text-align:center;">
                    <label class="form-label alerta_error"></label>
                </div>

                <div class="row acc2" style="display:none;">
                    <div class="col-xs-12">
                        <button class="btn btn-block bg-indigo waves-effect" onclick="ingresar();" type="button">Ingresar</button>
                    </div>
                </div>

                <div class="row acc2" style="display:none;">
                    <div class="col-xs-12">
                        <button class="btn btn-block bg-blue waves-effect" onclick="verificar();" type="button">Reenviar</button>
                    </div>
                </div>

                <div style="text-align:center;">
                    <a href="/SysGym/recoveryPass.php">Olvidó su contraseña?</a>
                </div>

            </div>

        </div>
    </div>
    
    <div class="alert_mail alert bg-pink alert-dismissible hidden" role="alert">
        <label class="form-label alerta_correo"></label>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>

    <?php include "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/extension/importJS.php"; ?>

</body>

</html>