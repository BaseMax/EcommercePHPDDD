<?php

namespace App\Domain\Product;

use App\Domain\IModel;

class Product implements IModel
{
    public int $id;
    public string $name;
    public int $price;

    private array $cols = ['id', 'name', 'price'];

    public function getCols() : array {
        return $this->cols;
    }
}