<?php 

namespace App\clases;

use App\ayudas\Html;
use App\clases\Modelo;
use App\errores\Esperado AS ErrorEsperado;
use App\errores\Base AS ErrorBase;
use App\ayudas\Redireccion;

abstract class Controlador
{
    public int    $sizeColumnasInputsFiltros = 3; // Define el tamaño de los elementos en el filtro de la lista
    public int    $registrosPorPagina = 10;       // Numero de registros por pagina en la lista
    public bool   $breadcrumb = true;             // Define si se muestran o no los breadcrumb
    public bool   $redireccionar = true;          // Variable para saber si redirecciona o no 
    public array  $camposFiltrosLista = [];       // Define los campos de los filtros
    public array  $camposLista;                   // Define los campo que se van a mostrar en la lista
    public array  $filtrosLista = [];             // Define los filtros que se deben aplicar para obtener los registros de las listas
    public array  $filtrosListaBase = [];         // Define los filtros base que se deben aplicar para obtener los registros de las listas
    public array  $htmlInputFiltros = [];         // Codigo html de los inputs del filtro para la lista
    public array  $htmlInputFormulario = [];      // Codigo html de los inputs del del formulario de registro y modificacion
    public array  $registro;                      // Almacena el registros para poder editarlo
    public array  $registros;                     // Almacena los resgistros para poder mostrarlos en la lista
    public array  $orderByCamposLista = [];       // Define el orden en que se debe mostrar la lista
    public string $htmlPaginador = '';            // Codigo html del paginador
    public string $llaveFormulario;               // Llave que se ocupa que los $_POST son de un formulario valido
    public string $nombreMenu;                    // Define el menu al cual se deben hacer la redirecciones
    public string $nameSubmit;                    // Nombre de el boton de submit de los filtros en las listas
    public string $filtroEspecial = '';           // Filtro especial para las listas 
    public Modelo $modelo;                        // Modelo del menu con el que se esta trabajando

    public function __construct()
    {
        $this->llaveFormulario = md5(SESSION_ID);
    }

    public function modificar()
    {
        $registroId = $this->validaRegistoId();

        try {
            $resultado = $this->modelo->buscarPorId($registroId);
        } catch (ErrorBase $e) {
            $error = new ErrorBase('Error al obtener datos de el registro a modificar',$e);
            if ($this->redireccionar) {
                $error->muestraError();
                exit;
            }
            throw $error;
        }

        $this->registro = $resultado['registros'][0];

    }

    public function modificarBd()
    {
        $registroId = $this->validaRegistoId();

        $datos = $_POST;
        $nombreLlaveFormulario = $this->llaveFormulario;
        if (!isset($datos[$nombreLlaveFormulario])) {
            $mensaje = 'llave no valida';
            if (!$this->redireccionar) {
                return $mensaje;
            }
            Redireccion::enviar($this->nombreMenu,'lista',SESSION_ID,$mensaje);
            exit;
        }

        unset($datos[$nombreLlaveFormulario]);

        try {
            $resultado = $this->modelo->modificarPorId($registroId, $datos);
        } catch (ErrorBase $e) {
            $error = new ErrorBase('Error al modificar datos',$e);
            if ($this->redireccionar) {
                $error->muestraError();
                exit;
            }
            throw $error;
        }

        $mensaje = 'registro modificado';
        if (!$this->redireccionar) {
            return $mensaje;
        }
        $url = Redireccion::obtener($this->nombreMenu,'lista',SESSION_ID,'registro modificado')."&pag={$this->obtenerNumeroPagina()}";
        header("Location: {$url}");
        exit;   
    }

