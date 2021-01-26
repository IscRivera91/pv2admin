<?php
$rutaBase = __DIR__.'/../../';

require_once "{$rutaBase}/vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable($rutaBase);
$dotenv->load();

define('ES_PRODUCCION', !$_ENV['APP_DEBUG'] );
if (!ES_PRODUCCION) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

session_start();
date_default_timezone_set($_ENV['APP_TIMEZONE']);

require_once "constantes.php";
require_once "configApp.php"; 
require_once "configDatabase.php"; 
