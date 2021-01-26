<?php 

namespace App\modelos;

use App\clases\Modelo;
use App\interfaces\Database;
use App\errores\Autentificacion AS ErrorAutentificacion;

class Sessiones extends Modelo
{
    public function __construct(Database $coneccion)
    {
        $tabla = 'sessiones';
        $relaciones = [
            'usuarios' => "{$tabla}.usuario_id",
            'grupos' => "{$tabla}.grupo_id"
        ]; 
        $columnas = [
            'unicas' => [],
            'obligatorias' => ['session_id','usuario_id','grupo_id'],
            'protegidas' => ['session_id']
        ];
        parent::__construct($coneccion, $tabla, $relaciones, $columnas);
    }

    public function buscarPorSessionId(string $sessionId):array
    {
        $filtros = [
            ['campo' => "sessiones.session_id" , 'valor' =>  $sessionId , 'signoComparacion' => '=' , 'conectivaLogica' => '' ]
        ];

        $filtroEspecial = '';

        $resultado = parent::buscarConFiltros($filtros, $filtroEspecial);

        if ( $resultado['numeroRegistros'] !== 1){
            throw new ErrorAutentificacion('sessionId no valido');
        }
        unset($resultado['registros'][0]['usuarios_password']);
        return $resultado['registros'][0];
    }

    public function eliminarConSessionId(string $sessionId):void
    {
        $filtros = [
            ['campo' => "sessiones.session_id" , 'valor' =>  $sessionId , 'signoComparacion' => '=' , 'conectivaLogica' => '' ]
        ];
        parent::eliminarConFiltros($filtros);
    }
}