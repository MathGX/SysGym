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
    <title>Cambiar contraseña</title>

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
                <div class="msg"><b>Ingrese su usuario o login de acceso</b></div>

                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">person</i>
                    </span>
                    <div class="form-line">
                        <input type="hidden" id="boton_recuperar" value="0">
                        <input type="hidden" id="per_email" value="0">
                        <input type="text" class="form-control" id="usu_login" placeholder="Usuario" required autofocus>
                    </div>
                </div>

                <div class="row acc1">
                    <div class="col-xs-12">
                        <button class="btn btn-block bg-indigo waves-effect" onclick="verificar_user();" type="button">Verificar</button>
                    </div>
                </div>

                <div class="input-group acc2" style="display:none;">
                    <span class="input-group-addon">
                        <i class="material-icons">check</i>
                    </span>
                    <div class="form-line">
                        <input type="password" class="form-control" id="actpas_clave" placeholder="Clave" required>
                    </div>
                </div>

                <div class="row acc2" style="display:none;">
                    <div class="col-xs-12">
                        <button class="btn btn-block bg-indigo waves-effect" onclick="comprobar_clave();" type="button">Comprobar</button>
                    </div>
                </div>

                <div class="input-group acc3" style="display:none;">
                    <span class="input-group-addon">
                        <i class="material-icons">lock</i>
                    </span>
                    <div class="form-line">
                        <input type="password" class="form-control" id="usu_contrasena1" placeholder="Nueva contraseña" required>
                    </div>
                </div>

                <div class="input-group acc3" style="display:none;">
                    <span class="input-group-addon">
                        <i class="material-icons">lock</i>
                    </span>
                    <div class="form-line">
                        <input type="password" class="form-control" id="usu_contrasena2" placeholder="Repita la contraseña" required>
                    </div>
                </div>

                <div class="alert_fail alert bg-pink alert-dismissible hidden" role="alert" style="text-align:center;">
                    <label class="form-label alerta_error"></label>
                </div>

                <div class="row acc3" style="display:none;">
                    <div class="col-xs-12">
                        <button class="btn btn-block bg-indigo waves-effect" onclick="actualizar_pass();" type="button">Actualizar</button>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <?php include "{$_SERVER['DOCUMENT_ROOT']}/SysGym/others/extension/importJS.php"; ?>

</body>

    <div class="alert_mail alert bg-pink alert-dismissible hidden" role="alert">
        <label class="form-label alerta_correo"></label>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>

</html>