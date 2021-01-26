<?php 

namespace App\modelos;

use App\clases\Modelo;
use App\interfaces\Database;

class Menus extends Modelo
{
    public function __construct(Database $coneccion)
    {
        $tabla = 'menus';
        $relaciones = []; 
        $columnas = [
            'unicas' => ['menu' => 'nombre'],
            'obligatorias' => ['nombre'],
            'protegidas' => []
        ];
        parent::__construct($coneccion, $tabla, $relaciones, $columnas);
    }
}