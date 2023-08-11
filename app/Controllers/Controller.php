<?php

namespace App\Controllers;

abstract class Controller
{
    protected function getJsonBody(){
        return json_decode(file_get_contents('php://input'), true);
    }
}