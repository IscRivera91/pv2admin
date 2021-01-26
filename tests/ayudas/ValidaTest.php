<?php

namespace Test\ayudas;

use App\ayudas\Valida;
use App\errores\Base AS ErrorBase;
use PHPUnit\Framework\TestCase;

class ValidaTest extends TestCase
{

   
    /**
     * @test
     */
    public function validaNombreTabla()
    {
        $error = null;
        try{
            Valida::nombreTabla('');
        }catch(ErrorBase $e){
            $error = $e;
        }
        $mensajeEsperado = 'El nombre de tabla no puede venir vacio';
        $this->assertSame($error->getMessage(),$mensajeEsperado);

        $error = null;
        try{
            Valida::nombreTabla(' sessiones usuarios ');
        }catch(ErrorBase $e){
            $error = $e;
        }
        $mensajeEsperado = 'El nombre de la tabla no es valido';
        $this->assertSame($error->getMessage(),$mensajeEsperado);
    }

     /**
     * @test
     */
    public function validaFiltros()
    {
        $error = null;
        try{
            $filtros = array();
            Valida::filtros($filtros);
        }catch(ErrorBase $e){
            $error = $e;
        }
        $mensajeEsperado = 'El array de filtros no puede estar vacio';
        $this->assertSame($error->getMessage(),$mensajeEsperado);

        $error = null;
        try{
            $filtros = array('');
            Valida::filtros($filtros);
        }catch(ErrorBase $e){
            $error = $e;
        }
        $mensajeEsperado = 'Los filtros deben ser un array de arrays';
        $this->assertSame($error->getMessage(),$mensajeEsperado);

        $error = null;
        try{
            $filtros = array([]);
            Valida::filtros($filtros);
        }catch(ErrorBase $e){
            $error = $e;
        }
        $mensajeEsperado = 'Cada filtro debe tener el key [\'campo\']';
        $this->assertSame($error->getMessage(),$mensajeEsperado);

        $error = null;
        try{
            $filtros = array(['campo' => 'id']);
            Valida::filtros($filtros);
        }catch(ErrorBase $e){
            $error = $e;
        }
        $mensajeEsperado = 'Cada filtro debe tener el key [\'valor\']';
        $this->assertSame($error->getMessage(),$mensajeEsperado);

        $error = null;
        try{
            $filtros = array(['campo' => 'id' , 'valor' => 1]);
            Valida::filtros($filtros);
        }catch(ErrorBase $e){
            $error = $e;
        }
        $mensajeEsperado = 'Cada filtro debe tener el key [\'signoComparacion\']';
        $this->assertSame($error->getMessage(),$mensajeEsperado);
    }

    /**
     * @test
     */
    public function validaArray()
    {
        $error = null;
        try{
            $nombreArray = 'Array';
            Valida::array($nombreArray,array());
        }catch(ErrorBase $e){
            $error = $e;
        }
        $mensajeEsperado = "Array:$nombreArray no puede ser un array vacio";
        $this->assertSame($error->getMessage(),$mensajeEsperado);
    }

    /**
     * @test
     */
    public function validaArrayAsociativo()
    {
        $error = null;
        try{
            $nombreArray = 'Array';
            Valida::arrayAsociativo($nombreArray,['juan','password']);
        }catch(ErrorBase $e){
            $error = $e;
        }
        $mensajeEsperado = "Array:$nombreArray debe ser un array asociativo";
        $this->assertSame($error->getMessage(),$mensajeEsperado);
    }

    /**
     * @test
     */
    public function validaConsulta()
    {
        $error = null;
        try{
            Valida::consulta('');
        }catch(ErrorBase $e){
            $error = $e;
        }
        $mensajeEsperado = 'La consulta no puede estar vacia';
        $this->assertSame($error->getMessage(),$mensajeEsperado);
    }
}