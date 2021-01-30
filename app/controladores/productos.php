<?php 

namespace App\controladores;

use App\ayudas\Html;
use App\ayudas\Redireccion;
use App\clases\Controlador;
use App\interfaces\Database;
use App\errores\Base AS ErrorBase;
use App\modelos\Productos AS ModeloProductos;
use App\modelos\Categorias AS ModeloCategorias;

class productos extends Controlador
{
    private $Categorias;
    private $Productos;
    public  $datosProducto;
    private $categoriasRegistros;

    public function __construct(Database $coneccion)
    {
        $this->Productos = new ModeloProductos($coneccion);
        $this->modelo = $this->Productos;
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
            'Codigo Barras' => 'productos_codigo_barras',
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
        $datos['productos+codigo_barras'] = '';
        $datos['productos+nombre'] = '';
        $datos['categorias+nombre'] = '';

        foreach ($datosFiltros as $key => $filtro) {
            $datos[$key] = $filtro;
        }

        $tablaCampo = 'productos+codigo_barras';
        $placeholder = '';
        $this->htmlInputFiltros[$tablaCampo] = Html::inputNumber($col,'Codigo Barras',1,$tablaCampo,$placeholder,$datos[$tablaCampo],false,true);

        $tablaCampo = 'productos+nombre';
        $placeholder = '';
        $this->htmlInputFiltros[$tablaCampo] = Html::inputText($col,'Producto',1,$tablaCampo,$placeholder,$datos[$tablaCampo]);

        $tablaCampo = 'categorias+nombre';
        $placeholder = '';
        $this->htmlInputFiltros[$tablaCampo] = Html::inputText($col,'Categoria',1,$tablaCampo,$placeholder,$datos[$tablaCampo]);
    }

    public function registrar()
    {
        $this->breadcrumb = true;

        $this->htmlInputFormulario[] = Html::inputNumberRequired(4,'Codigo Barras',8,'codigo_barras','','',false,true);
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
        $this->htmlInputFormulario[] = Html::inputNumberRequired(4,'Alerta',3,'alerta','',$registro["{$nombreMenu}_alerta"]);
        $this->htmlInputFormulario[] = Html::inputFloatRequired(4,'Precio Compra',4,'precio_compra','',$registro["{$nombreMenu}_precio_compra"]);
        $this->htmlInputFormulario[] = Html::inputFloatRequired(4,'Precio Venta',5,'precio_venta','',$registro["{$nombreMenu}_precio_venta"]);
        $this->htmlInputFormulario[] = Html::selectConBuscador(
            'categorias_id',
            'Categoria', 
            'categoria_id', 
            4,
            $this->categoriasRegistros,
            'categorias_nombre',
            $registro["{$nombreMenu}_categoria_id"],
            6,
        );

        $this->htmlInputFormulario[] = Html::submit('Modificar',$this->llaveFormulario,4);
    }

    public function agregarProducto()
    {
        $registroId = $this->validaRegistoId();

        $columnas = ['productos_id','productos_codigo_barras','productos_nombre','productos_cantidad'];
        $this->datosProducto = $this->Productos->buscarPorId($registroId,$columnas)['registros'][0];

    }

    public function agregarProductoBd()
    {
        $defaultValue = -1;
        $productoId =  isset($_POST['id']) ? $_POST['id'] : $defaultValue;
        $cantidadProducto =  isset($_POST['cantidadProducto']) ? $_POST['cantidadProducto'] : $defaultValue;

        if ($cantidadProducto == $defaultValue || $productoId == $defaultValue) {
            Redireccion::enviar($this->nombreMenu,'lista',SESSION_ID);
        }

        $this->Productos->agregarProductos($productoId,$cantidadProducto);

        
        $url = Redireccion::obtener($this->nombreMenu,'lista',SESSION_ID,'productos agregados')."&pag={$this->obtenerNumeroPagina()}";
        header("Location: {$url}");
        exit;
    }

}