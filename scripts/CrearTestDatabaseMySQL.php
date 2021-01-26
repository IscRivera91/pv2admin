<?php
require_once __DIR__.'/../app/config/requires.php';
require_once __DIR__.'/../recursos/BD/BaseDatos.php';

use App\clases\MySQL\Database;

$coneccion = new Database(DB_USER,DB_PASSWORD,DB_TEST_NAME,DB_HOST);

BaseDatos::crear($coneccion);