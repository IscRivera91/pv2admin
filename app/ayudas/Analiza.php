<?php

namespace App\ayudas;

class Analiza
{
    public static function campoMySQL(string $campo):string
    {
        $campo_explode = explode('.', $campo);
        $numero = count($campo_explode);
        if ($numero == 2) {
            return "{$campo_explode[0]}_{$campo_explode[1]}";
        }
        if ($numero == 1) {
            return $campo;
        }  
    }
}