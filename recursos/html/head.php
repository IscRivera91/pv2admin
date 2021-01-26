<!doctype html>
<html lang="es">
<head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/gif" href="<?php echo RUTA_PROYECTO; ?>img/favicon.ico"/>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php echo RUTA_PROYECTO; ?>adminlte3/fontawesome-free/css/all.min.css">
        <!-- iCheck for checkboxes and radio inputs -->
        <link rel="stylesheet" href="<?php echo RUTA_PROYECTO; ?>adminlte3/icheck-bootstrap/icheck-bootstrap.min.css">
        <!-- Select2 -->
        <link rel="stylesheet" href="<?php echo RUTA_PROYECTO; ?>adminlte3/select2/css/select2.min.css">
        <link rel="stylesheet" href="<?php echo RUTA_PROYECTO; ?>adminlte3/select2-bootstrap4-theme/select2-bootstrap4.min.css">
        <!-- AdminLTE3 -->
        <link rel="stylesheet" href="<?php echo RUTA_PROYECTO; ?>adminlte3/dist/css/adminlte.min.css">
        <!-- Alertify -->
        <link rel="stylesheet" href="<?php echo RUTA_PROYECTO; ?>alertify/css/alertify.min.css">
        <link rel="stylesheet" href="<?php echo RUTA_PROYECTO; ?>alertify/css/themes/default.min.css">
        <link rel="stylesheet" href="<?php echo RUTA_PROYECTO; ?>alertify/css/themes/semantic.min.css">
        <link rel="stylesheet" href="<?php echo RUTA_PROYECTO; ?>alertify/css/themes/bootstrap.min.css">
        <!-- Argus CSS -->
        <link rel="stylesheet" href="<?php echo RUTA_PROYECTO; ?>css/variables.css">
        <link rel="stylesheet" href="<?php echo RUTA_PROYECTO; ?>css/main.css">
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Roboto+Slab:wght@500&display=swap" rel="stylesheet">
        <?php 
            $rutaArchivoCss = '';
            if (isset($controladorActual) && isset($metodoActual)) {
                $rutaArchivoCss = "css/{$controladorActual}.{$metodoActual}.css";
            }
            
            if(file_exists("{$rutaBase}public/{$rutaArchivoCss}")) {
                echo "<link rel='stylesheet' href='".RUTA_PROYECTO."{$rutaArchivoCss}'>";
            }
        ?>
        <title><?= NOMBRE_PROYECTO ?></title>
    </head>