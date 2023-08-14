<?php

namespace App\Infrastructure\PaymentMethod;

interface IPaymentMethod
{
    public function paymentRequest();
    public function paymentVerifyRequest($id, $order_id);
}