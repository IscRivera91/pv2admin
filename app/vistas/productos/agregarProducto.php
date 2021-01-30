<?php 

use App\ayudas\Redireccion;

$requiredClases = "required class='form-control  form-control-sm'";

$datosProducto = $controlador->datosProducto;

$num_pagina = 1;
if (isset($_GET['pag'])){
    $num_pagina = (int) $_GET['pag'];
}

?>
<br>
<h2>Agregar Producto</h2>
<br>
<form autocomplete="off" role="form" method="POST"
      action="<?php echo Redireccion::obtener('productos','agregarProductoBd',SESSION_ID)."&pag={$num_pagina}" ?>">
      <input name="id" value="<?= $datosProducto['productos_id']; ?>" type='hidden'>
    <div class="row">
        <div class=col-md-3>
            <div class='input-group mb-3'>
            <input disabled value="<?= $datosProducto['productos_codigo_barras']; ?>" title='Codigo de Barras' <?= $requiredClases; ?> type='text'>
            </div>
        </div>
        <div class='col-md-12'></div>
        <div class=col-md-3>
            <div class='input-group mb-3'>
                <input disabled value="<?= $datosProducto['productos_nombre']; ?>" title='Nombre Producto' <?= $requiredClases; ?> type='text'>
            </div>
        </div>
        <div class='col-md-12'></div>
        <div class=col-md-3>
            <div class='input-group mb-3'>
                <input autofocus title='Cantidad a agregar' name='cantidadProducto' placeholder='Cantidad a agregar' <?= $requiredClases; ?> type='number'>
            </div>
        </div>
        <div class='col-md-12'></div>
        <div class=col-md-3>
            <div class='form-group'>
                <button type='submit' name='<?= md5(SESSION_ID) ?>' class='btn btn-default btn-argus btn-block  btn-flat btn-sm'>Agregar Productos</button>
            </div>
        </div>

    </div>
</form>