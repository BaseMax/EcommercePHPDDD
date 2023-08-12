<?php

namespace App\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Response\Abort;
use App\Response\Response;
use App\Router\Redirect;
use App\Validator\Validator;
use Exception;

class CheckoutController extends Controller
{
    public function review(){
        if(count($_SESSION['cart']) == 0){
            Redirect::to('/');
        }
        Response::view('Checkout/Review', []);
    }

    public function checkout(){
        if(count($_SESSION['cart']) == 0){
            Response::json(['products' => [], 'total_price' => 0]);
        }
        try{
            Validator::validate($_POST, [
                'address' => ['required']
            ]);
        } catch(Exception $e){
            Abort::badRequest();
        }
        $products = [];
        $total_price = 0;
        foreach($_SESSION['cart'] as $p){
            $product = (new Product)->where('id', intval($p['product']))->get();
            if(count($product) == 0){
                return Abort::serverError();
            }
            $product = $product[0];
            $total_price += $product->price * intval($p['quantity']);
            array_push($products, ['product' => $product, 'quantity' => intval($p['quantity'])]);
        }
        
        $order = new Order();
        $order->id = 1;
        Response::json($order->products());
        Response::json($products);
    }

    public function result($status){
        if($status == "ok"){

        } else if($status == "canceled"){

        }
        Abort::notFound();
    }
}