<?php

namespace App\errores;
use PDOException;
use App\errores\Base AS ErrorBase;

class MySQL extends ErrorBase
{
    public function __construct(PDOException $error , string $consultaSQL = '') 
    {
        parent::__construct($error->getMessage(), null, $error->getCode(), $consultaSQL);
    }
}