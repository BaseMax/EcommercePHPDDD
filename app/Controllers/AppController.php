<?php

namespace App\Controllers;

use App\Router\Redirect;

class AppController extends Controller
{
    public function index(){
        Redirect::to('/products');
    }
}