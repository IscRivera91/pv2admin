<?php

namespace Test\ayudas;

use App\modelos\Menus;
use App\modelos\Grupos;
use App\ayudas\Acciones;
use App\modelos\Metodos;
use App\modelos\MetodosGrupos;
use Test\LimpiarDatabase;
use PHPUnit\Framework\TestCase;

class AccionesTest extends TestCase
{
    
    /**
     * @test
     */
    public function acciones()
    {
        $claseDatabase = 'App\\clases\\'.DB_TIPO.'\\Database';
        $coneccion = new $claseDatabase();

        $Grupos = new Grupos($coneccion);
        $Menus = new Menus($coneccion);
        $Metodos = new Metodos($coneccion);
        $MetodosGrupos = new MetodosGrupos($coneccion);

        LimpiarDatabase::start($coneccion);

        // define menus
        $menus = [
            ['id'=> 1,'nombre'=>'usuarios','etiqueta'=>'USUARIOS','icono'=>'i-usuarios','activo'=>1]
        ];
        // inserta menus
        foreach ($menus as $menu) {
            $Menus->registrar($menu);
        }

        // defines metodos
        $metodos = [
            ['id'=>1, 'nombre'=>'accion1', 'accion'=> 'accion1', 'icono' => 'icono-accion1', 'menu_id'=>1, 'activo_menu'=>0, 'activo_accion'=>1],
            ['id'=>2, 'nombre'=>'accion2', 'accion'=> 'accion2', 'icono' => 'icono-accion2', 'menu_id'=>1, 'activo_menu'=>0, 'activo_accion'=>1],
            ['id'=>3, 'nombre'=>'accion3', 'accion'=> 'accion3', 'icono' => 'icono-accion3', 'menu_id'=>1, 'activo_menu'=>0, 'activo_accion'=>1],
            ['id'=>4, 'nombre'=>'accion4', 'accion'=> 'accion4', 'icono' => 'icono-accion4', 'menu_id'=>1, 'activo_menu'=>0, 'activo_accion'=>1],

        ];
        // inserta metodos
        foreach ($metodos as $metodo) {
            $Metodos->registrar($metodo);
        }

        //define grupos 
        $grupos = [
            ['id'=>GRUPO_ID, 'nombre' => 'adminstrador', 'activo' => 1]
        ];

        // inserta metodos
        foreach ($grupos as $grupo) {
            $Grupos->registrar($grupo);
        }

        // define e inserta MetodosGrupos 
        for ($i = 1 ; $i < 5 ; $i++) {
            $metodogrupo = ['id' => $i,'metodo_id'=> $i,'grupo_id' => GRUPO_ID, 'activo' => 1];

            $MetodosGrupos->registrar($metodogrupo);
        }

        $acciones = Acciones::crear($coneccion, GRUPO_ID, 'usuarios');
        $this->assertIsArray($acciones);
        $this->assertCount(4,$acciones);

        foreach ($acciones as $accion) {
            $this->assertCount(4,$accion);
            $this->assertArrayHasKey('metodos_nombre',$accion);
            $this->assertArrayHasKey('metodos_accion',$accion);
            $this->assertArrayHasKey('metodos_icono',$accion);
            $this->assertArrayHasKey('menus_nombre',$accion);
        }

    }
}