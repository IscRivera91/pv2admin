<?php

namespace App\clases;


use App\modelos\Usuarios;
use App\modelos\Sessiones;
use App\interfaces\Database;
use App\errores\Base AS ErrorBase;

class Autentificacion 
{
    private $Usuarios;
    private $Sessiones;
    public function __construct(Database $coneccion)
    {
        $this->Sessiones = new Sessiones($coneccion);
        $this->Usuarios = new Usuarios($coneccion);
    }
    
    public function defineConstantes(array $datos, string $sessionId):void
    {
        define('USUARIO_ID',$datos['usuarios_id']);
        define('GRUPO_ID',$datos['grupos_id']);
        define('SESSION_ID',$sessionId);
        define('NOMBRE_USUARIO',strtoupper($datos['usuarios_nombre_completo']));
        define('GRUPO',strtoupper($datos['grupos_nombre']));
        define('SEXO',$datos['usuarios_sexo']);
    }

    public function login():array
    {
        $this->validaUsuarioYPassword($_POST);

        $usuario = $this->Usuarios->login($_POST);
        $fechaHora = date('Y-m-d H:i:s');
        $sessionId = md5( md5( $_POST['usuario'].$_POST['password'].$fechaHora ) );

        $this->registraSessionId( $sessionId , $usuario , $fechaHora );

        return ['sessionId' => $sessionId , 'usuario' => $usuario , 'fechaHora' => $fechaHora];
    }

    public function logout(string $sessionId):void
    {
        $this->Sessiones->eliminarConSessionId($sessionId);
    }

    public function validaSessionId(string $sessionId):array
    {
        return $this->Sessiones->buscarPorSessionId($sessionId);
    }

    private function registraSessionId(string $sessionId, array $usuario, string $fechaHora):void
    {
        $datos['session_id'] = $sessionId;
        $datos['usuario_id'] = $usuario['usuarios_id'];
        $datos['grupo_id'] = $usuario['usuarios_grupo_id'];
        $datos['fecha_registro'] = $fechaHora;
        $this->Sessiones->registrar($datos);
    }

    private function validaUsuarioYPassword(array $datosPost):void
    {
        if ( !isset($datosPost['usuario']) )
        {
            throw new ErrorBase('Debe existir $_POST[\'usuarios\']');
        }
        if ( $datosPost['usuario'] == '')
        {
            throw new ErrorBase('$_POST[\'usuarios\'] no pude estar vacio');
        }
        if ( !isset($datosPost['password']) )
        {
            throw new ErrorBase('Debe existir $_POST[\'password\']');
        }
        if ( $datosPost['password'] == '')
        {
            throw new ErrorBase('$_POST[\'password\'] no pude estar vacio');
        }
        if  ( count($datosPost) !== 2 )
        {
            throw new ErrorBase('En la variable $_POST solo debe venir el usuario y el password');
        }
    }
}