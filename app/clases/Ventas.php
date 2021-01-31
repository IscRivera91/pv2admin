<?php

namespace App\clases;

use App\modelos\Productos;
use App\interfaces\Database;

class Ventas {
    private $ProductosModelo;

    public function __construct(Database $coneccion)
    {
        $this->ProductosModelo = new Productos($coneccion);
    }

    public function obtenerListaProductos(): array
    {
        $columnas = [];

        return $this->ProductosModelo->buscarTodo()['registros'];
    }
}