<?php

use App\controladores\menus;
use PHPUnit\Framework\TestCase;
use Test\LimpiarDatabase;

class CMenusTest extends TestCase
{
    /**
     * @test
     */
    public function crearConeccion()
    {
        $this->assertSame(1,1);
        $claseDatabase = 'App\\clases\\'.DB_TIPO.'\\Database';
        $coneccion = new $claseDatabase();

        LimpiarDatabase::start($coneccion);

        return $coneccion;
    }

    /**
     * @test
     * @depends crearConeccion
     */
    public function crearControlador($coneccion)
    {
        $this->assertSame(1,1);
        $menus = new menus($coneccion);
        return $menus;
    }
}