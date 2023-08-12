<?php

namespace App\Controllers;

use App\Response\Abort;
use App\Response\Response;
use App\Router\Redirect;
use App\Validator\Validator;
use App\Models\Product;
use Exception;

class CartController extends Controller
{
    public function cart(){
        if(count($_SESSION['cart']) == 0){
            Response::json(['products' => [], 'total_price' => 0]);
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
        Response::json(['products' => $products, 'total_price' => $total_price]);
    }

    public function add(){
        try{
            Validator::validate($_POST, [
                'product' => ['required'],
                'quantity' => ['required']
            ]);
        } catch(Exception $e){
            Abort::badRequest();
        }
        if(intval($_POST['quantity']) < 1){
            Abort::badRequest();
        }
        $added = false;
        for ($i=0; $i < count($_SESSION['cart']); $i++) { 
            if($_SESSION['cart'][$i]['product'] == intval($_POST['product'])){
                $_SESSION['cart'][$i]['quantity'] += intval($_POST['quantity']);
                $added = true;
                break;
            }
        }
        if(!$added){
            array_push($_SESSION['cart'], ['product' => intval($_POST['product']), 'quantity' => intval($_POST['quantity'])]);
        }
        $_SESSION['success'] = 'Product has been added to your cart';
        Redirect::to('/product/'.$_POST['product']);
    }
}