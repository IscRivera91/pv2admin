<?php 

namespace App\controladores;

use App\ayudas\Html;
use App\ayudas\Redireccion;
use App\clases\Controlador;
use App\interfaces\Database;
use App\errores\Base AS ErrorBase;
use App\modelos\Grupos AS ModeloGrupos;
use App\modelos\Usuarios AS ModeloUsuarios;

class usuarios extends Controlador
{
    private array $gruposRegistros;
    public int $usuarioId;
    private $Grupos;

    public function __construct(Database $coneccion)
    {
        $this->modelo = new ModeloUsuarios($coneccion);
        $this->Grupos = new ModeloGrupos($coneccion);

        try {
            $columas = ['grupos_id','grupos_nombre'];
            $this->gruposRegistros = $this->Grupos->buscarTodo($columas,[],'',true)['registros'];
        } catch (ErrorBase $e) {
            $error = new ErrorBase('Error al obtner los grupos');
            $error->muestraError();
        }

        $this->nombreMenu = 'usuarios';
        $this->breadcrumb = false;

        $this->camposLista = [
            'Id' => 'usuarios_id',
            'Nombre' => 'usuarios_nombre_completo',
            'Usuario' => 'usuarios_usuario',
            'Grupo' => 'grupos_nombre',
            'Activo' => 'usuarios_activo'
            
        ];

        parent::__construct();
    }

    public function generaInputFiltros (array $datosFiltros): void 
    {
        $col = 3;
        $this->sizeColumnasInputsFiltros = $col;
        
        //values de todos los inputs vacios
        $datos['usuarios+nombre_completo'] = '';
        $datos['grupos+nombre'] = '-1';

        foreach ($datosFiltros as $key => $filtro) {
            $datos[$key] = $filtro;
        }

        $placeholder = '';

        $tablaCampo = 'usuarios+nombre_completo';
        $this->htmlInputFiltros[$tablaCampo] = Html::inputText($col,'Nombre',1,$tablaCampo,$placeholder,$datos[$tablaCampo]);

        $tablaCampo = 'grupos+nombre';

        $this->htmlInputFiltros[$tablaCampo] = Html::selectConBuscador(
            'grupos_nombre',
            'Grupo', 
            $tablaCampo, 
            $col,
            $this->gruposRegistros,
            'grupos_nombre',
            $datos[$tablaCampo],
            1,
            ''
        );
    }

    public function registrar()
    {
        $this->breadcrumb = true;
        
        $this->htmlInputFormulario[] = Html::inputTextRequired(4,'Nombre Completo',1,'nombre_completo');
        $this->htmlInputFormulario[] = Html::inputTextRequired(4,'Correo',1,'correo_electronico');
        $this->htmlInputFormulario[] = Html::inputTextRequired(4,'Usuario',1,'usuario');
        $this->htmlInputFormulario[] = Html::inputTextRequired(4,'Contraseña',1,'password');
        $this->htmlInputFormulario[] = Html::selectConBuscador(
            'grupos_id',
            'Grupo', 
            'grupo_id', 
            4,
            $this->gruposRegistros,
            'grupos_nombre',
            '-1',
            1
        );
        

        $this->htmlInputFormulario[] = Html::submit('Registrar',$this->llaveFormulario,4);
    }

    public function modificar()
    {
        parent::modificar();
        $this->breadcrumb = true;

        $nombreMenu = $this->nombreMenu;
        $registro = $this->registro;

        $this->htmlInputFormulario[] = Html::inputTextRequired(4,'Nombre Completo',1,'nombre_completo','',$registro["{$nombreMenu}_nombre_completo"]);
        $this->htmlInputFormulario[] = Html::inputTextRequired(4,'Correo',1,'correo_electronico','',$registro["{$nombreMenu}_correo_electronico"]);
        $this->htmlInputFormulario[] = Html::inputTextRequired(4,'Usuario',1,'usuario','',$registro["{$nombreMenu}_usuario"]);
        $this->htmlInputFormulario[] = Html::selectConBuscador(
            'grupos_id',
            'Grupo', 
            'grupo_id', 
            4,
            $this->gruposRegistros,
            'grupos_nombre',
            $registro["{$nombreMenu}_grupo_id"],
            1
        );

        $this->htmlInputFormulario[] = Html::submit('Modificar',$this->llaveFormulario,4);
    }

    public function nuevaContra()
    {
        $this->usuarioId = $this->validaRegistoId();
        
        try {
            $resultado = $this->modelo->buscarPorId($this->usuarioId);
        } catch (ErrorBase $e) {
            $error = new ErrorBase('Error al obtener datos de el registro a cambiar contraseña',$e);
            if ($this->redireccionar) {
                $error->muestraError();
                exit;
            }
            throw $error;
        }

        $this->registro = $resultado['registros'][0];

        $this->htmlInputFormulario['inputContraseña'] = Html::inputTextRequired(4,'Contraseña',1,'password');
        $this->htmlInputFormulario['submit'] = Html::submit('cambiar contraseña',$this->llaveFormulario,4);
    }

    public function nuevaContraBd()
    {

        $nombreLlaveFormulario = $this->llaveFormulario;
        
        if (!isset($_POST[$nombreLlaveFormulario])) {
            $mensaje = 'llave no valida';
            if (!$this->redireccionar) {
                return $mensaje;
            }
            $url = Redireccion::obtener($this->nombreMenu,'lista',SESSION_ID,$mensaje)."&pag={$this->obtenerNumeroPagina()}";
            header("Location: {$url}");
            exit;
        }

        $usuarioId = $_POST['usuarioId'];
        $datos = ['password' => md5($_POST['password']) ];
        
        try {
            $resultado = $this->modelo->modificarPorId($usuarioId, $datos);
        } catch (ErrorBase $e) {
            $error = new ErrorBase('Error al cambiar contraseña',$e);
            if ($this->redireccionar) {
                $error->muestraError();
                exit;
            }
            throw $error;
        }

        $mensaje = 'se cambio la contraseña';
        if (!$this->redireccionar) {
            return $mensaje;
        }
        $url = Redireccion::obtener($this->nombreMenu,'lista',SESSION_ID,$mensaje)."&pag={$this->obtenerNumeroPagina()}";
        header("Location: {$url}");
        exit; 
    }

}