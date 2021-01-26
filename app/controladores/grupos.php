<?php 

namespace App\controladores;

use App\ayudas\Html;
use App\clases\Modelo;
use App\modelos\Metodos;
use App\clases\Controlador;
use App\interfaces\Database;
use App\modelos\MetodosGrupos;
use App\errores\Base AS ErrorBase;
use App\modelos\Grupos AS ModeloGrupos;

class grupos extends Controlador
{
    public array $metodosAgrupadosPorMenu;
    public string $nombreGrupo;
    public int $grupoId;
    public $Metodos;
    public $MetodosGrupos;
    public $Grupos;

    public function __construct(Database $coneccion)
    {
        $this->modelo = new ModeloGrupos($coneccion);
        $this->Grupos = new ModeloGrupos($coneccion);
        $this->Metodos = new Metodos($coneccion);
        $this->MetodosGrupos = new MetodosGrupos($coneccion);
        $this->nombreMenu = 'grupos';
        $this->breadcrumb = false;

        $this->camposLista = [
            'Id' => 'grupos_id',
            'Grupo' => 'grupos_nombre',
            'Activo' => 'grupos_activo'
        ];

        parent::__construct();
    }

    public function generaInputFiltros (array $datosFiltros): void 
    {
        $col = 3;
        $this->sizeColumnasInputsFiltros = $col;
        
        //values de todos los inputs vacios
        $datos['grupos+nombre'] = '';

        foreach ($datosFiltros as $key => $filtro) {
            $datos[$key] = $filtro;
        }

        $tablaCampo = 'grupos+nombre';
        $placeholder = '';

        $this->htmlInputFiltros[$tablaCampo] = Html::inputText($col,'Grupo',1,$tablaCampo,$placeholder,$datos[$tablaCampo]);
    }

    public function registrar()
    {
        $this->breadcrumb = true;
        
        $this->htmlInputFormulario[] = Html::inputTextRequired(4,'Grupo',1,'nombre');

        $this->htmlInputFormulario[] = Html::submit('Registrar',$this->llaveFormulario,4);
    }

    public function modificar()
    {
        parent::modificar();
        $this->breadcrumb = true;

        $nombreMenu = $this->nombreMenu;
        $registro = $this->registro;

        $this->htmlInputFormulario[] = Html::inputTextRequired(4,'Grupo',1,'nombre','',$registro["{$nombreMenu}_nombre"]);

        $this->htmlInputFormulario[] = Html::submit('Modificar',$this->llaveFormulario,4);
    }

    public function permisos()
    {
        $grupoId = $this->validaRegistoId();
        $this->grupoId = $grupoId;
        $this->metodosAgrupadosPorMenu = $this->Grupos->obtenerMetodosAgrupadosPorMenu($grupoId);
        $this->nombreGrupo = $this->Grupos->obtenerNombreGrupo($grupoId);
        
    }

    public function altaPermiso()
    {
        try {
            
            $metodoId = $this->validaMedotoId();
            $grupoId = $this->validaGrupoId();

            $datos = ['grupo_id' => $grupoId, 'metodo_id' => $metodoId, 'activo' => 1];
            $this->MetodosGrupos->registrar($datos);

        } catch (ErrorBase $e) {
            header('Content-Type: application/json');
            $json = json_encode(['respuesta' => false,'error' => $e->getMessage()]);
            echo $json;
            exit;
        }

        header('Content-Type: application/json');
        $json = json_encode(['respuesta' => true,'error' => '']);
        echo $json;
        exit;

    }

    public function bajaPermiso()
    {
        try {
            
            $metodoId = $this->validaMedotoId();
            $grupoId = $this->validaGrupoId();

            $filtros = [
                ['campo'=>"{$this->MetodosGrupos->obtenerTabla()}.grupo_id", 'valor'=>$grupoId, 'signoComparacion'=>'=', 'conectivaLogica' => ''],
                ['campo'=>"{$this->MetodosGrupos->obtenerTabla()}.metodo_id", 'valor'=>$metodoId, 'signoComparacion'=>'=', 'conectivaLogica' => 'AND']
            ];
            $this->MetodosGrupos->eliminarConFiltros($filtros);

        } catch (ErrorBase $e) {
            header('Content-Type: application/json');
            $json = json_encode(['respuesta' => false,'error' => $e->getMessage()]);
            echo $json;
            exit;
        }

        header('Content-Type: application/json');
        $json = json_encode(['respuesta' => true,'error' => '']);
        echo $json;
        exit;

    }

    public function validaMedotoId()
    {
        if (!isset($_GET['metodoId'])) {
            throw new ErrorBase('se esperaba el parametro GET metodoId');  
        }

        $metodoId = (int) $_GET['metodoId'];

        if (!$this->Metodos->existeRegistroId($metodoId)) {
            throw new ErrorBase('el metodoId no existe'); 
        }
        
        return $metodoId;

    }

    public function validaGrupoId()
    {
        if (!isset($_GET['grupoId'])) {
            throw new ErrorBase('se esperaba el parametro GET grupoId');  
        }

        $grupoId = (int) $_GET['grupoId'];

        if (!$this->modelo->existeRegistroId($grupoId)) {
            throw new ErrorBase('el grupoId no existe');  
        }
        
        return $grupoId;
    }

}