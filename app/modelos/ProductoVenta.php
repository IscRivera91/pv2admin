<?php 

namespace App\modelos;

use App\clases\Modelo;
use App\interfaces\Database;

class ProductoVenta extends Modelo
{
    public function __construct(Database $coneccion)
    {
        $tabla = 'productoventa';
        $relaciones = [
            'ventas' => "{$tabla}.venta_id",
            'productos' => "{$tabla}.producto_id"
        ]; 
        $columnas = [
            'unicas' => [],
            'obligatorias' => ['venta_id','producto_id'],
            'protegidas' => []
        ];
        parent::__construct($coneccion, $tabla, $relaciones, $columnas);
    }
}