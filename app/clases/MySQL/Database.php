<?php

namespace App\clases\MySQL;

use PDO;
use PDOException;
use PDOStatement;
use App\ayudas\Valida;
use App\ayudas\Analiza;
use App\interfaces\Database AS DatabaseInterface;
use App\errores\MySQL AS ErrorMySQL;


class Database implements DatabaseInterface
{
    private string $hostBd = DB_HOST;
    private string $usuarioBD = DB_USER;
    private string $passwordBd = DB_PASSWORD;
    private string $nombreBd = DB_NAME;
    private PDO $dbh;
    private PDOStatement $stmt;

    public function __construct(
        string $usuario = DB_USER, 
        string $passwordBd = DB_PASSWORD,
        string $nombreBd = DB_NAME, 
        string $hostBd = DB_HOST
    ) {
        $this->hostBd = $hostBd;
        $this->usuarioBD = $usuario;
        $this->passwordBd = $passwordBd;
        $this->nombreBd = $nombreBd;
        
        $dsn = "mysql:host={$this->hostBd};dbname={$this->nombreBd}";
        $opciones = array(PDO::ATTR_PERSISTENT=>true, PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION);

        try
        {
            $this->dbh = new PDO($dsn,$this->usuarioBD,$this->passwordBd,$opciones);
            $this->dbh->exec('set names utf8');
        } 
        catch (PDOException $e) 
        {
            throw new ErrorMySQL($e);
        }
    }

    private function blindarDatos(array $datos):void
    {
        if (count($datos) != 0)
        {
            foreach ( $datos as $campo => $valor)
            {
                $campo = Analiza::campoMySQL($campo);
                $this->stmt->bindValue(":{$campo}",$valor);
            }
        }
    }

    private function blindarFiltros(array $filtros):void
    {
        if (count($filtros) != 0){
            foreach ( $filtros as $filtro){
                $filtro['campo'] = Analiza::campoMySQL($filtro['campo']);
                $this->stmt->bindValue(":{$filtro['campo']}",$filtro['valor']);
            }
        }
    }

    public function ejecutaConsultaDelete(string $consulta = '', array $filtros = []):array
    {
        Valida::consulta($consulta);
        $this->stmt = $this->dbh->prepare($consulta);
        $this->blindarFiltros($filtros);
        try 
        {
            $this->stmt->execute();
            return ['mensaje' => 'registro eliminado'];
        } 
        catch (PDOException $e)
        {
            throw new ErrorMySQL($e,' Consulta: '.$consulta);
        }
    }

    public function ejecutaConsultaInsert(string $consulta = '', array $datos = []):array
    {
        Valida::consulta($consulta);
        $this->stmt = $this->dbh->prepare($consulta);
        $this->blindarDatos($datos);
        try 
        {
            $this->stmt->execute();
            $registroId = (int) $this->dbh->lastInsertId();
            return ['mensaje' => 'datos registrados','registroId' =>$registroId];
        } 
        catch (PDOException $e)
        {
            throw new ErrorMySQL($e,' Consulta: '.$consulta);
        }
    }

    public function ejecutaConsultaSelect(string $consulta = '', array $filtros = []):array
    {
        Valida::consulta($consulta);
        $this->stmt = $this->dbh->prepare($consulta);
        $this->blindarFiltros($filtros);
        try 
        {
            $this->stmt->execute();
            $numeroRegistros = $this->stmt->rowCount();
            $resultado = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
            return [ 'registros' => $resultado , 'numeroRegistros' => (int) $numeroRegistros ];
        } 
        catch (PDOException $e)
        {
            throw new ErrorMySQL($e,' Consulta: '.$consulta);
        }
    }

    public function ejecutaConsultaUpdate(string $consulta = '', array $datos = [], array $filtros = []):array
    {
        Valida::consulta($consulta);
        $this->stmt = $this->dbh->prepare($consulta);
        $this->blindarDatos($datos);
        $this->blindarFiltros($filtros);
        try 
        {
            $this->stmt->execute();
            return ['mensaje' => 'registro modificado'];
        } 
        catch (PDOException $e)
        {
            throw new ErrorMySQL($e,' Consulta: '.$consulta);
        }
    }

    public function beginTransaction(): void
    {
        $this->dbh->beginTransaction();
    }

    public function rollBack(): void
    {
        $this->dbh->rollBack();
    }

    public function commit(): void
    {
        $this->dbh->commit();
    }

    public function ejecutaQuery(string $query)
    {
        $this->stmt = $this->dbh->prepare($query);
        try 
        {
            $this->stmt->execute();
            return ['mensaje' => 'query ejecutada'];
        } 
        catch (PDOException $e)
        {
            throw new ErrorMySQL($e,' Query: '.$query);
        }
    }

    public function obtenColumnasTabla(string $tabla):array
    {
        $consulta = "SHOW COLUMNS FROM $tabla FROM {$this->nombreBd}";
        $this->stmt = $this->dbh->prepare($consulta);
        try 
        {
            $this->stmt->execute();
            $resultado = (array) $this->stmt->fetchAll(PDO::FETCH_ASSOC);
            return $this->generaArrayColumnas($resultado);
        } 
        catch (PDOException $e)
        {
            throw new ErrorMySQL($e,' Consulta: '.$consulta);
        }
    }

    private function generaArrayColumnas(array $columnasArray):array
    {
        $arrayColumnas = [];

        foreach ($columnasArray as $columna)
        {
            $arrayColumnas[] = "{$columna['Field']}";
        }

        return $arrayColumnas;
    }
}