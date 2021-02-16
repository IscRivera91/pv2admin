<?php

namespace App\traits;

trait JsonResponse {

    public function successResponse($data, $code = 200)
    {
        header('Content-Type: application/json');
        http_response_code($code);
        $repuesta = json_encode(['data' => $data]);
        echo $repuesta;
        exit;
    }

    public function errorResponse($data, $code)
    {
        header('Content-Type: application/json');
        http_response_code($code);
        $repuesta = json_encode(['data' => $data, 'code' => $code]);
        echo $repuesta;
        exit;
    }


}

