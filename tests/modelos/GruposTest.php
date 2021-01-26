<?php

namespace Test\modelos;

use App\modelos\Menus;
use App\modelos\Grupos;
use App\modelos\Metodos;
use App\modelos\Usuarios;
use App\modelos\MetodosGrupos;
use App\errores\Base AS ErrorBase;
use PHPUnit\Framework\TestCase;

class GruposTest extends TestCase
{
    public int $registrosExtras = 2;
    /**
     * @test
     */
    public function crearConeccion()
    {
        $this->assertSame(1,1);
        $claseDatabase = 'App\\clases\\'.DB_TIPO.'\\Database';
        $coneccion = new $claseDatabase();
        return $coneccion;
    
    }

    /**
     * @test
     * @depends crearConeccion
     */
    public function crearModeloMetodosGrupos($coneccion)
    {
        $this->assertSame(1,1);
        $MetodosGrupos = new MetodosGrupos($coneccion);

        return $MetodosGrupos;
    }

    /**
     * @test
     * @depends crearConeccion
     * @depends crearModeloMetodosGrupos
     */
    public function crearModelo($coneccion,$MetodosGrupos)
    {
        $this->assertSame(1,1);
        $Usuarios = new Usuarios($coneccion);
        $Grupos = new Grupos($coneccion);
        $Metodos = new Metodos($coneccion);
        $Menus = new Menus($coneccion);

        $MetodosGrupos->eliminarTodo();
        $Usuarios->eliminarTodo();
        $Grupos->eliminarTodo();
        $Metodos->eliminarTodo();
        $Menus->eliminarTodo();
        
        $grupos = [
            ['id' => 5,'nombre' => 'nombre5' , 'activo' => 1],
            ['id' => 6,'nombre' => 'nombre6' , 'activo' => 1]
        ];
        foreach ($grupos as $grupo) {
            $Grupos->registrar($grupo);
        }

        $menus = [
            ['id' => 1,'nombre' => 'nombre1' , 'activo' => 1],
            ['id' => 2,'nombre' => 'nombre2' , 'activo' => 1]
        ];
        foreach ($menus as $menu) {
            $Menus->registrar($menu);
        }

        $metodos = [
            ['id'=>1, 'nombre'=>'accion1', 'accion'=> 'accion1', 'icono' => 'icono-accion1', 'menu_id'=>1, 'activo_menu'=>0, 'activo_accion'=>1],
            ['id'=>2, 'nombre'=>'accion2', 'accion'=> 'accion2', 'icono' => 'icono-accion2', 'menu_id'=>1, 'activo_menu'=>0, 'activo_accion'=>1],
            ['id'=>3, 'nombre'=>'accion3', 'accion'=> 'accion3', 'icono' => 'icono-accion3', 'menu_id'=>1, 'activo_menu'=>0, 'activo_accion'=>1],
            ['id'=>4, 'nombre'=>'accion4', 'accion'=> 'accion4', 'icono' => 'icono-accion4', 'menu_id'=>1, 'activo_menu'=>0, 'activo_accion'=>1],

            ['id'=>5, 'nombre'=>'accion1', 'accion'=> 'accion1', 'icono' => 'icono-accion1', 'menu_id'=>2, 'activo_menu'=>0, 'activo_accion'=>1],
            ['id'=>6, 'nombre'=>'accion2', 'accion'=> 'accion2', 'icono' => 'icono-accion2', 'menu_id'=>2, 'activo_menu'=>0, 'activo_accion'=>1],
            ['id'=>7, 'nombre'=>'accion3', 'accion'=> 'accion3', 'icono' => 'icono-accion3', 'menu_id'=>2, 'activo_menu'=>0, 'activo_accion'=>1],
            ['id'=>8, 'nombre'=>'accion4', 'accion'=> 'accion4', 'icono' => 'icono-accion4', 'menu_id'=>2, 'activo_menu'=>0, 'activo_accion'=>1]
        ];
        foreach ($metodos as $metodo) {
            $Metodos->registrar($metodo);
        }

        $metodosgrupos = [
            ['id' => 1,'grupo_id' => 5 , 'metodo_id' => 1, 'activo' => 1],
            ['id' => 2,'grupo_id' => 5 , 'metodo_id' => 2, 'activo' => 1],
            ['id' => 3,'grupo_id' => 5 , 'metodo_id' => 3, 'activo' => 1],
            ['id' => 4,'grupo_id' => 5 , 'metodo_id' => 4, 'activo' => 1],

            ['id' => 5,'grupo_id' => 5 , 'metodo_id' => 5, 'activo' => 1],
            ['id' => 6,'grupo_id' => 5 , 'metodo_id' => 6, 'activo' => 1],
            ['id' => 7,'grupo_id' => 6 , 'metodo_id' => 1, 'activo' => 1],
            ['id' => 8,'grupo_id' => 6 , 'metodo_id' => 2, 'activo' => 1]
        ];

        foreach ($metodosgrupos as $metodogrupo) {
            $MetodosGrupos->registrar($metodogrupo);
        }

        return $Grupos;
    }

