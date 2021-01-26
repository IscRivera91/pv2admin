<?php 

namespace App\interfaces;

use App\interfaces\Database;

interface GeneraConsultas 
{
    public function __construct(Database $coneccion);

    public function delete(string $tabla, array $filtros = []):string;

    public function insert(string $tabla = '', array $datos = []):string;
    
    public function select(
        string $tabla = '',
        array $columnas = [],
        array $filtros = [], 
        string $limit = '',
        array $orderBy = [],
        array $relaciones = []
    );

    public function update(
        string $tabla = '', 
        array $datos = [], 
        array $filtros = [] 
    ): string;

}