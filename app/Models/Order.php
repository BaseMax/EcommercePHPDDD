<?php

namespace App\Models;

class Order extends Model
{
    public $id;
    public $products;
    public $total_price;
    public $address;

    protected $cols = ['id', 'products', 'total_price', 'address'];
}