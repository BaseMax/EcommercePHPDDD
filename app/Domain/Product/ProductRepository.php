<?php

namespace App\Domain\Product;

use App\Domain\Repository;

class ProductRepository extends Repository
{
    public function __construct($db)
    {
        parent::__construct(new Product(), $db);
    }
}