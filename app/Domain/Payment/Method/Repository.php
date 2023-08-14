<?php

namespace App\Domain\Payment\Method;

class Repository
{
    private $paymentMethod;

    public function __construct($paymentMethod, $order)
    {
        $this->paymentMethod = new $paymentMethod($order);
    }

    public function paymentRequest(){
        return $this->paymentMethod->paymentRequest();
    }

    public function paymentVerifyRequest($id, $order_id){
        return $this->paymentMethod->paymentVerifyRequest($id, $order_id);
    }
}