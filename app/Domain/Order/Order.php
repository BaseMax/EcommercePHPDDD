<?php

namespace App\Domain\Order;

use App\Domain\IModel;

class Order implements IModel
{
    public int $id;
    public int $total_price;
    public string $address;
    public string $status;

    public array $cols = ['id', 'total_price', 'address', 'status'];

    public function getCols() : array {
        return $this->cols;
    }
}