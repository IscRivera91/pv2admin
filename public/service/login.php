<?php

$rutaBase = __DIR__.'/../../';
require_once "{$rutaBase}app/config/requires.php"; 

use App\clases\Autentificacion;
use App\clases\JsonResponse;
use App\errores\Base AS ErrorBase;

$json = new JsonResponse();

try {
    $claseDatabase = 'App\\clases\\'.DB_TIPO.'\\Database';
    $coneccion = new $claseDatabase();
}catch (ErrorBase $e) {
    $json->errorResponse('Algo salio mal, intente de nuevo mas tarde',JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
}

$autentificacion = new Autentificacion($coneccion);

try {
    $resultado = $autentificacion->login();
}catch (ErrorBase $e) {
    $json->errorResponse($e->getMessage(),JsonResponse::HTTP_UNAUTHORIZED);
}

$json->successResponse($resultado);
