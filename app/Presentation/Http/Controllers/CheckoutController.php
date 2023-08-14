<?php

namespace App\Presentation\Http\Controllers;

use App\Domain\Order\OrderRepository;
use App\Domain\Product\ProductRepository;
use App\Presentation\Http\Response\Response;
use App\Presentation\Http\Response\Abort;
use App\Presentation\Http\Router\Redirect;
use App\Application\Validator\Validator;
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
            $product = (new ProductRepository($this->mysqlDatabase))->where('id', intval($p['product']))->first();
            if($product == null){
                return Abort::serverError();
            }
            $total_price += $product->price * intval($p['quantity']);
            array_push($products, ['product' => $product, 'quantity' => intval($p['quantity'])]);
        }
        $orderId = (new OrderRepository($this->mysqlDatabase))->create([
            'total_price' => $total_price,
            'address' => $_POST['address'],
            'status' => 'pending'
        ]);
        (new OrderRepository($this->mysqlDatabase))->setProducts($orderId, $products);
        Redirect::to('/payment/pay/'.$orderId);
    }

    public function result($status){
        Response::view('Checkout/Result', ['status' => $status]);
    }
}