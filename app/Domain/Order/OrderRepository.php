<?php

namespace App\Domain\Order;

use App\Domain\Repository;

class OrderRepository extends Repository
{
    public function __construct($db)
    {
        parent::__construct(new Order(), $db);
    }

    public function getProducts($orderId){
        return $this->getBelogsToMany($orderId, 'App\Domain\Product\ProductRepository', 'order_products', 'order_id', 'product_id', ['quantity']);
    }

    public function setProducts($orderId, $products){
        return $this->setBelogsToMany($orderId, $products, 'App\Domain\Product\ProductRepository', 'order_products', 'order_id', 'product_id', ['quantity']);
    }
}