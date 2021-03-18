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
            'unicas' => ['producto' => 'nombre'],
            'obligatorias' => ['codigo_barras','nombre','categoria_id','cantidad','precio_compra','precio_venta'],
            'protegidas' => []
        ];
        parent::__construct($coneccion, $tabla, $relaciones, $columnas);
    } 

    public function agregarProductos(int $productoId, int $cantidad)
    {
        $cantidadActual = $this->obtenerDatosConRegistroId($productoId)['productos_cantidad'];
        $nuevaCantidadProducto = $cantidadActual + $cantidad;
        $this->modificarPorId($productoId,['cantidad' => $nuevaCantidadProducto]);
    }

    public function descontarProductos(int $productoId, int $cantidad)
    {
        $cantidadActual = $this->obtenerDatosConRegistroId($productoId)['productos_cantidad'];
        $nuevaCantidadProducto = $cantidadActual - $cantidad;
        $this->modificarPorId($productoId,['cantidad' => $nuevaCantidadProducto]);
    }
}