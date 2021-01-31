<?php

namespace Test\clases;

use App\modelos\Grupos;
use App\modelos\Usuarios;
use Test\LimpiarDatabase;
use App\clases\Autentificacion;
use PHPUnit\Framework\TestCase;
use App\errores\Base AS ErrorBase;
use App\errores\Autentificacion AS ErrorAutentificacion;

class AutentificacionTest extends TestCase
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
    public function creaAutentificacion($coneccion)
    {
        $this->assertSame(1,1);

        $Usuarios = new Usuarios($coneccion);
        $Grupos = new Grupos($coneccion);

        LimpiarDatabase::start($coneccion);
        
        $grupo = ['id' => GRUPO_ID, 'nombre' => 'programador'];
        $Grupos->registrar($grupo);

        $usuarios = [
            ['id' => 1, 'usuario' => 'admin','correo_electronico' => 'admin@mail.com', 'password' => 'admin', 'grupo_id' => GRUPO_ID, 'activo' => 1],
            ['id' => 2, 'usuario' => 'admin2','correo_electronico' => 'admin2@mail.com', 'password' => 'admin', 'grupo_id' => GRUPO_ID, 'activo' => 0]
        ];

        foreach ($usuarios as $usuario) {
            $Usuarios->registrar($usuario);
        }

        $autentificacion = new Autentificacion($coneccion);
        return $autentificacion;
    }

    /**
     * @test
     * @depends creaAutentificacion
     */
    public function login($autentificacion)
    {
        $error = null;
        try{
            $resultado = $autentificacion->login();
        }catch(ErrorBase $e){
            $error = $e;
        }
        $mensajeEsperado = 'Debe existir $_POST[\'usuario\']';
        $this->assertSame($error->getMessage(),$mensajeEsperado);

        $_POST['usuario'] = '';

        $error = null;
        try{
            $resultado = $autentificacion->login();
        }catch(ErrorBase $e){
            $error = $e;
        }
        $mensajeEsperado = '$_POST[\'usuarios\'] no pude estar vacio';
        $this->assertSame($error->getMessage(),$mensajeEsperado);

        $_POST['usuario'] = 'juan';

        $error = null;
        try{
            $resultado = $autentificacion->login();
        }catch(ErrorBase $e){
            $error = $e;
        }
        $mensajeEsperado = 'Debe existir $_POST[\'password\']';
        $this->assertSame($error->getMessage(),$mensajeEsperado);

        $_POST['password'] = '';

        $error = null;
        try{
            $resultado = $autentificacion->login();
        }catch(ErrorBase $e){
            $error = $e;
        }
        $mensajeEsperado = '$_POST[\'password\'] no pude estar vacio';
        $this->assertSame($error->getMessage(),$mensajeEsperado);
        
        $_POST['password'] = 'asd';

        $error = null;
        try{
            $resultado = $autentificacion->login();
        }catch(ErrorBase $e){
            $error = $e;
        }
        $mensajeEsperado = "usuario o contraseña incorrecto";
        $this->assertSame($error->getMessage(),$mensajeEsperado);
        $this->assertInstanceOf(ErrorAutentificacion::class, $error);
        
        $_POST['password'] = 'admin';
        $_POST['usuario'] = 'admin';
        
        $resultado = $autentificacion->login();
        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('sessionId',$resultado);
        $this->assertArrayHasKey('usuario',$resultado);
        $this->assertIsArray($resultado['usuario']);
        $sessionId = md5( md5( $_POST['usuario'].$_POST['password'].$resultado['fechaHora'] ) );
        $this->assertSame($sessionId,$resultado['sessionId']);

        return $sessionId;
    }

    /**
     * @test
     * @depends creaAutentificacion
     * @depends login
     */
    public function validaSessionId($autentificacion,$sessionId)
    {
        $resultado = $autentificacion->validaSessionId($sessionId);
        $this->assertIsArray($resultado);
        $this->assertCount(25,$resultado);
        $this->assertArrayHasKey('usuarios_id',$resultado);
        $this->assertArrayHasKey('grupos_id',$resultado);
        $this->assertArrayHasKey('grupos_nombre',$resultado);
        $this->assertArrayHasKey('usuarios_nombre_completo',$resultado);
        $this->assertArrayHasKey('usuarios_sexo',$resultado);
        return $sessionId;
    }
    
}