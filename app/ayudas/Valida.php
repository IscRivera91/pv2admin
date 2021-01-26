<?php

namespace App\ayudas;

use App\interfaces\Database;
use App\modelos\MetodosGrupos;
use App\errores\Base AS ErrorBase;

class Valida 
{
    
    public static function consulta(string $consulta = ''):void
    {
        if($consulta === '') {
            throw new ErrorBase('La consulta no puede estar vacia');
        }
    }

    public static function arrayAsociativo(string $nombreArray = '' ,array $array):void
    {
        array($nombreArray,$array);
        // https://cybmeta.com/comprobar-si-un-array-es-asociativo-o-secuencial-en-php
        if (!(array_keys( $array ) !== range( 0, count($array) - 1 ))) {
            throw new ErrorBase("Array:$nombreArray debe ser un array asociativo");
        }
    }

    public static function array(string $nombreArray = '', array $array):void
    {        
        if (count($array) === 0) {
            throw new ErrorBase("Array:$nombreArray no puede ser un array vacio");
        }
    }

    public static function filtros(array $filtros):void
    {
        if (count($filtros) === 0) {
            throw new ErrorBase('El array de filtros no puede estar vacio');
        }

        foreach ($filtros as $filtro)
        {
            if (!is_array($filtro)) {
                throw new ErrorBase('Los filtros deben ser un array de arrays');
            }
            if (!array_key_exists('campo', $filtro)) {
                throw new ErrorBase('Cada filtro debe tener el key [\'campo\']');
            }
            if (!array_key_exists('valor', $filtro)) {
                throw new ErrorBase('Cada filtro debe tener el key [\'valor\']');
            }
            if (!array_key_exists('signoComparacion', $filtro)) {
                throw new ErrorBase('Cada filtro debe tener el key [\'signoComparacion\']');
            }
            if (!array_key_exists('conectivaLogica', $filtro)) {
                throw new ErrorBase('Cada filtro debe tener el key [\'conectivaLogica\']');
            }
        }
    }

    public static function nombreTabla(string $tabla):void
    {
        $tabla = trim($tabla,' ');

        if ($tabla === '') {
            throw new ErrorBase('El nombre de tabla no puede venir vacio');
        }

        $explodeTabla = explode(' ',$tabla);
        
        if (count($explodeTabla) != 1) {
            throw new ErrorBase('El nombre de la tabla no es valido');
        }
    }

    public static function permiso(
        Database $coneccion, 
        int $grupoId,
        string $controladorActual,
        string $metodoActual
    ): bool {
        $modeloMetodosGrupos = new MetodosGrupos($coneccion);

        $filtros = [
            ['campo' => "{$modeloMetodosGrupos->obtenerTabla()}.grupo_id",'valor'=>$grupoId,          'signoComparacion'=>'=', 'conectivaLogica'=>''],
            ['campo' => "metodos.nombre",       'valor'=>$metodoActual,     'signoComparacion'=>'=', 'conectivaLogica'=>'AND'],
            ['campo' => "menus.nombre",         'valor'=>$controladorActual,'signoComparacion'=>'=', 'conectivaLogica'=>'AND']
        ];

        $filtroEspecial = '';

        $columnas = [
            'id',
            'metodos_nombre',
            'menus_nombre'
        ];

        $resultado = $modeloMetodosGrupos->buscarConFiltros($filtros, $filtroEspecial, $columnas);
        
        if ($resultado['numeroRegistros'] == 1) {
            return true;
        }

        return false;
    }

}