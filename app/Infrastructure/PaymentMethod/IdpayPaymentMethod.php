<?php

namespace App\Infrastructure\PaymentMethod;

use Exception;
use App\Domain\Order\Order;

class IdpayPaymentMethod
{
    private Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function paymentRequest() : string{
        $params = [
            'order_id' => $this->order->id,
            'amount' => $this->order->total_price,
            'callback' => $_ENV['APP_URL'].'/payment/callback'
        ];
        return $this->request($params, $_ENV['ID_PAY_PAYMENT_LINK']);
    }

    public function paymentVerifyRequest(string $id, int $order_id) : string{
        $params = array(
            'id' => $id,
            'order_id' => $order_id,
        );
        return $this->request($params, $_ENV['ID_PAY_PAYMENT_VERIFY']);
    }

    public function request(array $params, string $link) : string{
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-API-KEY: '.$_ENV['ID_PAY_API_KEY'],
            'X-SANDBOX: 1'
        ]);
        $result = curl_exec($ch);
        if (!curl_errno($ch)) {
            switch (curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                case 201:
                    break;
                case 200:
                    break;
                default:
                    throw new Exception("Something went wrong");
        }
        } else {
            throw new Exception("Something went wrong");
        }
        curl_close($ch);
        return $result;
    }
}