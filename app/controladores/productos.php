<?php 

namespace App\controladores;

use App\ayudas\Html;
use App\clases\Controlador;
use App\interfaces\Database;
use App\errores\Base AS ErrorBase;
use App\modelos\Productos AS ModeloProductos;
use App\modelos\Categorias AS ModeloCategorias;

class productos extends Controlador
{
    private $Categorias;
    private $categoriasRegistros;

    public function __construct(Database $coneccion)
    {
        $this->modelo = new ModeloProductos($coneccion);
        $this->Categorias = new ModeloCategorias($coneccion);
        $this->nombreMenu = 'productos';
        $this->breadcrumb = false;

        try {
            $columas = ['categorias_id','categorias_nombre'];
            $this->categoriasRegistros = $this->Categorias->buscarTodo($columas,[],'',true)['registros'];
        } catch (ErrorBase $e) {
            $error = new ErrorBase('Error al obtener los categorias');
            $error->muestraError();
        }

        $this->camposLista = [
            'Id' => 'productos_id',
            'Producto' => 'productos_nombre',
            'Categoria' => 'categorias_nombre',
            'Cantidad' => 'productos_cantidad',
            'Alerta' => 'productos_alerta',
            'Precio Compra' => 'productos_precio_compra',
            'Precio Venta' => 'productos_precio_venta',
            'Activo' => 'productos_activo'
        ];
        
        parent::__construct();
    }
    
    public function generaInputFiltros (array $datosFiltros): void 
    {
        $col = 3;
        $this->sizeColumnasInputsFiltros = $col;
        
        //values de todos los inputs vacios
        $datos['productos+nombre'] = '';

        foreach ($datosFiltros as $key => $filtro) {
            $datos[$key] = $filtro;
        }

        $tablaCampo = 'productos+nombre';
        $placeholder = '';

        $this->htmlInputFiltros[$tablaCampo] = Html::inputText($col,'Producto',1,$tablaCampo,$placeholder,$datos[$tablaCampo]);
    }

    public function registrar()
    {
        $this->breadcrumb = true;
        
        $this->htmlInputFormulario[] = Html::inputTextRequired(4,'Producto',1,'nombre');
        $this->htmlInputFormulario[] = Html::inputNumberRequired(4,'Cantidad',2,'cantidad');
        $this->htmlInputFormulario[] = Html::inputNumberRequired(4,'Alerta',3,'alerta');
        $this->htmlInputFormulario[] = Html::inputFloatRequired(4,'Precio Compra',4,'precio_compra');
        $this->htmlInputFormulario[] = Html::inputFloatRequired(4,'Precio Venta',5,'precio_venta','','',true);
        $this->htmlInputFormulario[] = Html::selectConBuscador(
            'categorias_id',
            'Categoria', 
            'categoria_id', 
            4,
            $this->categoriasRegistros,
            'categorias_nombre',
            '-1',
            6,
        );

        $this->htmlInputFormulario[] = Html::selectActivo('Activo','activo',4,'1',7);

        $this->htmlInputFormulario[] = Html::submit('Registrar',$this->llaveFormulario,4);
    }

    public function modificar()
    {
        parent::modificar();
        $this->breadcrumb = true;

        $nombreMenu = $this->nombreMenu;
        $registro = $this->registro;
        
        $this->htmlInputFormulario[] = Html::inputTextRequired(4,'Producto',1,'nombre','',$registro["{$nombreMenu}_nombre"]);
        $this->htmlInputFormulario[] = Html::selectActivo('Activo','activo',4,$registro["{$nombreMenu}_activo"],2);

        $this->htmlInputFormulario[] = Html::submit('Modificar',$this->llaveFormulario,4);
    }

}