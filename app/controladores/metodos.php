<?php 

namespace App\controladores;

use App\ayudas\Html;
use App\clases\Controlador;
use App\interfaces\Database;
use App\errores\Base AS ErrorBase;
use App\modelos\Menus AS ModeloMenus;
use App\modelos\Metodos AS ModeloMetodos;

class metodos extends Controlador
{
    private array $menuRegistros;
    private $Menus;

    public function __construct(Database $coneccion)
    {
        $this->modelo = new ModeloMetodos($coneccion);
        $this->Menus = new ModeloMenus($coneccion);

        try {
            $columas = ['menus_id','menus_nombre'];
            $this->menuRegistros = $this->Menus->buscarTodo($columas,[],'',true)['registros'];
        } catch (ErrorBase $e) {
            $error = new ErrorBase('Error al obtner los menus');
            $error->muestraError();
        }

        $this->nombreMenu = 'metodos';
        $this->breadcrumb = false;

        $this->camposLista = [
            'Id' => 'metodos_id',
            'Menu' => 'menus_nombre',
            'Metodo' => 'metodos_nombre',
            'Etiqueta' => 'metodos_etiqueta',
            'Icono' => 'metodos_icono',
            'Activo' => 'metodos_activo',
            'Activo Accion' => 'metodos_activo_accion',
            'Activo Menu' => 'metodos_activo_menu'
        ];

        parent::__construct();
    }

    public function generaInputFiltros (array $datosFiltros): void 
    {
        $col = 3;
        $this->sizeColumnasInputsFiltros = $col;
        
        //values de todos los inputs vacios
        $datos['metodos+nombre'] = '';
        $datos['menus+nombre'] = '-1';

        foreach ($datosFiltros as $key => $filtro) {
            $datos[$key] = $filtro;
        }

        $placeholder = '';

        $tablaCampo = 'menus+nombre';
        $this->htmlInputFiltros[$tablaCampo] = Html::selectConBuscador(
            'menus_nombre',
            'Menu', 
            $tablaCampo, 
            $col,
            $this->menuRegistros,
            'menus_nombre',
            $datos[$tablaCampo],
            1,
            ''
        );

        $tablaCampo = 'metodos+nombre';
        $this->htmlInputFiltros[$tablaCampo] = Html::inputText($col,'Metodo',2,$tablaCampo,$placeholder,$datos[$tablaCampo]);
    }

    public function registrar()
    {
        $this->breadcrumb = true;

        $this->htmlInputFormulario[] = Html::inputTextRequired(4,'Metodo',1,'nombre');
        $this->htmlInputFormulario[] = Html::inputText(4,'Etiqueta',1,'etiqueta');
        $this->htmlInputFormulario[] = Html::inputText(4,'Icono',1,'icono');
        $this->htmlInputFormulario[] = Html::selectConBuscador('menus_id','Menu', 'menu_id', 3,$this->menuRegistros,'menus_nombre','-1',1);
        $this->htmlInputFormulario[] = Html::selectActivo('Activo','activo',3,'-1',2);
        $this->htmlInputFormulario[] = Html::selectActivo('Activo Accion','activo_accion',3,'-1',3);
        $this->htmlInputFormulario[] = Html::selectActivo('Activo Menu','activo_menu',3,'-1',4);

        $this->htmlInputFormulario[] = Html::submit('Registrar',$this->llaveFormulario,4);
    }

    public function modificar()
    {
        parent::modificar();
        $this->breadcrumb = true;

        $nombreMenu = $this->nombreMenu;
        $registro = $this->registro;

        $this->htmlInputFormulario[] = Html::inputTextRequired(4,'Metodo',1,'nombre','',$registro["{$nombreMenu}_nombre"]);
        $this->htmlInputFormulario[] = Html::inputText(4,'Etiqueta',1,'etiqueta','',$registro["{$nombreMenu}_etiqueta"]);
        $this->htmlInputFormulario[] = Html::inputText(4,'Icono',1,'icono','',$registro["{$nombreMenu}_icono"]);
        $this->htmlInputFormulario[] = Html::selectConBuscador(
            'menus_id',
            'Menu', 
            'menu_id', 
            3,
            $this->menuRegistros,
            'menus_nombre',
            $registro["{$nombreMenu}_menu_id"],
            1
        );
        $this->htmlInputFormulario[] = Html::selectActivo('Activo','activo',3,$registro["{$nombreMenu}_activo"],2);
        $this->htmlInputFormulario[] = Html::selectActivo('Activo Accion','activo_accion',3,$registro["{$nombreMenu}_activo_accion"],3);
        $this->htmlInputFormulario[] = Html::selectActivo('Activo Menu','activo_menu',3,$registro["{$nombreMenu}_activo_menu"],4);

        $this->htmlInputFormulario[] = Html::submit('Modificar',$this->llaveFormulario,4);
    }

}