    public function registrarBd()
    {
        $datos = $_POST;
        $nombreLlaveFormulario = $this->llaveFormulario;
        if (!isset($datos[$nombreLlaveFormulario])) {
            $mensaje = 'llave no valida';
            if (!$this->redireccionar) {
                return $mensaje;
            }
            Redireccion::enviar($this->nombreMenu,'registrar',SESSION_ID);
            exit;
        }
        
        unset($datos[$nombreLlaveFormulario]);

        try {
            $resultado = $this->modelo->registrar($datos);
        } catch (ErrorBase $e) {
            $error = new ErrorBase('Error al registrar datos',$e);
            if ($this->redireccionar) {
                $error->muestraError();
                exit;
            }
            throw $error;
        }
        
        $mensaje = 'datos registrados';
        if (!$this->redireccionar) {
            return $mensaje;
        }
        Redireccion::enviar($this->nombreMenu,'lista',SESSION_ID,$mensaje);
        exit;
    }

    public function activarBd(){

        $registroId = $this->validaRegistoId();

        $datos["activo"] = 1;

        try {
            $resultado = $this->modelo->modificarPorId($registroId, $datos);
        } catch (ErrorBase $e) {
            $error = new ErrorBase('Error al activar registro',$e);
            if ($this->redireccionar) {
                $error->muestraError();
                exit;
            }
            throw $error;
        }

        $mensaje = 'registro activado';
        if (!$this->redireccionar) {
            return $mensaje;
        }
        $url = Redireccion::obtener($this->nombreMenu,'lista',SESSION_ID,$mensaje)."&pag={$this->obtenerNumeroPagina()}";
        header("Location: {$url}");
        exit;   
    }

    public function desactivarBd(){

        $registroId = $this->validaRegistoId();

        $datos["activo"] = 0;

        try {
            $resultado = $this->modelo->modificarPorId($registroId, $datos);
        } catch (ErrorBase $e) {
            $error = new ErrorBase('Error al desactivar registro',$e);
            if ($this->redireccionar) {
                $error->muestraError();
                exit;
            }
            throw $error;
        }

        $mensaje = 'registro desactivado';
        if (!$this->redireccionar) {
            return $mensaje;
        }
        $url = Redireccion::obtener($this->nombreMenu,'lista',SESSION_ID,$mensaje)."&pag={$this->obtenerNumeroPagina()}";
        header("Location: {$url}");
        exit;
        
    }

    public function eliminarBd()
    {
        $registroId = $this->validaRegistoId();

        try {
            $resultado = $this->modelo->eliminarPorId($registroId);
        } catch (ErrorBase $e) {
            $codigoError = $e->obtenCodigo();
            if ($codigoError == REGISTRO_RELACIONADO) {
                $mensaje = 'No se puede eliminar un registro que esta relacionado';
                if (!$this->redireccionar) {
                    return $mensaje;
                }
                $url = Redireccion::obtener($this->nombreMenu,'lista',SESSION_ID,$mensaje)."&pag={$this->obtenerNumeroPagina()}";
                header("Location: {$url}");
                exit;
            }
            $error = new ErrorBase('Error al eliminar registro',$e);
            if ($this->redireccionar) {
                $error->muestraError();
                exit;
            }
            throw $error;
        }

        $mensaje = 'registro eliminado';
        if (!$this->redireccionar) {
            return $mensaje;
        }
        $url = Redireccion::obtener($this->nombreMenu,'lista',SESSION_ID,$mensaje)."&pag={$this->obtenerNumeroPagina()}";
        header("Location: {$url}");
        exit;
    }

    public function generaInputFiltros(array $datosFiltros): void
    {
        
    }

    public function lista(): void
    {
        $columnas = [];
        $orderBy = [];

        $this->nameSubmit = "{$this->nombreMenu}ListaFiltro";

        $datosFiltros = $this->generaDatosFiltros();
        $this->generaInputFiltros($datosFiltros);

        if (count($this->filtrosListaBase) != 0) {
            $this->filtrosLista = [];
            $this->filtrosLista[] = ['campo' =>'1', 'valor'=>'1', 'signoComparacion'=>'=', 'conectivaLogica'=>''];
            foreach ($this->filtrosListaBase as $filtro) {
                $this->filtrosLista[] = $filtro;
            }
        }

        if (count($datosFiltros) != 0) {
            $this->filtrosLista = [];
            $this->aplicaFiltros($datosFiltros);
        }

        if (count($this->htmlInputFiltros) != 0) {
            $this->htmlInputFiltros[] = Html::submit('Filtrar', $this->nameSubmit, $this->sizeColumnasInputsFiltros);
            $urlDestino = Redireccion::obtener($this->nombreMenu,'lista',SESSION_ID).'&limpiaFiltro';
            $this->htmlInputFiltros[] = Html::linkBoton($urlDestino, 'Limpiar', $this->sizeColumnasInputsFiltros);
        }

        $limit = $this->obteneLimitPaginador();
        foreach ($this->camposLista as $nombre => $campo){
            $columnas[] = $campo;
        }
        $resultado = $this->modelo->buscarConFiltros($this->filtrosLista, $this->filtroEspecial, $columnas, $orderBy, $limit);
        $this->registros = $resultado['registros'];
    }