    /**
     * @test
     * @depends crearModelo
     */
    public function obtenerIdsMetodosGrupos($modelo)
    {
        $resultado = $modelo->obtenerIdsMetodosGrupos(5);
        $this->assertIsArray($resultado);
        $this->assertCount(6,$resultado);
    }

    /**
     * @test
     * @depends crearModelo
     */
    public function obtenerNombreGrupo($modelo)
    {
        $resultado = $modelo->obtenerNombreGrupo(5);
        $this->assertSame('nombre5',$resultado);

        $resultado = $modelo->obtenerNombreGrupo(6);
        $this->assertSame('nombre6',$resultado);
    }

    /**
     * @test
     * @depends crearModelo
     */
    public function obtenerMetodosAgrupadosPorMenu($modelo)
    {
        $resultado = $modelo->obtenerMetodosAgrupadosPorMenu(5);
        $this->assertIsArray($resultado);
        $this->assertCount(2,$resultado);

        $this->assertIsArray($resultado['nombre1']);
        $this->assertCount(4,$resultado['nombre1']);
        $this->assertArrayHasKey('id', $resultado['nombre1'][0]);
        $this->assertArrayHasKey('metodo', $resultado['nombre1'][0]);
        $this->assertArrayHasKey('activo', $resultado['nombre1'][0]);

        $this->assertIsArray($resultado['nombre2']);
        $this->assertCount(4,$resultado['nombre2']);
        $this->assertArrayHasKey('id', $resultado['nombre2'][0]);
        $this->assertArrayHasKey('metodo', $resultado['nombre2'][0]);
        $this->assertArrayHasKey('activo', $resultado['nombre2'][0]);

    }

    /**
     * @test
     * @depends crearModelo
     */
    public function registrar($modelo)
    {
        $registros = [
            ['id' => 1,'nombre' => 'nombre1' , 'activo' => 1],
            ['id' => 2,'nombre' => 'nombre2' , 'activo' => 1],
            ['id' => 3,'nombre' => 'nombre3' , 'activo' => 1],
            ['id' => 4,'nombre' => 'nombre4' , 'activo' => 1],
        ];

        foreach ($registros as $key => $registro) {

            $resultado = $modelo->registrar($registro);
            $this->assertIsArray($resultado);
            $this->assertCount(2,$resultado);
            $mensajeEsperado = 'datos registrados';
            $this->assertSame($mensajeEsperado,$resultado['mensaje']);
            $this->assertSame($registro['id'],$resultado['registroId']);

            $error = null;
            try {
                $resultado = $modelo->registrar($registro);
            } catch (ErrorBase $e) {
                $error = $e;
            }
            $mensajeEsperado = "grupo:{$registro['nombre']} ya registrad@";
            $this->assertSame($mensajeEsperado,$error->getMessage());

        }

        return $registros;

    }

    /**
     * @test
     * @depends crearModelo
     * @depends registrar
     */
    public function obtenerDatosConRegistroId($modelo,$registros)
    {
        foreach ($registros as $key => $registro) {

            $resultado = $modelo->obtenerDatosConRegistroId($registro['id']);
            $this->assertIsArray($resultado);
            $this->assertCount(7,$resultado);

            $columnas = [];
            $orderBy = [];
            $limit = '';
            $noUsarRelaciones = true;

            $resultado = $modelo->obtenerDatosConRegistroId($registro['id'], $columnas, $orderBy, $limit, $noUsarRelaciones);
            $this->assertIsArray($resultado);
            $this->assertCount(7,$resultado);
        }
        return $registros;
    }

