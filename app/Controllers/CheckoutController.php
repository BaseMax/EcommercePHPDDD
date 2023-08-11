<?php

namespace App\Controllers;

use App\Models\Product;
use App\Response\Abort;
use App\Response\Response;
use App\Router\Redirect;

class CheckoutController extends Controller
{
    public function review(){
        if(count($_SESSION['cart']) == 0){
            Redirect::to('/');
        }
        Response::view('Checkout/Review', []);
    }

    public function checkout(){

    }

    public function result($status){
        if($status == "ok"){

        } else if($status == "canceled"){

        }
        Abort::notFound();
    }
}