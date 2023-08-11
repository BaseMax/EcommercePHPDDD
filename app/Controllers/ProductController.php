<?php

namespace App\Controllers;

use App\Models\Product;
use App\Response\Abort;
use App\Response\Response;

class ProductController extends Controller
{
    public function products(){
        Response::view('Product/Products', ['products' => (new Product)->get()]);
    }

    public function product($id){
        $products = (new Product)->where('id', intval($id))->get();
        if(count($products) == 0) Abort::notFound();
        Response::view('Product/Product', ['product' => $products[0]]);
    }
}