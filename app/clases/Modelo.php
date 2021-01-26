<?php

namespace App\clases;

use App\interfaces\Database;
use App\interfaces\GeneraConsultas;
use App\errores\Base AS ErrorBase;
use App\errores\Esperado AS ErrorEsperado;

abstract class Modelo 
{
    private GeneraConsultas $generaConsulta;
    private string $tabla;
    private Database $coneccion;
    private array $columnasUnicas;
    private array $columnasObligatorias;
    private array $columnasProtegidas;
    private array $relaciones;
    private array $respaldoRelaciones;

    public function __construct(Database $coneccion, string $tabla, array $relaciones, array $columnas)
    {
        $claseGeneraConsultas = 'App\\clases\\'.DB_TIPO.'\\GeneraConsultas';
        $this->generaConsulta = new $claseGeneraConsultas($coneccion);
        $this->coneccion = $coneccion;
        $this->tabla = $tabla;
        $this->relaciones = $relaciones;
        $this->respaldoRelaciones = $relaciones;
        $this->columnasUnicas = $columnas['unicas'];
        $this->columnasObligatorias = $columnas['obligatorias'];
        $this->columnasProtegidas = $columnas['protegidas'];
    }

    public function modificarPorId(int $id, array $datos):array
    {
        try{
            $this->validaColunmasUnicas($datos,$id);
        } catch(ErrorBase $e) {
            throw new ErrorEsperado($e->getMessage(), $this->tabla, 'modificar', $id);
        }
    
        $filtros = [
            ['campo'=>$this->tabla.'.id', 'valor'=>$id, 'signoComparacion'=>'=', 'conectivaLogica' => '']
        ];
        
        if (isset(get_defined_constants(true)['user']['USUARIO_ID'])) {
            $datos['usuario_actualizacion_id'] = USUARIO_ID;
        }

        try {
            $consulta = $this->generaConsulta->update($this->tabla,$datos,$filtros);
            $resultado = $this->coneccion->ejecutaConsultaUpdate($consulta,$datos,$filtros);
        } catch(ErrorBase $e) {
            throw new ErrorBase($e->getMessage(),$e);
        }
        return $resultado;
    }

    public function eliminarPorId(int $id):array
    {
        $filtros = [
            ['campo'=>$this->tabla.'.id', 'valor'=>$id, 'signoComparacion'=>'=', 'conectivaLogica' => '']
        ];
        try {
            $resultado = $this->eliminarConFiltros($filtros);
        } catch(ErrorBase $e) {
            throw new ErrorBase('Error al eliminar registro con id',$e);
        }
        return $resultado;
    }

    public function eliminarConFiltros(array $filtros):array
    {
        try {
            $consulta = $this->generaConsulta->delete($this->tabla, $filtros);
            $resultado = $this->coneccion->ejecutaConsultaDelete($consulta, $filtros);
        } catch(ErrorBase $e) {
            throw new ErrorBase('Error al elimnar registro con filtros',$e);
        }
        return $resultado;
    }

    public function eliminarTodo():array
    {
        try {
            $resultado = $this->eliminarConFiltros([]);
        } catch(ErrorBase $e) {
            throw new ErrorBase('Error al elimnar todos los registros',$e);
        }
        return $resultado;
    }

    public function buscarPorId(
        int $id,
        array $columnas = [],
        array $orderBy = [],
        string $limit = '',
        bool $noUsarRelaciones = false,
        array $nuevasRelaciones = []
    ): array {
        $filtros = [
            ['campo'=>$this->tabla.'.id', 'valor'=>$id, 'signoComparacion'=>'=', 'conectivaLogica' => '']
        ];
        
        $filtroEspecial = '';

        try {
            $resultado = $this->buscarConFiltros($filtros, $filtroEspecial, $columnas, $orderBy, $limit, $noUsarRelaciones, $nuevasRelaciones);
        } catch(ErrorBase $e) {
            throw new ErrorBase($e->getMessage(),$e);
        }
        return $resultado;
    }

    public function buscarConFiltros( 
        array $filtros,
        string $filtroEspecial = '',
        array $columnas = [],
        array $orderBy = [],
        string $limit = '',
        bool $noUsarRelaciones = false,
        array $nuevasRelaciones = []
    ): array {
        $this->analizaRelaciones($noUsarRelaciones, $nuevasRelaciones);
        try {
            $consulta = $this->generaConsulta->select(
                $this->tabla,
                $columnas,
                $filtros,
                $limit,
                $orderBy,
                $this->relaciones 
            );
            $resultado = $this->coneccion->ejecutaConsultaSelect($consulta, $filtros);
        } catch(ErrorBase $e) {
            throw new ErrorBase($e->getMessage(),$e);
        }
        $resultado = $this->eliminaColumnasProtegidas($resultado);
        return $resultado;
    }

    public function buscarTodo(
        array $columnas = [], 
        array $orderBy = [], 
        string $limit = '', 
        bool $noUsarRelaciones = false, 
        array $nuevasRelaciones = []
    ): array {
        try {
            $filtroEspecial = '';
            $resultado = $this->buscarConFiltros([], $filtroEspecial, $columnas, $orderBy, $limit, $noUsarRelaciones, $nuevasRelaciones);
        } catch(ErrorBase $e) {
            throw new ErrorBase($e->getMessage(),$e);
        }
        return $resultado;
    }

