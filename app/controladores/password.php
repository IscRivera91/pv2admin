<?php 

namespace App\controladores;

use App\ayudas\Html;
use App\modelos\Usuarios;
use App\ayudas\Redireccion;
use App\interfaces\Database;

class password
{
    private $Usuarios;
    public bool   $breadcrumb = false;
    public string $llaveFormulario; 

    public function __construct(Database $coneccion)
    {
        $this->llaveFormulario = md5(SESSION_ID);
        $this->Usuarios = new Usuarios($coneccion);
    }

    public function cambiarPassword()
    {
        $this->htmlInputFormulario[] = Html::inputPassword(4,'Contraseña Actual','passwordActual','passwordActual','','',true);
        $this->htmlInputFormulario[] = Html::inputPassword(4,'Nueva Contraseña','password','passwordNueva','','',true);
        $this->htmlInputFormulario[] = Html::inputPassword(4,' Confirmar Contraseña','confirmaPassword','confirmaPasswordNueva','','',true);
    }

    public function cambiarPasswordBd()
    {
        $datos = $_POST;
        $nombreLlaveFormulario = $this->llaveFormulario;
        if (!isset($datos[$nombreLlaveFormulario])) {
            Redireccion::enviar('password','cambiarPassword',SESSION_ID);
        }

        $filtros = [
            ['campo'=>'usuarios.password', 'valor'=>md5($datos['passwordActual']), 'signoComparacion'=>'=', 'conectivaLogica' => ''],
            ['campo'=>'usuarios.id', 'valor'=>USUARIO_ID, 'signoComparacion'=>'=', 'conectivaLogica' => 'AND']
        ];

        $filtroEspecial = '';

        $resultado = $this->Usuarios->buscarConFiltros($filtros, $filtroEspecial);

        if ($resultado['numeroRegistros'] != 1) {
            Redireccion::enviar('password','cambiarPassword',SESSION_ID,'Contraseña incorrecta');
        }

        $nuevaPassword = [
            'password' => md5($datos['passwordNueva']) 
        ];

        $this->Usuarios->modificarPorId(USUARIO_ID,$nuevaPassword);

        Redireccion::enviar('inicio','index',SESSION_ID,'se cambio la contraseña');

    }
}