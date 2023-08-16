<?php

namespace App\Infrastructure\PaymentMethod;

interface IPaymentMethod
{
    public function paymentRequest() : string;
    public function paymentVerifyRequest(string $id, int $order_id) : string;
}