<?php

namespace App\Models;

class Order extends Model
{
    public $id;
    public $total_price;
    public $address;
    public $status;

    protected $cols = ['id', 'total_price', 'address', 'status'];

    public function getProducts(){
        return $this->getBelogsToMany('App\Models\Product', 'order_products', 'order_id', 'product_id', ['quantity']);
    }

    public function setProducts($products){
        return $this->setBelogsToMany($products, 'App\Models\Product', 'order_products', 'order_id', 'product_id', ['quantity']);
    }
}