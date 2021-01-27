<?php 

namespace App\modelos;

use App\clases\Modelo;
use App\interfaces\Database;

class Productos extends Modelo
{
    public function __construct(Database $coneccion)
    {
        $tabla = 'productos';
        $relaciones = [
            'categorias' => "{$tabla}.categoria_id"
        ]; 
        $columnas = [
            'unicas' => ['nombre'],
            'obligatorias' => ['codigo_barras','nombre','categoria_id','cantidad','precio_compra','precio_venta'],
            'protegidas' => []
        ];
        parent::__construct($coneccion, $tabla, $relaciones, $columnas);
    }
}