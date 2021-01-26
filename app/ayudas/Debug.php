<?php

namespace App\ayudas;

class Debug
{
    public static function print($varible): void
    {
        echo '<pre>';
        print_r($varible);
        echo '</pre>';
    }

    public static function dump($varible): void
    {
        echo '<pre>';
        var_dump($varible);
        echo '</pre>';
    }
}