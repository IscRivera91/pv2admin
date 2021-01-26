<?php

namespace Test\clases;

use App\clases\MySQL\Database;
use App\clases\MySQL\GeneraConsultas;
use PHPUnit\Framework\TestCase;

class MySQLGeneraConsultasTest extends TestCase
{
    /**
     * @test
     */
    public function creaGeneraConsultas()
    {
        $this->assertSame(1,1);

        $coneccion = new Database();
        $generaConsultas = new GeneraConsultas($coneccion);

        return $generaConsultas;
    }

    /**
     * @test
     * @depends creaGeneraConsultas
     */
    public function generaConsultaDelete($generaConsulta)
    {
        $tabla = 'usuarios';

        $consultaEsperada = 'DELETE FROM usuarios';
        $consulta = $generaConsulta->delete($tabla);
        $this->assertSame($consulta,$consultaEsperada);

        $filtros = [
            ['campo' => 'id' , 'valor' => '1' , 'signoComparacion' => '=', 'conectivaLogica' => '']
        ];

        $consultaEsperada = 'DELETE FROM usuarios WHERE id = :id';
        $consulta = $generaConsulta->delete($tabla,$filtros);
        $this->assertSame($consulta,$consultaEsperada);
    }

    /**
     * @test
     * @depends creaGeneraConsultas
     */
    public function generaConsultaInsert($generaConsulta)
    {
        $tabla = 'usuarios';
        $datos = ['user' => 'pedro' , 'password' => 'contra'];

        $consultaEsperada = 'INSERT INTO usuarios (user,password) VALUES (:user,:password)';
        $consulta = $generaConsulta->insert($tabla,$datos);
        
        $this->assertSame($consulta,$consultaEsperada);
    }


    /**
     * @test
     * @depends creaGeneraConsultas
     */
    public function generaConsultaSelect($generaConsulta)
    {
        $tabla = 'usuarios';
        $colunmas = ['usuarios_grupo_id'];

        $consultaEsperada = 'SELECT usuarios.grupo_id AS usuarios_grupo_id FROM usuarios';
        $consulta = $generaConsulta->select($tabla,$colunmas);
        $this->assertSame($consulta,$consultaEsperada);

        $colunmas = ['usuarios_usuario_registro_id'];
        
        $consultaEsperada = 'SELECT usuarios.usuario_registro_id AS usuarios_usuario_registro_id FROM usuarios';
        $consulta = $generaConsulta->select($tabla,$colunmas);
        $this->assertSame($consulta,$consultaEsperada);


        $colunmas = ['id'];
        $filtros = [
            ['campo' => 'id' , 'valor' => '1' , 'signoComparacion' => '=', 'conectivaLogica' => '']
        ]; 

        $consultaEsperada = 'SELECT usuarios.id AS usuarios_id FROM usuarios';
        $consulta = $generaConsulta->select($tabla,$colunmas);
        $this->assertSame($consulta,$consultaEsperada);

        $colunmas = ['usuarios_id'];

        $consultaEsperada = 'SELECT usuarios.id AS usuarios_id FROM usuarios';
        $consulta = $generaConsulta->select($tabla,$colunmas);
        $this->assertSame($consulta,$consultaEsperada);

        $colunmas = ['grupos_id'];

        $consultaEsperada = 'SELECT grupos.id AS grupos_id FROM usuarios';
        $consulta = $generaConsulta->select($tabla,$colunmas);
        $this->assertSame($consulta,$consultaEsperada);
       
        $consultaEsperada = 'SELECT grupos.id AS grupos_id FROM usuarios WHERE id = :id';
        $consulta = $generaConsulta->select($tabla,$colunmas,$filtros);
        $this->assertSame($consulta,$consultaEsperada);

        $colunmas = ['usuarios_id','grupos_id'];
        $relaciones = ['grupos' => 'usuarios.grupo_id'];
        $limit = '';
        $orderBy = [];

        $consultaEsperada = 'SELECT usuarios.id AS usuarios_id,grupos.id AS grupos_id FROM usuarios LEFT JOIN grupos ON grupos.id = usuarios.grupo_id WHERE id = :id';
        $consulta = $generaConsulta->select($tabla,$colunmas,$filtros,$limit,$orderBy,$relaciones);
        $this->assertSame($consulta,$consultaEsperada);

        $limit = 2;

        $consultaEsperada = 'SELECT usuarios.id AS usuarios_id,grupos.id AS grupos_id FROM usuarios LEFT JOIN grupos ON grupos.id = usuarios.grupo_id WHERE id = :id LIMIT 2';
        $consulta = $generaConsulta->select($tabla,$colunmas,$filtros,$limit,$orderBy,$relaciones);
        $this->assertSame($consulta,$consultaEsperada);

        $orderBy = ['usuarios.nombre' => 'DESC'];

        $consultaEsperada = 'SELECT usuarios.id AS usuarios_id,grupos.id AS grupos_id FROM usuarios LEFT JOIN grupos ON grupos.id = usuarios.grupo_id WHERE id = :id ORDER BY usuarios.nombre DESC LIMIT 2';
        $consulta = $generaConsulta->select($tabla,$colunmas,$filtros,$limit,$orderBy,$relaciones);
        $this->assertSame($consulta,$consultaEsperada);
    }

    /**
     * @test
     * @depends creaGeneraConsultas
     */
    public function generaConsultaUpdate($generaConsulta)
    {
        $tabla = 'usuarios';
        $datos = ['user' => 'pedro' , 'password' => 'contra'];
        

        $consultaEsperada = 'UPDATE usuarios SET user = :user , password = :password';
        $consulta = $generaConsulta->update($tabla,$datos);
        $this->assertSame($consulta,$consultaEsperada);

        $filtros = [
            ['campo' => 'id' , 'valor' => '1' , 'signoComparacion' => '=', 'conectivaLogica' => '']
        ];

        $consultaEsperada = 'UPDATE usuarios SET user = :user , password = :password WHERE id = :id';
        $consulta = $generaConsulta->update($tabla,$datos,$filtros);
        $this->assertSame($consulta,$consultaEsperada);
    }
}