    private function generaDatosFiltros(): array
    {
        $datosFiltros = [];

        if  (isset($_GET['limpiaFiltro'])) {    
            unset($_SESSION[SESSION_ID][$this->nameSubmit]);
        }

        if (isset($_SESSION[SESSION_ID][$this->nameSubmit]) && !isset($_POST[$this->nameSubmit])){
            $_POST = $_SESSION[SESSION_ID][$this->nameSubmit];
        }

        if (isset($_POST[$this->nameSubmit])) {
            $_SESSION[SESSION_ID][$this->nameSubmit] = $_POST;
            $datosFiltros = $_POST;
        }

        return $datosFiltros;
    }

    private function aplicaFiltros(array $datosValue = []): void
    {
        $this->filtrosLista[] = ['campo' =>'1', 'valor'=>'1', 'signoComparacion'=>'=', 'conectivaLogica'=>''];

        foreach ($this->filtrosListaBase as $filtro) {
            $this->filtrosLista[] = $filtro;
        }

        foreach ($this->htmlInputFiltros as $tablaCampo => $value) {
            
            $campo = str_replace('+','.',$tablaCampo);

            $this->filtrosLista[] = [
                'campo' =>$campo,
                'valor'=>"%{$datosValue[$tablaCampo]}%",
                'signoComparacion'=>'LIKE',
                'conectivaLogica'=>'AND'
            ];
        }
        
    }

    public function validaRegistoId(): int
    {
        if (!isset($_GET['registroId'])) {
            $error = new ErrorEsperado('no se puede realizar la accion sin un registro id', $this->nombreMenu, 'lista');
            if ($this->redireccionar) {
                $error->muestraError();
                exit;
            }
            throw $error;
        }

        $registroId = (int) $_GET['registroId'];

        if (!$this->modelo->existeRegistroId($registroId)) {
            $error = new ErrorEsperado('no se puede realizar la accion si el registro no existe', $this->nombreMenu, 'lista');
            if ($this->redireccionar) {
                $error->muestraError();
                exit;
            }
            throw $error;
        }

        return $registroId;
    }

    public function obteneLimitPaginador(): string
    {
        $numeroRegistros = $this->modelo->obtenerNumeroRegistros($this->filtrosLista, $this->filtroEspecial);
        $numeroPaginas = (int) (($numeroRegistros-1) / (int)$this->registrosPorPagina );
        $numeroPaginas++;
        $numeroPagina = (int)$this->obtenerNumeroPagina();

        if ($numeroPagina > $numeroPaginas){
            if (!$this->redireccionar) {
                throw new ErrorBase('la pagina solicitada no existe');
            }
            Redireccion::enviar($this->nombreMenu,'lista',SESSION_ID,'');
            exit;
        }

        $limit = ( ( ($numeroPagina-1) * (int)$this->registrosPorPagina ) ).','.$this->registrosPorPagina.' ';

        if ($numeroPaginas > 1){
            $this->htmlPaginador = Html::paginador($numeroPaginas,$numeroPagina,$this->nombreMenu);
        }

        return $limit;
    }

    public function obtenerNumeroPagina(): int
    {
        $num_pagina = 1;
        if (isset($_GET['pag'])){
            $num_pagina = (int) $_GET['pag'];
        }
        return (int)$num_pagina;
    }
}