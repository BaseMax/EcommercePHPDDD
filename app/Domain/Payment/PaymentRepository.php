<?php

namespace App\Domain\Payment;

use App\Domain\Repository;
use App\Infrastructure\Database\IDatabase;

class PaymentRepository extends Repository
{
    public function __construct(string $db)
    {
        parent::__construct(new Payment(), $db);
    }
}