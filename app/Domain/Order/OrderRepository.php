<?php

namespace App\Domain\Order;

use App\Domain\Repository;
use App\Infrastructure\Database\IDatabase;

class OrderRepository extends Repository
{
    public function __construct(string $db)
    {
        parent::__construct(new Order(), $db);
    }

    public function getProducts(int $orderId){
        return $this->getBelogsToMany($orderId, 'App\Domain\Product\ProductRepository', 'order_products', 'order_id', 'product_id', ['quantity']);
    }

    public function setProducts(int $orderId, array $products){
        return $this->setBelogsToMany($orderId, $products, 'App\Domain\Product\ProductRepository', 'order_products', 'order_id', 'product_id', ['quantity']);
    }
}