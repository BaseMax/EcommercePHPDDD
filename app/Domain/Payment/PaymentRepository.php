<?php

namespace App\Domain\Payment;

use App\Domain\Repository;

class PaymentRepository extends Repository
{
    public function __construct($db)
    {
        parent::__construct(new Payment(), $db);
    }
}