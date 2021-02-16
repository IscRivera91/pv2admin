<?php

$rutaBase = __DIR__.'/../../';
require_once "{$rutaBase}app/config/requires.php"; 

use App\clases\Autentificacion;
use App\errores\Base AS ErrorBase;


try {
    $claseDatabase = 'App\\clases\\'.DB_TIPO.'\\Database';
    $coneccion = new $claseDatabase();
}catch (ErrorBase $e) {
    print_r('Error al conectarce a la base de datos, favor de contactar al equipo de desarrollo');
    exit;
}

$autentificacion = new Autentificacion($coneccion);

try {
    $resultado = $autentificacion->login();
}catch (ErrorBase $e) {
    $respuesta = json_encode(['sessionId' => null ,'msj' => $e->getMessage()]);
    print_r($respuesta);
    exit;
}

