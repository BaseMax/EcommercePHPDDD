<?php

namespace App\Presentation\Http\Response;

class Response
{
    public static function json(array $data, int $status = 200) : void{
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit();
    }

    public static function view(string $view, array $viewVars, int $status = 200) : void{
        http_response_code($status);
        require_once __DIR__.'/../Views/Layout/Header.php';
        require_once __DIR__.'/../Views/'.$view.'.php';
        require_once __DIR__.'/../Views/Layout/Footer.php';
        exit();
    }
}