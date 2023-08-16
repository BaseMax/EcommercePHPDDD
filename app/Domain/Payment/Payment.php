<?php

namespace App\Domain\Payment;

use App\Domain\IModel;

class Payment implements IModel
{
    public int $id;
    public int $order_id;
    public string $idpay_id;
    public string $link;
    public int $amount;
    public int $status;
    public int $track_id;

    public array $cols = ['id', 'order_id', 'idpay_id', 'link', 'amount', 'status', 'track_id'];

    public function getCols() : array {
        return $this->cols;
    }
}