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
        $orderId = (new Order())->create([
            'total_price' => $total_price,
            'address' => $_POST['address'],
            'status' => 'pending'
        ]);
        $order = (new Order)->where('id', $orderId)->get()[0];
        $order->setProducts($products);
        Redirect::to('/payment/pay/'.$orderId);
    }

    public function result($status){
        Response::view('Checkout/Result', ['status' => $status]);
    }
}