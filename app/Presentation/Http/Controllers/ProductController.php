<?php

namespace App\Presentation\Http\Controllers;

use App\Domain\Product\ProductRepository;
use App\Presentation\Http\Response\Response;
use App\Presentation\Http\Response\Abort;

class ProductController extends Controller
{
    public function products(){
        Response::view('Product/Products', ['products' => (new ProductRepository($this->mysqlDatabase))->get()]);
    }

    public function product($id){
        $product = (new ProductRepository($this->mysqlDatabase))->where('id', intval($id))->first();
        if($product == null) Abort::notFound();
        Response::view('Product/Product', ['product' => $product]);
    }
}