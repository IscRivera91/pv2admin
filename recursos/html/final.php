<?php $teme = "bootstrap4"; ?>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="<?php echo RUTA_PROYECTO; ?>adminlte3/jquery/jquery.min.js"></script>
    <!-- Select2 -->
    <script src="<?php echo RUTA_PROYECTO; ?>adminlte3/select2/js/select2.full.min.js"></script>
    <!-- Bootstrap Switch -->
    <script src="<?php echo RUTA_PROYECTO; ?>adminlte3/bootstrap-switch/js/bootstrap-switch.min.js"></script>
    <!-- AdminLTE3 App -->
    <script src="<?php echo RUTA_PROYECTO; ?>adminlte3/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo RUTA_PROYECTO; ?>adminlte3/dist/js/adminlte.min.js"></script>
    <!-- Alertify -->
    <script src="<?php echo RUTA_PROYECTO; ?>alertify/alertify.min.js"></script>
    <!-- Argus JS -->
    <script src="<?php echo RUTA_PROYECTO; ?>js/main.js"></script>
    <script>
        $(function () {

            //Initialize Select2 Elements
            $('.select2').select2();

            //Initialize Select2 Elements
            $('.select2bs4').select2({
            theme: '<?= $teme ?>'
            });
        });
    </script>
    <?php 
        $rutaArchivoJs = '';
        if (isset($controladorActual) && isset($metodoActual)) {
            $rutaArchivoJs = "js/{$controladorActual}.{$metodoActual}.js";
        }
        if(file_exists($rutaArchivoJs)) {
            echo "<script src='$rutaArchivoJs'></script>";
        }
    ?>
    </body>
</html>