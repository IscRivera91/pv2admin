<?php use App\ayudas\Redireccion; ?>
<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">

            <li class="nav-item dropdown">

                <a class="nav-link" role="button" data-toggle="dropdown" href="#">
                <i class="fas fa-ellipsis-v"></i>
                </a>

                <div class="dropdown-menu dropdown-menu dropdown-menu">

                    <a href="<?php echo Redireccion::obtener('password','cambiarPassword',SESSION_ID); ?>"class="dropdown-item text-center">
                        Cambiar Contraseña
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="<?php echo Redireccion::obtener('session','logout',SESSION_ID); ?>"class="dropdown-item text-center">
                        Salir
                    </a>

                </div>
                
            </li>

        </ul>
    </nav> 
    <!-- /.navbar -->