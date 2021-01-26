<?php

namespace Test\modelos;

use App\modelos\Grupos;
use App\modelos\Usuarios;
use Test\LimpiarDatabase;
use App\errores\Base AS ErrorBase;
use PHPUnit\Framework\TestCase;

class UsuariosTest extends TestCase
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
        $Grupos = new Grupos($coneccion);
        $Usuarios = new Usuarios($coneccion);

        LimpiarDatabase::start($coneccion);

        $grupo = ['id' => 1,'nombre' => 'nombre1' , 'activo' => 1];
        $Grupos->registrar($grupo);

        return $Usuarios;
    }

    /**
     * @test
     * @depends crearModelo
     */
    public function registrar($modelo)
    {
        $registros = [
            ['id' => 1,'usuario' => 'usuario1', 'password' => 'userpass', 'correo_electronico' => 'mail1@mail.com', 'grupo_id' => 1 , 'activo' => 1],
            ['id' => 2,'usuario' => 'usuario2', 'password' => 'userpass', 'correo_electronico' => 'mail2@mail.com', 'grupo_id' => 1 , 'activo' => 1],
            ['id' => 3,'usuario' => 'usuario3', 'password' => 'userpass', 'correo_electronico' => 'mail3@mail.com', 'grupo_id' => 1 , 'activo' => 1],
            ['id' => 4,'usuario' => 'usuario4', 'password' => 'userpass', 'correo_electronico' => 'mail4@mail.com', 'grupo_id' => 1 , 'activo' => 1],
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
            $mensajeEsperado = "usuario:{$registro['usuario']} ya registrad@";
            $this->assertSame($mensajeEsperado,$error->getMessage());

            $registro['usuario'] = 'nuevo_usuario';

            $error = null;
            try {
                $resultado = $modelo->registrar($registro);
            } catch (ErrorBase $e) {
                $error = $e;
            }
            $mensajeEsperado = "correo:{$registro['correo_electronico']} ya registrad@";
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
            $this->assertCount(18,$resultado);

            $columnas = [];
            $orderBy = [];
            $limit = '';
            $noUsarRelaciones = true;

            $resultado = $modelo->obtenerDatosConRegistroId($registro['id'], $columnas, $orderBy, $limit, $noUsarRelaciones);
            $this->assertIsArray($resultado);
            $this->assertCount(11,$resultado);
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
        $campoTabla = 'usuario';
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
            $mensajeEsperado = "usuario:{$registro[$campoTabla]} ya registrad@";
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