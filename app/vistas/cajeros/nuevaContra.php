<?php

use App\ayudas\Redireccion;

$inputs = $controlador->htmlInputFormulario;
$registro = $controlador->registro;

?>
<br>
<h3><?= "cajero: {$registro['usuarios_nombre_completo']}"; ?></h3>
<br>

<?php $rutaPOST = Redireccion::obtener($controlador->nombreMenu,'nuevaContraBd',SESSION_ID); ?>
<form autocomplete="off" role="form" method="POST" action="<?= $rutaPOST  ?>">
    <input type="hidden" name="usuarioId" value="<?= $registro['usuarios_id']; ?>">
    <div class="row">
        <?php
            foreach ($inputs as $input) {
                echo $input;
            }
        ?>
    </div>
</form>