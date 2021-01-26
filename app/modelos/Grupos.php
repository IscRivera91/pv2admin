<?php 

namespace App\modelos;

use App\clases\Modelo;
use App\modelos\Metodos;
use App\interfaces\Database;
use App\modelos\MetodosGrupos;
use App\errores\Base AS ErrorBase;

class Grupos extends Modelo
{
    public Modelo $MetodosGrupos;
    public Modelo $Metodos;

    private string $tabla;

    public function __construct(Database $coneccion)
    {
        $this->MetodosGrupos = new MetodosGrupos($coneccion);
        $this->Metodos = new Metodos($coneccion);
        $tabla = 'grupos';
        $this->tabla = $tabla;
        $relaciones = []; 
        $columnas = [
            'unicas' => ['grupo' => 'nombre'],
            'obligatorias' => ['nombre'],
            'protegidas' => []
        ];
        parent::__construct($coneccion, $tabla, $relaciones, $columnas);
    }

    public function obtenerNombreGrupo(int $grupoId):string
    {
        $columnas = ['nombre'];
        $orderBy = [];
        $limit = '';
        $noUsarRelaciones = true;
        $resultado = $this->buscarPorId($grupoId, $columnas, $orderBy, $limit, $noUsarRelaciones);

        return $resultado['registros'][0]["{$this->tabla}_nombre"];
    }

    public function obtenerMetodosAgrupadosPorMenu(int $grupoId):array
    {
        $columnas = ['id','nombre','menus_nombre'];
        $orderBy = ['menus.nombre' => 'ASC','metodos_nombre' => 'ASC'];

        try {
            $resultado = $this->Metodos->buscarTodo($columnas, $orderBy);
        } catch (ErrorBase $e) {
            throw new ErrorBase('Error al tratar de obtener los Metodos',$e);
        }

        try {
            $ids = $this->obtenerIdsMetodosGrupos($grupoId);
        } catch (ErrorBase $e) {
            throw new ErrorBase('Error al tratar de obtener los ids',$e);
        }

        $metodos = [];

        foreach ($resultado['registros'] as $registro) {

            $nombreMenu = $registro['menus_nombre'];
            if (!isset($metodos[$nombreMenu])){
                $metodos[$nombreMenu] = [];
            }
            $activo = 0;
            if (in_array($registro['metodos_id'],$ids)){
                $activo = 1;
            }

            $metodos[$nombreMenu ][] = [
                'id' => $registro['metodos_id'],
                'metodo' => $registro['metodos_nombre'],
                'activo' => $activo 
            ];
        }

        return $metodos;
    }

    public function obtenerIdsMetodosGrupos(int $grupoId):array
    {
        $filtros = [
            ['campo' => "{$this->MetodosGrupos->obtenerTabla()}.grupo_id", 'valor'=>$grupoId, 'signoComparacion'=>'=', 'conectivaLogica'=>'']
        ];

        $filtroEspecial = '';
    
        $columnas = ["{$this->MetodosGrupos->obtenerTabla()}_metodo_id"];
        $orderBy = [];
        $limit = '';
        $noUsarRelaciones = true;
        try {
            $resultado = $this->MetodosGrupos->buscarConFiltros($filtros, $filtroEspecial, $columnas, $orderBy, $limit, $noUsarRelaciones);
        } catch (ErrorBase $e) {
            throw new ErrorBase('Error al tratar de obtener los MetodosGrupos',$e);
        }

        $ids = [];

        foreach ($resultado['registros'] as $registro) {
            $ids[] = $registro["{$this->MetodosGrupos->obtenerTabla()}_metodo_id"];
        }

        return $ids;
    }
}