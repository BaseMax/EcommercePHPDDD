<?php

namespace App\Domain\Product;

use App\Domain\Repository;
use App\Infrastructure\Database\IDatabase;

class ProductRepository extends Repository
{
    public function __construct(string $db)
    {
        parent::__construct(new Product(), $db);
    }
}