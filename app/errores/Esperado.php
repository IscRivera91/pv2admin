<?php 

namespace App\errores;

use App\ayudas\Redireccion;
use App\errores\Base AS ErrorBase;

class Esperado extends ErrorBase
{
    private string $controlador;
    private string $metodo;
    private string $registroId;

    public function __construct(string $mensaje = '', string $controlador = '', string $metodo = '', string $registroId = '') 
    {
        $this->controlador = $controlador;
        $this->metodo = $metodo;
        $this->registroId = $registroId;
        parent::__construct($mensaje, null);
    }

    public function muestraError(bool $esRecursivo = false)
    {
        Redireccion::enviar($this->controlador, $this->metodo, SESSION_ID, $this->message, $this->registroId);
        exit;
    }

}