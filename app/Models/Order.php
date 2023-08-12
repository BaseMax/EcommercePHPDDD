<?php

namespace App\Models;

class Order extends Model
{
    public $id;
    public $total_price;
    public $address;
    public $status;

    protected $cols = ['id', 'total_price', 'address', 'status'];

    public function products(){
        return $this->belogsToMany('App\Models\Product', 'order_products', 'order_id', 'product_id', ['quantity']);
    }
}