<?php

namespace Test\clases;

use App\clases\Ventas;
use PHPUnit\Framework\TestCase;
use App\errores\Base AS ErrorBase;


class VentasTest extends TestCase
{
    /**
     * @test
     */
    public function creaConeccion()
    {
        $this->assertSame(1,1);
        $claseDatabase = 'App\\clases\\'.DB_TIPO.'\\Database';
        $coneccion = new $claseDatabase();
        return $coneccion;
    }

    /**
     * @test
     * @depends creaConeccion
     */
    public function creaObjetoVentas($coneccion)
    {
        $this->assertSame(1,1);
        $Ventas = new Ventas($coneccion);
        return $Ventas;
    }
}