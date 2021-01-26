<?php 

namespace App\errores;

use App\ayudas\Redireccion;
use App\errores\Base AS ErrorBase;

class Autentificacion extends ErrorBase
{

    public function __construct(string $mensaje = '') 
    {
        parent::__construct($mensaje);
    }

    public function muestraError(bool $esRecursivo = false)
    {
        Redireccion::enviar_login($this->message);
        exit;
    }

}