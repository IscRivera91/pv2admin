<?php

use App\ayudas\Redireccion;

$inputs = $controlador->htmlInputFormulario;
$pagina = "&pag={$controlador->obtenerNumeroPagina()}";

?>
<br>
<form autocomplete="off" role="form" method="POST"
      action="<?php echo Redireccion::obtener($controlador->nombreMenu,'modificarBd',SESSION_ID,'',$_GET['registroId']).$pagina ?>">
    <div class="row">
        <?php
            foreach ($inputs as $input) {
                echo $input;
            }
        ?>
    </div>
</form>