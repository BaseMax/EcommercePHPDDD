<?php

namespace App\Presentation\Http\Router;

class Redirect
{
    public static function to($path) : void{
        header('Location: '.$path);
        exit();
    }
}