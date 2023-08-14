<?php

namespace App\Domain\Payment;

class Payment
{
    public $id;
    public $order_id;
    public $idpay_id;
    public $link;
    public $amount;
    public $status;
    public $track_id;

    public $cols = ['id', 'order_id', 'idpay_id', 'link', 'amount', 'status', 'track_id'];
}