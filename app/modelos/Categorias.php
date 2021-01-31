<?php 

namespace App\modelos;

use App\clases\Modelo;
use App\interfaces\Database;

class Categorias extends Modelo
{
    public function __construct(Database $coneccion)
    {
        $tabla = 'categorias';
        $relaciones = []; 
        $columnas = [
            'unicas' => ['categoria'=>'nombre'],
            'obligatorias' => ['nombre'],
            'protegidas' => []
        ];
        parent::__construct($coneccion, $tabla, $relaciones, $columnas);
    }
}