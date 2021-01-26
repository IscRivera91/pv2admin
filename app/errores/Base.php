<?php
// https://www.php.net/manual/es/language.exceptions.php
// https://www.php.net/manual/es/language.exceptions.extending.php

namespace App\errores;

use Exception;

class Base extends Exception
{

    private string $consultaSQL;
    private array $errorInformacion;
    private string $codigo;

    public function __construct(string $mensaje = '' , Base $errorAnterior = null , string $codigo = '' , string $consultaSQL = '' ) 
    {

        if (!is_null($errorAnterior)) {
            $codigo = $errorAnterior->obtenCodigo();
            $consultaSQL = $errorAnterior->obtenConsultaSQL();
        }
        $this->codigo = $codigo;
        $this->consultaSQL = $consultaSQL;
        parent::__construct($mensaje, 0 ,$errorAnterior);
    }

    public function obtenConsultaSQL()
    {
        return $this->consultaSQL;
    }

    public function obtenCodigo()
    {
        return $this->codigo;
    }

    public function muestraError(bool $esRecursivo = false)
    {
        $this->configuraErrorHtml();

        if ($esRecursivo)
        {
            return $this->errorInformacion;
        }

        if (ES_PRODUCCION)
        {
            header('Location: '.RUTA_PROYECTO.'error.php?session_id='.SESSION_ID.'&codigo='.$this->obtenCodigo());
            exit;
        }
        
        echo '<font size="3">';
        print_r($this->errorInformacion);
        echo '</font>';
    }

    private function configuraErrorHtml():void
    {
        $errorAnterrior = $this->obtenErrorAnterior();
        $this->errorInformacion = 
        [
            'mensaje'=> '<div class="error-base-msj"><b>'.$this->message.'</b></div>',
            'file'=> '<div><b>'.$this->file.'</b></div>',
            'line'=> '<div><b>'.$this->line.'</b></div><hr>',
            'datos'=>$errorAnterrior
        ];
    }

    private function configuraErrorJson():void
    {
        $errorAnterrior = $this->obtenErrorAnterior();
        $this->errorInformacion = 
        [
            'mensaje'=> $this->message,
            'file'=> $this->file,
            'line'=> $this->line,
            'datos'=>$errorAnterrior
        ];
    }

    private function obtenErrorAnterior()
    {
        $errorAnterior = $this->consultaSQL;
        
        if (!is_null($this->getPrevious())) 
        {
            $errorAnterior = $this->getPrevious()->muestraError(true);
        }
        return $errorAnterior;
    }
}