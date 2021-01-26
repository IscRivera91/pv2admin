<?php

namespace Test\ayudas;

use App\ayudas\Menu;
use App\modelos\Menus;
use App\modelos\Grupos;
use App\modelos\Metodos;
use Test\LimpiarDatabase;
use App\modelos\MetodosGrupos;
use PHPUnit\Framework\TestCase;

class MenuTest extends TestCase
{
    
    /**
     * @test
     */
    public function menu()
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
            ['id'=> 1,'nombre'=>'usuarios','etiqueta'=>'USUARIOS','icono'=>'i-usuarios','activo'=>1],
            ['id'=> 2,'nombre'=>'metodos','etiqueta'=>'METODOS','icono'=>'i-metodos','activo'=>1]
        ];
        // inserta menus
        foreach ($menus as $menu) {
            $Menus->registrar($menu);
        }

        // defines metodos
        $metodos = [
            ['id'=>1, 'nombre'=>'registrar', 'etiqueta'=>'registrar', 'menu_id'=>1, 'activo_menu'=>1, 'activo_accion'=>0, 'activo'=>1],
            ['id'=>2, 'nombre'=>'lista',     'etiqueta'=>'lista',     'menu_id'=>1, 'activo_menu'=>1, 'activo_accion'=>0, 'activo'=>1],
            ['id'=>3, 'nombre'=>'lista2',    'etiqueta'=>'lista2',    'menu_id'=>1, 'activo_menu'=>0, 'activo_accion'=>0, 'activo'=>1],
            ['id'=>4, 'nombre'=>'lista3',    'etiqueta'=>'lista3',    'menu_id'=>1, 'activo_menu'=>1, 'activo_accion'=>0, 'activo'=>0],
            
            ['id'=>5, 'nombre'=>'registrar', 'etiqueta'=>'registrar', 'menu_id'=>2, 'activo_menu'=>1, 'activo_accion'=>0, 'activo'=>1],
            ['id'=>6, 'nombre'=>'lista',     'etiqueta'=>'lista',     'menu_id'=>2, 'activo_menu'=>1, 'activo_accion'=>0, 'activo'=>1],
            ['id'=>7, 'nombre'=>'lista2',    'etiqueta'=>'lista2',    'menu_id'=>2, 'activo_menu'=>0, 'activo_accion'=>0, 'activo'=>1],
            ['id'=>8, 'nombre'=>'lista3',    'etiqueta'=>'lista3',    'menu_id'=>2, 'activo_menu'=>1, 'activo_accion'=>0, 'activo'=>0]
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
        for ($i = 1 ; $i < 9 ; $i++) {
            $metodogrupo = ['id' => $i,'metodo_id'=> $i,'grupo_id' => GRUPO_ID, 'activo' => 1];

            $MetodosGrupos->registrar($metodogrupo);
        }

        $menu = Menu::crear($coneccion, GRUPO_ID);
        $this->assertIsArray($menu);
        $this->assertCount(2,$menu);
        $this->assertSame('METODOS',$menu['METODOS'][2]);
        $this->assertSame('USUARIOS',$menu['USUARIOS'][2]);

    }
}