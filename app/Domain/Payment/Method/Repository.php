<?php

namespace App\Domain\Payment\Method;

use App\Domain\Order\Order;
use App\Infrastructure\PaymentMethod\IPaymentMethod;

class Repository
{
    private IPaymentMethod $paymentMethod;

    public function __construct(string $paymentMethod, Order $order)
    {
        $this->paymentMethod = new $paymentMethod($order);
    }

    public function paymentRequest() : string{
        return $this->paymentMethod->paymentRequest();
    }

    public function paymentVerifyRequest(string $id, int $order_id) : string{
        return $this->paymentMethod->paymentVerifyRequest($id, $order_id);
    }
}