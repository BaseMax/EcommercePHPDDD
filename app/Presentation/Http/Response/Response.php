<?php

namespace App\Presentation\Http\Response;

class Response
{
    public static function json($data, $status = 200){
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit();
    }

    public static function view($view, $viewVars, $status = 200){
        http_response_code($status);
        require_once __DIR__.'/../Views/Layout/Header.php';
        require_once __DIR__.'/../Views/'.$view.'.php';
        require_once __DIR__.'/../Views/Layout/Footer.php';
        exit();
    }
}