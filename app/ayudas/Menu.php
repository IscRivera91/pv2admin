<?php 

namespace App\ayudas;

use App\interfaces\Database;
use App\modelos\MetodosGrupos;

class Menu
{
    public static function crear(
        Database $coneccion, 
        int $grupoId
    ): array {
        
        if (isset($_SESSION[SESSION_ID]['menuDefinido']) && GUARDAR_MENU_SESSION) {
            return $_SESSION[SESSION_ID]['menuDefinido'];
        }

        $modeloMetodosGrupos = new MetodosGrupos($coneccion);
        
        $filtros = [
            ['campo' => "{$modeloMetodosGrupos->obtenerTabla()}.grupo_id", 'valor'=>$grupoId, 'signoComparacion'=>'=', 'conectivaLogica'=>''],
            ['campo' => "metodos.activo", 'valor'=>true, 'signoComparacion'=>'=', 'conectivaLogica'=>'AND'],
            ['campo' => "metodos.activo_menu", 'valor'=>true, 'signoComparacion'=>'=', 'conectivaLogica'=>'AND'],
            ['campo' => "menus.activo", 'valor'=>true, 'signoComparacion'=>'=', 'conectivaLogica'=>'AND']
        ];

        $filtroEspecial = '';
        
        $columnas = [
            'metodos_nombre',
            'metodos_etiqueta',
            'menus_nombre',
            'menus_etiqueta',
            'menus_icono'
        ];

        $orderBy = [
            'menus.nombre' => 'ASC',
            'metodos.nombre' => 'ASC'
        ];

        $resultado = $modeloMetodosGrupos->buscarConFiltros($filtros, $filtroEspecial, $columnas, $orderBy);

        $menuDefinido = array();

        foreach ( $resultado['registros'] as $menu){
            if (!isset($menuDefinido[ $menu['menus_etiqueta'] ])){
                $menuDefinido[ $menu['menus_etiqueta'] ] = array($menu['menus_nombre'],
                    $menu['menus_icono'],$menu['menus_etiqueta']);
            }
            array_push($menuDefinido[ $menu['menus_etiqueta'] ] ,array(
                'label' =>  $menu['metodos_etiqueta'],
                'metodo' => $menu['metodos_nombre']
            ));

        }
        $_SESSION[SESSION_ID]['menuDefinido'] = $menuDefinido; 
        return $menuDefinido;
    }
}