<?php 

namespace App\controladores;

use App\ayudas\Html;
use App\clases\Controlador;
use App\interfaces\Database;
use App\modelos\Categorias AS ModeloCategorias;

class categorias extends Controlador
{

    public function __construct(Database $coneccion)
    {
        $this->modelo = new ModeloCategorias($coneccion);
        $this->nombreMenu = 'categorias';
        $this->breadcrumb = false;

        $this->camposLista = [
            'Id' => 'categorias_id',
            'Categoria' => 'categorias_nombre',
            'Activo' => 'categorias_activo'
        ];

        parent::__construct();
    }

    public function generaInputFiltros (array $datosFiltros): void 
    {
        $col = 3;
        $this->sizeColumnasInputsFiltros = $col;
        
        //values de todos los inputs vacios
        $datos['categorias+nombre'] = '';

        foreach ($datosFiltros as $key => $filtro) {
            $datos[$key] = $filtro;
        }

        $tablaCampo = 'categorias+nombre';
        $placeholder = '';

        $this->htmlInputFiltros[$tablaCampo] = Html::inputText($col,'Categoria',1,$tablaCampo,$placeholder,$datos[$tablaCampo]);
    }

    public function registrar()
    {
        $this->breadcrumb = true;
        
        $this->htmlInputFormulario[] = Html::inputTextRequired(4,'Categoria',1,'nombre');

        $this->htmlInputFormulario[] = Html::submit('Registrar',$this->llaveFormulario,4);
    }

    public function modificar()
    {
        parent::modificar();
        $this->breadcrumb = true;

        $nombreMenu = $this->nombreMenu;
        $registro = $this->registro;

        $this->htmlInputFormulario[] = Html::inputTextRequired(4,'Categoria',1,'nombre','',$registro["{$nombreMenu}_nombre"]);

        $this->htmlInputFormulario[] = Html::submit('Modificar',$this->llaveFormulario,4);
    }

}