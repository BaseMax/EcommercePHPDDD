<?php

namespace App\Domain\Order;

class Order
{
    public $id;
    public $total_price;
    public $address;
    public $status;

    public $cols = ['id', 'total_price', 'address', 'status'];
}