<?php use App\ayudas\Redireccion; ?>
<aside class="main-sidebar sidebar-light-primary elevation-4">

  <a  href="<?php echo Redireccion::obtener('inicio','index',SESSION_ID);?>" class="brand-link">
    <img src="<?php echo RUTA_PROYECTO; ?>img/logo.png"
        class="brand-image img-circle elevation-3"
        style="opacity: .8">
    <span class="brand-text font-weight-light" ><b><?= NOMBRE_PROYECTO ?></b></span>
  </a>

  
  <div class="sidebar">

    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

      <?php foreach ($menu_navegacion as $item_menu => $menu) { ?>
        <?php 
          $menuControlador = $menu_navegacion[$item_menu][0];
          $menuIcono = $menu_navegacion[$item_menu][1];
          $menuEtiqueta = $menu_navegacion[$item_menu][2];
        
          
          $imprime = '<li class="nav-item has-treeview">';
          if ( $controladorActual == $menuControlador){
            $imprime =  '<li class="nav-item has-treeview menu-open">';
          }
          echo $imprime;
        ?>
          <a href="#" class="nav-link">
            <i class="nav-icon <?php echo $menuIcono; ?>" ></i>
            <p>
              <?php echo $menuEtiqueta; ?>
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>

          <ul class="nav nav-treeview">

            <?php foreach ($menu as $metodo){ ?>
              <?php if ( is_array($metodo) ){ ?>
                <?php
                $metodoMenu = $metodo['metodo'];
                $metodoEtiqueta = $metodo['label'];
                  $letra = 'r'; // esta es la letar que finaliza el fa ya sea far o fas
                  if ($metodoMenu == $metodoActual && $controladorActual == $menuControlador){
                    $letra = 's';
                  }
                
                ?>
                <li class="nav-item">
                  <a href="<?php echo Redireccion::obtener($menuControlador,$metodoMenu,SESSION_ID);?>" class="nav-link">
                    <i class="fa<?php echo $letra ?> fa-circle nav-icon" ></i>
                    <p><?php echo $metodoEtiqueta; ?></p>
                  </a>
                </li>
              <?php } // end if is array?>          
            <?php }// end foreach ($menu as $metodo) ?>

          </ul>

        </li>
        <?php } // end foreach ($menu_navegacion as $item_menu => $menu) ?>

      </ul>
      
    </nav>
    
  </div>
  
</aside>