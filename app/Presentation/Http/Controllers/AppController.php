<?php

namespace App\Presentation\Http\Controllers;

use App\Presentation\Http\Router\Redirect;

class AppController extends Controller
{
    public function index(){
        Redirect::to('/products');
    }
}