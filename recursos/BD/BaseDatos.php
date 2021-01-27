<?php

class BaseDatos 
{
    public static function crear($coneccion)
    {
        $query = "
    
            SET NAMES utf8mb4;
            SET FOREIGN_KEY_CHECKS = 0;

            DROP TABLE IF EXISTS `categorias`;
            CREATE TABLE `categorias`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `nombre` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            `activo` tinyint(11) NULL DEFAULT 0,
            `usuario_registro_id` int(11) NULL DEFAULT NULL,
            `usuario_actualizacion_id` int(11) NULL DEFAULT NULL,
            `fecha_registro` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP,
            `fecha_actualizacion` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0),
            PRIMARY KEY (`id`) USING BTREE
            ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;
    
            DROP TABLE IF EXISTS `grupos`;
            CREATE TABLE `grupos`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `nombre` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            `activo` tinyint(11) NULL DEFAULT 0,
            `usuario_registro_id` int(11) NULL DEFAULT NULL,
            `usuario_actualizacion_id` int(11) NULL DEFAULT NULL,
            `fecha_registro` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP,
            `fecha_actualizacion` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0),
            PRIMARY KEY (`id`) USING BTREE
            ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;
    
            DROP TABLE IF EXISTS `menus`;
            CREATE TABLE `menus`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `nombre` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `etiqueta` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `icono` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `activo` tinyint(11) NULL DEFAULT NULL,
            `usuario_registro_id` int(11) NULL DEFAULT NULL,
            `usuario_actualizacion_id` int(11) NULL DEFAULT NULL,
            `fecha_registro` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP,
            `fecha_actualizacion` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0),
            PRIMARY KEY (`id`) USING BTREE
            ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;
    
            DROP TABLE IF EXISTS `metodosgrupos`;
            CREATE TABLE `metodosgrupos`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `metodo_id` int(11) NULL DEFAULT NULL,
            `grupo_id` int(11) NULL DEFAULT NULL,
            `activo` tinyint(11) NULL DEFAULT NULL,
            `usuario_registro_id` int(11) NULL DEFAULT NULL,
            `usuario_actualizacion_id` int(11) NULL DEFAULT NULL,
            `fecha_registro` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP,
            `fecha_actualizacion` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0),
            PRIMARY KEY (`id`) USING BTREE,
            INDEX `metodo_id`(`metodo_id`) USING BTREE,
            INDEX `grupo_id`(`grupo_id`) USING BTREE,
            UNIQUE INDEX `permiso`(`grupo_id`, `metodo_id`) USING BTREE,
            CONSTRAINT `metodo_grupo_ibfk_1` FOREIGN KEY (`metodo_id`) REFERENCES `metodos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT `metodo_grupo_ibfk_2` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
            ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;
    
            DROP TABLE IF EXISTS `metodos`;
            CREATE TABLE `metodos`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `nombre` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `etiqueta` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `accion` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `icono` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `menu_id` int(11) NULL DEFAULT NULL,
            `activo_menu` tinyint(11) NULL DEFAULT NULL,
            `activo_accion` tinyint(11) NULL DEFAULT NULL,
            `activo` tinyint(11) NULL DEFAULT NULL,
            `usuario_registro_id` int(11) NULL DEFAULT NULL,
            `usuario_actualizacion_id` int(11) NULL DEFAULT NULL,
            `fecha_registro` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP,
            `fecha_actualizacion` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0),
            PRIMARY KEY (`id`) USING BTREE,
            INDEX `menu_id`(`menu_id`) USING BTREE,
            UNIQUE INDEX `nombre`(`nombre`, `menu_id`) USING BTREE,
            CONSTRAINT `metodos_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
            ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;
    
            DROP TABLE IF EXISTS `sessiones`;
            CREATE TABLE `sessiones`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `session_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `usuario_id` int(11) NULL DEFAULT NULL,
            `grupo_id` int(11) NULL DEFAULT NULL,
            `activo` tinyint(11) NULL DEFAULT NULL,
            `usuario_registro_id` int(11) NULL DEFAULT NULL,
            `usuario_actualizacion_id` int(11) NULL DEFAULT NULL,
            `fecha_registro` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`) USING BTREE,
            INDEX `usuario_id`(`usuario_id`) USING BTREE,
            INDEX `grupo_id`(`grupo_id`) USING BTREE,
            CONSTRAINT `sessiones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT `sessiones_ibfk_2` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
            ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;
    
            DROP TABLE IF EXISTS `usuarios`;
            CREATE TABLE `usuarios`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `usuario` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `nombre_completo` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `correo_electronico` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `sexo` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `grupo_id` int(11) NULL DEFAULT NULL,
            `activo` tinyint(11) NULL DEFAULT NULL,
            `usuario_registro_id` int(11) NULL DEFAULT NULL,
            `usuario_actualizacion_id` int(11) NULL DEFAULT NULL,
            `fecha_registro` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP,
            `fecha_actualizacion` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0),
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `user`(`usuario`) USING BTREE,
            UNIQUE INDEX `email`(`correo_electronico`) USING BTREE,
            INDEX `grupo_id`(`grupo_id`) USING BTREE,
            INDEX `usuario_alta_id`(`usuario_registro_id`) USING BTREE,
            INDEX `usuario_update_id`(`usuario_actualizacion_id`) USING BTREE,
            CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
            ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;
    
            SET FOREIGN_KEY_CHECKS = 1;
    
        ";
    
        $resultado = $coneccion->ejecutaQuery($query);
        print_r('crear:');
        print_r($resultado);
    }

    public static function insertarRegistrosBase($coneccion,$user,$password,$nombre,$email,$sexo = 'm')
    {
        $password = md5($password);
        $query = "
            SET FOREIGN_KEY_CHECKS = 0;

            INSERT INTO grupos (nombre , activo , usuario_registro_id ,usuario_actualizacion_id)
            VALUES
            ('programador',TRUE,-1,-1),
            ('administrador',TRUE,-1,-1),
            ('cajero',TRUE,-1,-1);

            INSERT INTO usuarios 
            (usuario, password, nombre_completo, correo_electronico, sexo, grupo_id, activo, usuario_registro_id, usuario_actualizacion_id) 
            VALUES 
            ('$user', '$password', '$nombre', '$email', '$sexo', '1', '1', '-1', '-1');

            INSERT INTO `menus` 
            (id, nombre, etiqueta, icono, activo, usuario_registro_id, usuario_actualizacion_id) 
            VALUES 
            (1, 'menus', 'MENUS', 'fas fa-th-list', '1', '-1', '-1'),
            (2, 'metodos', 'METODOS', 'fas fa-list-ul', '1', '-1', '-1'),
            (3, 'grupos', 'GRUPOS', 'fas fa-users-cog', '1', '-1', '-1'),
            (4, 'usuarios', 'USUARIOS', 'fas fa-users', '1', '-1', '-1'),
            (5, 'categorias', 'CATEGORIAS', 'fas fa-chart-pie', '1', '-1', '-1'),
            (6, 'productos', 'PRODUCTOS', 'fas fa-boxes', '1', '-1', '-1'),
            (7, 'cajeros', 'CAJEROS', 'fas fa-cash-register', '1', '-1', '-1');
        ";

        $numeroMenus = 7;

        $numeroMetodosEspeciales = 5;
        $query .= "
            INSERT INTO `metodos` 
            (id,nombre,etiqueta,accion,icono,menu_id,activo_menu,activo_accion,activo,usuario_registro_id,usuario_actualizacion_id)
            VALUES

            ('1','nuevaContra','','Cambiar contraseña','fas fa-key',4,FALSE,TRUE,TRUE,-1,-1),
            ('2','nuevaContraBd','','','',4,FALSE,FALSE,TRUE,-1,-1),

            ('3','permisos','','Asigna Permisos','fas fa-plus-square',3,FALSE,TRUE,TRUE,-1,-1),
            ('4','bajaPermiso','','','',3,FALSE,FALSE,TRUE,-1,-1),
            ('5','altaPermiso','','','',3,FALSE,FALSE,TRUE,-1,-1),

        ";

        $metodoId = (int)$numeroMetodosEspeciales;
        for ($i = 1 ; $i <= $numeroMenus ; $i++) {
            $query .= "
            ('".($metodoId+1)."','registrar','Registrar','','',$i,TRUE,FALSE,TRUE,-1,-1),
            ('".($metodoId+2)."','lista','Lista','','',$i,TRUE,FALSE,TRUE,-1,-1),
            ('".($metodoId+3)."','registrarBd','','','',$i,FALSE,FALSE,TRUE,-1,-1),
            ('".($metodoId+4)."','activarBd','','Activar','fas fa-play',$i,FALSE,TRUE,TRUE,-1,-1),
            ('".($metodoId+5)."','desactivarBd','','Desactivar','fas fa-pause',$i,FALSE,TRUE,TRUE,-1,-1),
            ('".($metodoId+6)."','modificar','','Modificar','fas fa-pencil-alt',$i,FALSE,TRUE,TRUE,-1,-1),
            ('".($metodoId+7)."','eliminarBd','','Eliminar','fas fa-trash',$i,FALSE,TRUE,TRUE,-1,-1),
            ('".($metodoId+8)."','modificarBd','','','',$i,FALSE,FALSE,TRUE,-1,-1),
            ";
            $metodoId += 8;
        }
        
        $query .= "

            ('".($metodoId+1)."','','','','',1,FALSE,FALSE,TRUE,-1,-1);

            INSERT INTO metodosgrupos (metodo_id,grupo_id,activo)
            VALUES 
        
        ";

        $numeroMetodos = ($numeroMenus*8) + $numeroMetodosEspeciales;
        $grupoId = 1;
        for ($i = 1 ; $i < $numeroMetodos ; $i++) {
            $query .= "($i,$grupoId,TRUE),";
        }
        $query .= "($numeroMetodos,$grupoId,TRUE);";

        $query .= "
            SET FOREIGN_KEY_CHECKS = 1;
        ";

        $resultado = $coneccion->ejecutaQuery($query);
        print_r('insertar:');
        print_r($resultado);
    }
}

