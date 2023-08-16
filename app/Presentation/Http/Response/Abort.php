<?php

namespace App\Presentation\Http\Response;

class Abort
{
    private static function render(string $message, int $code) : void{
        Response::view('Abort', ['message' => $message, 'code' => $code], $code);
    }

    public static function notFound() : void{
        self::render('Not Found', 404);
    }

    public static function badRequest() : void{
        self::render('Bad Request', 400);
    }

    public static function serverError() : void{
        self::render('Server Error', 500);
    }
}