    /**
     * @test
     * @depends crearModelo
     * @depends obtenerDatosConRegistroId
     */
    public function obtenerNumeroRegistros($modelo,$registros)
    {
        $resultado = $modelo->obtenerNumeroRegistros();
        $this->assertSame((count($registros)+$this->registrosExtras),$resultado);
        return $registros;
    }

    /**
     * @test
     * @depends crearModelo
     * @depends obtenerNumeroRegistros
     */
    public function existeRegistroId($modelo,$registros)
    {
        $resultado = $modelo->existeRegistroId(-1);
        $this->assertIsBool($resultado);
        $this->assertSame(false,$resultado);

        foreach ($registros as $key => $registro) {

            $resultado = $modelo->existeRegistroId($registro['id']);
            $this->assertIsBool($resultado);
            $this->assertSame(true,$resultado);
        }
        return $registros;
    }

    /**
     * @test
     * @depends crearModelo
     * @depends existeRegistroId
     */
    public function buscarPorId($modelo,$registros)
    {
        foreach ($registros as $key => $registro) {

            $resultado = $modelo->buscarPorId($registro['id']);
            $this->assertIsArray($resultado);
            $this->assertCount(2,$resultado);
            $this->assertSame(1,$resultado['numeroRegistros']);
            $this->assertCount(1,$resultado['registros']);
            
        }
        return $registros;
    }

    /**
     * @test
     * @depends crearModelo
     * @depends buscarPorId
     */
    public function modificarPorId($modelo,$registros)
    {
        $campoTabla = 'nombre';
        foreach ($registros as $key => $registro) {

            $registro[$campoTabla] = $registro[$campoTabla].'_modificado';
            $registros[$key][$campoTabla] = $registro[$campoTabla]; 

            $resultado = $modelo->modificarPorId($registro['id'],$registro);
            $this->assertIsArray($resultado);
            $this->assertCount(1,$resultado);
            $mensajeEsperado = 'registro modificado';
            $this->assertSame($mensajeEsperado,$resultado['mensaje']);
        }

        for ($i = 1 ; $i < 4 ; $i++) {
            $registro = $registros[$i]; 
            $registro[$campoTabla] = $registros[0][$campoTabla];

            $error = null;
            try {
                $resultado = $modelo->modificarPorId($registro['id'],$registro);
            } catch (ErrorBase $e) {
                $error = $e;
            }
            $mensajeEsperado = "grupo:{$registro[$campoTabla]} ya registrad@";
            $this->assertSame($mensajeEsperado,$error->getMessage());
        }

        return $registros;
    }

    /**
     * @test
     * @depends crearModelo
     * @depends modificarPorId
     */
    public function eliminarPorId($modelo,$registros)
    {
        for ($i = 0 ; $i < 2 ; $i++) {
            $registro = $registros[$i];
            $resultado = $modelo->eliminarPorId($registro['id']);
            $this->assertIsArray($resultado);
            $this->assertCount(1,$resultado);
            $mensajeEsperado = 'registro eliminado';
            $this->assertSame($mensajeEsperado,$resultado['mensaje']);
            unset($registros[$i]);
        }

        $resultado = $modelo->obtenerNumeroRegistros();
        $this->assertSame((count($registros)+$this->registrosExtras),$resultado);

        return $registros;
    }

    /**
     * @test
     * @depends crearModelo
     * @depends eliminarPorId
     * @depends crearModeloMetodosGrupos
     */
    public function eliminarTodo($modelo,$registrosl,$MetodosGrupos)
    {
        $MetodosGrupos->eliminarTodo();
        $resultado = $modelo->eliminarTodo();
        $this->assertIsArray($resultado);
        $this->assertCount(1,$resultado);
        $mensajeEsperado = 'registro eliminado';
        
        $resultado = $modelo->obtenerNumeroRegistros();
        $this->assertSame(0,$resultado);
    }
}