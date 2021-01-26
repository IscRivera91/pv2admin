<?php

namespace App\interfaces;

interface Database
{
    
    public function __construct(
        string $usuario = DB_USER, 
        string $passwordBd = DB_PASSWORD,
        string $nombreBd = DB_NAME, 
        string $hostBd = DB_HOST
    );

    public function ejecutaConsultaDelete(string $consulta = '', array $filtros = []):array;

    public function ejecutaConsultaInsert(string $consulta = '', array $datos = []):array;
    
    public function ejecutaConsultaSelect(string $consulta = '', array $filtros = []):array;

    public function ejecutaConsultaUpdate(string $consulta = '', array $datos = [], array $filtros = []):array;
    
    public function obtenColumnasTabla(string $tabla):array;

    public function beginTransaction(): void;
    
    public function rollBack(): void;
    
    public function commit(): void;
    
    
}