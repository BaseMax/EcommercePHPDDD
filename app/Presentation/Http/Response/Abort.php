<?php

namespace App\Presentation\Http\Response;

class Abort
{
    private static function render($message, $code){
        Response::view('Abort', ['message' => $message, 'code' => $code], $code);
    }

    public static function notFound(){
        self::render('Not Found', 404);
    }

    public static function badRequest(){
        self::render('Bad Request', 400);
    }

    public static function serverError(){
        self::render('Server Error', 500);
    }
}