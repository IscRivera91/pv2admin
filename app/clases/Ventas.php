<?php

namespace App\clases;

use App\modelos\Productos as ProductosModelo;
use App\interfaces\Database;
use App\modelos\Ventas as VentasModelo;
use App\errores\Base as ErrorBase;
use App\modelos\ProductoVenta;

class Ventas {

    private $ProductosModelo;
    private $VentasModelo;
    private $ProductoVentaModelo;
    private $json;
    private $coneccion;

    public function __construct(Database $coneccion)
    {
        $this->coneccion = $coneccion;
        $this->ProductosModelo = new ProductosModelo($this->coneccion);
        $this->VentasModelo = new VentasModelo($this->coneccion);
        $this->ProductoVentaModelo = new ProductoVenta($this->coneccion);
        $this->json = new JsonResponse;
    }

    public function registrar(array $datosPost, array $productosVendidos): void
    {
        $datosVenta['cajero_id'] = USUARIO_ID;
        $datosVenta['fecha'] = $datosPost['fecha'];
        $datosVenta['hora'] = $datosPost['hora'];
        $datosVenta['numero_productos'] = $datosPost['numero_productos'];
        $datosVenta['cobro'] = $datosPost['cobro'];
        $datosVenta['pago'] = $datosPost['pago'];
        $datosVenta['cambio'] = $datosPost['cambio'];
        $datosVenta['ganancia'] = $datosPost['ganancia'];

        $this->coneccion->beginTransaction();

        try{
            $ventaId = $this->VentasModelo->registrar($datosVenta)['registroId'];
        } catch(ErrorBase $e) {
            $this->coneccion->rollBack();
            $this->json->errorResponse($e->getMessage(),JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        $this->registrarProductosEnVenta($ventaId,$productosVendidos);
        
        $data['ventaId'] = $ventaId;
        $this->coneccion->commit();
        $this->json->successResponse($data);
    }

    private function registrarProductosEnVenta(int $ventaId, array $productosVendidos): void
    {
        foreach ($productosVendidos as $producto) {
            $productoId = $producto['id'];
            $cantidad = $producto['cantidad'];
            
            try{
                $this->ProductoVentaModelo->registrar(
                    [
                        'venta_id'=>$ventaId,
                        'producto_id'=>$productoId,
                        'cantidad' => $cantidad
                    ]
                );
            } catch(ErrorBase $e) {
                $this->coneccion->rollBack();
                $mensaje = "Error al registrar el producto a la venta";
                $this->json->errorResponse($mensaje,JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }

            try{
                $this->ProductosModelo->descontarProductos($productoId,$cantidad);
            } catch(ErrorBase $e) {
                $this->coneccion->rollBack();
                $mensaje = "Error al descontar la cantidad de la tabla productos";
                $this->json->errorResponse($mensaje,JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }

        }
    }

}