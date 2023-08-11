<?php

namespace App\Models;

class Product extends Model
{
    public $id;
    public $name;
    public $price;

    protected $cols = ['id', 'name', 'price'];
}