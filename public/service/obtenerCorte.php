<?php 

$rutaBase = __DIR__.'/../../';
require_once "{$rutaBase}app/config/requires.php"; 

use App\clases\Autentificacion;
use App\clases\JsonResponse;
use App\errores\Base AS ErrorBase;
use App\modelos\Productos;

$json = new JsonResponse;

if (!isset($_POST['sessionId'])){
    $json->errorResponse('Es necesario el token de sessionId',JsonResponse::HTTP_BAD_REQUEST);
} 

$sessionId = $_POST['sessionId'];

try {
    $claseDatabase = 'App\\clases\\'.DB_TIPO.'\\Database';
    $coneccion = new $claseDatabase();
}catch (ErrorBase $e) {
    $json->errorResponse('Algo salio mal, intente de nuevo mas tarde',JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
}

$autentificacion = new Autentificacion($coneccion);

try{
    $datos = $autentificacion->validaSessionId($sessionId);
}catch(ErrorBase $e){
    $mensaje = "sessionId no valido";
    $json->errorResponse($mensaje,JsonResponse::HTTP_UNAUTHORIZED);
}