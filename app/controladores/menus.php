<?php 

namespace App\controladores;

use App\ayudas\Html;
use App\clases\Controlador;
use App\interfaces\Database;
use App\modelos\Menus AS ModeloMenus;

class menus extends Controlador
{

    public function __construct(Database $coneccion)
    {
        $this->modelo = new ModeloMenus($coneccion);
        $this->nombreMenu = 'menus';
        $this->breadcrumb = false;

        $this->camposLista = [
            'Id' => 'menus_id',
            'Menu' => 'menus_nombre',
            'Etiqueta' => 'menus_etiqueta',
            'Icono' => 'menus_icono',
            'Activo' => 'menus_activo'
        ];
        
        parent::__construct();
    }
    
    public function generaInputFiltros (array $datosFiltros): void 
    {
        $col = 3;
        $this->sizeColumnasInputsFiltros = $col;
        
        //values de todos los inputs vacios
        $datos['menus+nombre'] = '';

        foreach ($datosFiltros as $key => $filtro) {
            $datos[$key] = $filtro;
        }

        $tablaCampo = 'menus+nombre';
        $placeholder = '';

        $this->htmlInputFiltros[$tablaCampo] = Html::inputText($col,'Menu',1,$tablaCampo,$placeholder,$datos[$tablaCampo]);
    }

    public function registrar()
    {
        $this->breadcrumb = true;
        
        $this->htmlInputFormulario[] = Html::inputTextRequired(4,'Menu',1,'nombre');
        $this->htmlInputFormulario[] = Html::inputTextRequired(4,'Etiqueta',1,'etiqueta');
        $this->htmlInputFormulario[] = Html::inputTextRequired(4,'Icono',1,'icono');
        $this->htmlInputFormulario[] = Html::selectActivo('Activo','activo',4,'-1',2);

        $this->htmlInputFormulario[] = Html::submit('Registrar',$this->llaveFormulario,4);
    }

    public function modificar()
    {
        parent::modificar();
        $this->breadcrumb = true;

        $nombreMenu = $this->nombreMenu;
        $registro = $this->registro;
        
        $this->htmlInputFormulario[] = Html::inputTextRequired(4,'Menu',1,'nombre','',$registro["{$nombreMenu}_nombre"]);
        $this->htmlInputFormulario[] = Html::inputTextRequired(4,'Etiqueta',1,'etiqueta','',$registro["{$nombreMenu}_etiqueta"]);
        $this->htmlInputFormulario[] = Html::inputTextRequired(4,'Icono',1,'icono','',$registro["{$nombreMenu}_icono"]);
        $this->htmlInputFormulario[] = Html::selectActivo('Activo','activo',4,$registro["{$nombreMenu}_activo"],2);

        $this->htmlInputFormulario[] = Html::submit('Modificar',$this->llaveFormulario,4);
    }

}