<?php

namespace App\Presentation\Http\Controllers;

use App\Domain\Product\ProductRepository;
use App\Presentation\Http\Response\Response;
use App\Presentation\Http\Response\Abort;
use App\Presentation\Http\Router\Redirect;
use App\Application\Validator\Validator;
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
            $product = (new ProductRepository($this->mysqlDatabase))->where('id', intval($p['product']))->first();
            if($product == null){
                return Abort::serverError();
            }
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