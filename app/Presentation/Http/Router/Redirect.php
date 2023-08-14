<?php

namespace App\Presentation\Http\Router;

class Redirect
{
    public static function to($path){
        header('Location: '.$path);
        exit();
    }
}