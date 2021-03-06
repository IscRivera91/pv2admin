<?php

namespace Test\modelos;

use App\errores\Base AS ErrorBase;
use App\modelos\Categorias;
use App\modelos\Productos;
use Test\LimpiarDatabase;
use PHPUnit\Framework\TestCase;

class ProductosTest extends TestCase
{
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
    public function crearModelo($coneccion)
    {
        $this->assertSame(1,1);
        $Productos = new Productos($coneccion);
        $Categorias = new Categorias($coneccion);

        LimpiarDatabase::start($coneccion);

        $categorias = [
            ['id' => 1,'nombre' => 'categoria1' , 'activo' => 1],
            ['id' => 2,'nombre' => 'categoria2' , 'activo' => 1],
            ['id' => 3,'nombre' => 'categoria3' , 'activo' => 1],
            ['id' => 4,'nombre' => 'categoria4' , 'activo' => 1],
        ];

        foreach ($categorias as $key => $categoria) {
            $Categorias->registrar($categoria);
        }

        return $Productos;
    }

    /**
     * @test
     * @depends crearModelo
     */
    public function registrar($modelo)
    {
        $registros = [
            ['id'=>1,'categoria_id'=>1,'nombre'=>'p1','activo'=>1,'codigo_barras'=>'0051','cantidad'=>10,'precio_compra'=>25,'precio_venta'=>30],
            ['id'=>2,'categoria_id'=>2,'nombre'=>'p2','activo'=>1,'codigo_barras'=>'0052','cantidad'=>10,'precio_compra'=>25,'precio_venta'=>30],
            ['id'=>3,'categoria_id'=>3,'nombre'=>'p3','activo'=>1,'codigo_barras'=>'0053','cantidad'=>10,'precio_compra'=>25,'precio_venta'=>30],
            ['id'=>4,'categoria_id'=>4,'nombre'=>'p4','activo'=>1,'codigo_barras'=>'0054','cantidad'=>10,'precio_compra'=>25,'precio_venta'=>30],
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
            $mensajeEsperado = "producto:{$registro['nombre']} ya registrad@";
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
            $this->assertCount(21,$resultado);

            $columnas = [];
            $orderBy = [];
            $limit = '';
            $noUsarRelaciones = true;

            $resultado = $modelo->obtenerDatosConRegistroId($registro['id'], $columnas, $orderBy, $limit, $noUsarRelaciones);
            $this->assertIsArray($resultado);
            $this->assertCount(14,$resultado);
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
        $this->assertSame(count($registros),$resultado);
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
            $mensajeEsperado = "producto:{$registro[$campoTabla]} ya registrad@";
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
        $this->assertSame(count($registros),$resultado);

        return $registros;
    }

    /**
     * @test
     * @depends crearModelo
     * @depends eliminarPorId
     */
    public function eliminarTodo($modelo,$registrosl)
    {
        $resultado = $modelo->eliminarTodo();
        $this->assertIsArray($resultado);
        $this->assertCount(1,$resultado);
        $mensajeEsperado = 'registro eliminado';
        
        $resultado = $modelo->obtenerNumeroRegistros();
        $this->assertSame(0,$resultado);
    }
}