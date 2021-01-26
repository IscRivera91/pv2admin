<?php

namespace Test;

use App\modelos\Menus;
use App\modelos\Grupos;
use App\modelos\Metodos;
use App\modelos\Usuarios;
use App\modelos\Sessiones;
use App\interfaces\Database;
use App\modelos\MetodosGrupos;

class LimpiarDatabase
{
    public static function start(Database $coneccion): void
    {
        $MetodosGrupos = new MetodosGrupos($coneccion);
        $Metodos = new Metodos($coneccion);
        $Menus = new Menus($coneccion);
        $Usuarios = new Usuarios($coneccion);
        $Grupos = new Grupos($coneccion);
        $Sessiones = new Sessiones($coneccion);

        $Sessiones->eliminarTodo();
        $MetodosGrupos->eliminarTodo();
        $Metodos->eliminarTodo();
        $Menus->eliminarTodo();
        $Usuarios->eliminarTodo();
        $Grupos->eliminarTodo();
    }
}