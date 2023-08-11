<?php

namespace App\Router;

class Redirect
{
    public static function to($path){
        header('Location: '.$path);
        exit();
    }
}