    public function existeRegistroId(int $id):bool
    {
        $columnas = ["{$this->tabla}_id"];
        $orderBy = [];
        $limit = '';
        $noUsarRelaciones = true;

        $resultado = $this->buscarPorId($id, $columnas, $orderBy, $limit, $noUsarRelaciones);
        
        if ($resultado['numeroRegistros'] === 1) {
            return true;
        }

        return false;
    }

    public function registrar(array $datos):array
    {
        try {
            $this->validaColumnasObligatorias($this->columnasObligatorias, $datos);
        } catch(ErrorBase $e) {
            throw new ErrorBase($e->getMessage(),$e);
        }

        try {
            $this->validaColunmasUnicas($datos);
        } catch(ErrorBase $e) {
            throw new ErrorEsperado($e->getMessage(), $this->tabla, 'registrar');
        }
               
        if (isset(get_defined_constants(true)['user']['USUARIO_ID'])) {
            $datos['usuario_registro_id'] = USUARIO_ID;
            $datos['usuario_actualizacion_id'] = USUARIO_ID;
            if (!isset($datos['activo'])) {
                $datos['activo'] = true;
            }
        }
              
        try {
            $consulta = $this->generaConsulta->insert($this->tabla, $datos);
            $resultado = $this->coneccion->ejecutaConsultaInsert($consulta, $datos);
        } catch(ErrorBase $e) {
            throw new ErrorBase($e->getMessage(),$e);
        }
        
        return $resultado;
    }

    public function obtenerDatosConRegistroId(
        int $id,
        array $columnas = [],
        array $orderBy = [],
        string $limit = '',
        bool $noUsarRelaciones = false,
        array $nuevasRelaciones = []
    ): array {
        try {
            $resultado = $this->buscarPorId($id, $columnas, $orderBy, $limit, $noUsarRelaciones, $nuevasRelaciones);
        } catch(ErrorBase $e) {
            throw new ErrorBase('Error al obtener datos con registro id',$e);
        }
        return $resultado['registros'][0];
    }

    public function obtenerTabla():string
    {
        return $this->tabla;
    }

    public function obtenerNumeroRegistros(array $filtros =[]):int
    {
        try {
            $filtroEspecial = '';
            $columnas = ['id']; 
            $orderBy = []; 
            $limit = ''; 
            $noUsarRelaciones = false;
            if (count($filtros) == 0){
                $noUsarRelaciones = true;
            } 
            $resultado = $this->buscarConFiltros($filtros, $filtroEspecial, $columnas, $orderBy, $limit, $noUsarRelaciones);
        } catch(ErrorBase $e) {
            throw new ErrorBase($e->getMessage(),$e);
        }
        return ( int ) $resultado['numeroRegistros'];
    }

    public function iniciaTransaccion()
    {
        $this->coneccion->beginTransaction();
    }

    public function cancelaTransaccion()
    {
        $this->coneccion->rollBack();
    }

    public function ejecutaTransaccion()
    {
        $this->coneccion->commit();
    }

    private function eliminaColumnasProtegidas(array $resultado):array
    {
        if (count($resultado['registros']) > 0) {
            foreach ($this->columnasProtegidas as $columnaProtegida) {
                if (isset($resultado['registros'][0]["{$this->tabla}_{$columnaProtegida}"])) {
                    foreach($resultado['registros'] as $clave => $registro) {
                        unset($resultado['registros'][$clave]["{$this->tabla}_{$columnaProtegida}"]);
                    }
                }
            }
        }
        return $resultado;
    }

    private function validaColunmasUnicas(array $datos , int $registroId = 0):void
    {
        $columnas = [$this->tabla.'_id'];
        foreach ($this->columnasUnicas as $nombreColumnaunica => $columnaUnica) {
            if (isset($datos[$columnaUnica])) {
                $filtros = [
                    ['campo'=>$columnaUnica, 'valor'=>$datos[$columnaUnica], 'signoComparacion'=>'=','conectivaLogica'=>''],
                    ['campo'=>"{$this->tabla}.id", 'valor'=>$registroId, 'signoComparacion'=>'<>', 'conectivaLogica'=>'AND']
                ];
                try {
                    $consulta = $this->generaConsulta->select($this->tabla,$columnas,$filtros);
                    $resultado = $this->coneccion->ejecutaConsultaSelect($consulta,$filtros);
                } catch(ErrorBase $e) {
                    throw new ErrorBase($e->getMessage(),$e);
                } 
                if ($resultado['numeroRegistros'] != 0) {
                    throw new ErrorEsperado($nombreColumnaunica.':'.$datos[$columnaUnica].' ya registrad@');
                }
            }  
        }
    }

    private function validaColumnasObligatorias(array $columnasObligatorias ,array $datos ):void
    {
        foreach($columnasObligatorias as $columnaObligatoria) {
            if (!array_key_exists($columnaObligatoria, $datos)) {
                throw new ErrorBase("El campo $columnaObligatoria debe existir en el array de datos");
            }
            if (is_null( $datos[$columnaObligatoria] ) ||  $datos[$columnaObligatoria] == '') {
                throw new ErrorBase("El campo $columnaObligatoria no pude ser vacio o null");
            }
        }
    }

    private function analizaRelaciones(bool $noUsarRelaciones, array $nuevasRelaciones):void
    {
        $this->relaciones = $this->respaldoRelaciones;
        if ($noUsarRelaciones){
            $this->relaciones = [];
        }
        if (count($nuevasRelaciones)){
            $this->relaciones = $nuevasRelaciones;
        }
    }

}