<?php 

namespace App\modelos;

use App\clases\Modelo;
use App\interfaces\Database;

class Ventas extends Modelo
{
    public function __construct(Database $coneccion)
    {
        $tabla = 'ventas';
        $relaciones = [
            'usuarios' => "{$tabla}.cajero_id"
        ]; 
        $columnas = [
            'unicas' => [],
            
            'obligatorias' => [
                'fecha',
                'hora',
                'cajero_id',
                'numero_productos',
                'cobro',
                'pago',
                'cambio',
                'ganancia'
            ],

            'protegidas' => []
        ];
        parent::__construct($coneccion, $tabla, $relaciones, $columnas);
    } 

}