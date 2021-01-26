<?php 
    $metodosAgrupadosPorMenu = $controlador->metodosAgrupadosPorMenu;
    $nombreGrupo = $controlador->nombreGrupo;
    $grupoId = $controlador->grupoId;
?>
<br>
<div class="card">
    <input type="hidden" name="session_id" id="session_id" value="<?php echo $_GET['session_id'] ?>">
    <div class="card-header text-center">
        Grupo : <?= $nombreGrupo ?>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            
            <div class="accordion" id="accordionExample">
                <?php foreach ($metodosAgrupadosPorMenu as $menu => $metodos ): ?>
                    <div class="card">
                        <div class="card-header" id="heading<?php echo $menu ?>">
                            <h2 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#ac<?php echo $menu ?>" aria-expanded="false" aria-controls="ac<?php echo $menu ?>">
                                    <b><?php echo strtoupper ($menu) ?></b>  
                                </button>
                            </h2>
                        </div>
                        <div id="ac<?php echo $menu ?>" class="collapse" aria-labelledby="heading<?php echo $menu ?>" data-parent="#accordionExample">
                            <div class="card-body">
                                        <?php
                                        foreach ($metodos as $metodo){
                                            $metodoId = $metodo['id'];
                                            $classActivo = 'sin-permiso-btn';
                                            if($metodo['activo'] == 1){
                                                $classActivo = 'con-permiso-btn';
                                            }
                                            echo "<a onclick='permisos($metodoId,$grupoId);' id='$metodoId' class='btn btn-default $classActivo btn-flat'>".$metodo['metodo']."</a>";

                                        }
                                        ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                
            </div>

        </div>
    </div>
</div>