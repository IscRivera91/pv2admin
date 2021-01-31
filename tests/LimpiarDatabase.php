<?php

namespace Test;

use App\modelos\Menus;
use App\modelos\Grupos;
use App\modelos\Metodos;
use App\modelos\Usuarios;
use App\modelos\Sessiones;
use App\interfaces\Database;
use App\modelos\Categorias;
use App\modelos\MetodosGrupos;
use App\modelos\Productos;

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
        $Categotias = new Categorias($coneccion);
        $Productos = new Productos($coneccion);

        $Productos->eliminarTodo();
        $Categotias->eliminarTodo();
        $Sessiones->eliminarTodo();
        $MetodosGrupos->eliminarTodo();
        $Metodos->eliminarTodo();
        $Menus->eliminarTodo();
        $Usuarios->eliminarTodo();
        $Grupos->eliminarTodo();
    }
}