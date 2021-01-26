<?php

namespace Test\clases;

use App\clases\MySQL\Database;
use PHPUnit\Framework\TestCase;
use App\errores\Base AS ErrorBase;

class MySQLDatabaseTest extends TestCase
{ 
    /**
     * @test
     */
    public function creaConeccion()
    {
        $this->assertSame(1,1);
        $coneccion = new Database();
        return $coneccion;
        
    }

    /**
     * @test
     * @depends creaConeccion
     */
    public function obtenColumnasTabla($coneccion)
    {
        $tabla = 'usuarios';
        $resultado = $coneccion->obtenColumnasTabla($tabla);
        $this->assertIsArray($resultado);
        $this->assertSame("id",$resultado[0]);
        $this->assertCount(12,$resultado);

        $tabla = 'grupos';
        $resultado = $coneccion->obtenColumnasTabla($tabla);
        $this->assertIsArray($resultado);
        $this->assertSame("id",$resultado[0]);
        $this->assertCount(7,$resultado);
    }

    /**
     * @test
     * @depends creaConeccion
     */
    public function ejecutaConsultaDelete($coneccion)
    {
        $tabla = 'usuarios';
        $error = null;
        try{
            $coneccion->ejecutaConsultaDelete("DELETEasdS FROM $tabla");
        }catch(ErrorBase $e){
            $error = $e;
        }
        $this->assertInstanceOf(ErrorBase::class, $error);
        $this->assertEquals($error->obtenCodigo(),42000);

        $resultado = $coneccion->ejecutaConsultaDelete("DELETE FROM $tabla");
        $this->assertCount(1,$resultado);
        
    }
    
    /**
     * @test
     * @depends creaConeccion
     */
    public function ejecutaConsultaInsert($coneccion)
    {
        $datos = ['id' => 9, 'usuario' =>'juan', 'password' =>'juan'];

        $consulta_base = 'INSERT INTO usuarios (id,usuario,password) VALUES (:id,:usuario,:password)';
        try{
            $resultado = $coneccion->ejecutaConsultaInsert("$consulta_base ");
        }catch(ErrorBase $e){
            $error = $e;
        }
        $this->assertInstanceOf(ErrorBase::class, $error);
        $this->assertEquals($error->obtenCodigo(),42000);
        
        $resultado = $coneccion->ejecutaConsultaInsert("$consulta_base ",$datos);
        $this->assertCount(2,$resultado);
        $this->assertSame('datos registrados',$resultado['mensaje']);
        $this->assertSame($datos['id'],$resultado['registroId']);

        $error = null;
        try{
            $resultado = $coneccion->ejecutaConsultaInsert("$consulta_base ",$datos);
        }catch(ErrorBase $e){
            $error = $e;
        }
        $this->assertInstanceOf(ErrorBase::class, $error);
        $this->assertEquals($error->obtenCodigo(),23000);

        return $datos;
        
    }

    /**
     * @test
     * @depends creaConeccion
     * @depends ejecutaConsultaInsert
     */
    public function ejecutaConsultaUpdate($coneccion,$datos)
    {
        $filtros = [
            ['campo' => 'id' , 'valor' => $datos['id'] , 'signoComparacion' => '=']
        ];

        $datos['usuario'] = 'ivan';

        $consulta_base = 'UPDATE usuarios SET usuario = :usuario';
        $error = null;
        try{
            $coneccion->ejecutaConsultaUpdate("$consulta_base WHERE id = :id");
        }catch(ErrorBase $e){
            $error = $e;
        }
        $this->assertInstanceOf(ErrorBase::class, $error);
        $this->assertEquals($error->obtenCodigo(),42000);

        $datos['password'] = 'asd123';

        $resultado = $coneccion->ejecutaConsultaUpdate("$consulta_base, password = :password WHERE id = :id ",$datos,$filtros);
        $this->assertCount(1,$resultado);
        $this->assertSame('registro modificado',$resultado['mensaje']);

        return $datos;
    }

    /**
     * @test
     * @depends creaConeccion
     * @depends ejecutaConsultaUpdate
     */
    public function ejecutaConsultaSelect($coneccion,$datos)
    {
        $filtros = [
            ['campo' => 'id' , 'valor' => $datos['id'] , 'signoComparacion' => '='],
            ['campo' => 'usuario' , 'valor' => $datos['usuario'] , 'signoComparacion' => '='],
            ['campo' => 'password' , 'valor' => $datos['password'] , 'signoComparacion' => '=']
        ];

        $consulta_base = 'SELECT * FROM usuarios WHERE id = :id AND usuario = :usuario AND password = :password';
        $error = null;
        try{
            $coneccion->ejecutaConsultaSelect("$consulta_base");
        }catch(ErrorBase $e){
            $error = $e;
        }
        $this->assertInstanceOf(ErrorBase::class, $error);
        $this->assertEquals($error->obtenCodigo(),42000);

        $resultado = $coneccion->ejecutaConsultaSelect("$consulta_base",$filtros);
        $this->assertCount(2,$resultado);
        $this->assertSame(1,$resultado['numeroRegistros']);
        $this->assertCount(12,$resultado['registros'][0]);
        $this->assertEquals($datos['id'],$resultado['registros'][0]['id']);
        $this->assertSame($datos['usuario'],$resultado['registros'][0]['usuario']);
        $this->assertSame($datos['password'],$resultado['registros'][0]['password']);
    }
    
}