<?php

use App\ayudas\Redireccion;

$inputs = $controlador->htmlInputFormulario;

?>
<br>
<form autocomplete="off" role="form" method="POST"
      action="<?php echo Redireccion::obtener($controlador->nombreMenu,'registrarBd',SESSION_ID) ?>">
    <div class="row">
        <?php
            foreach ($inputs as $input) {
                echo $input;
            }
        ?>
    </div>
</form>