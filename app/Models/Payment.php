<?php

namespace App\Models;

class Payment extends Model
{
    public $id;
    public $order_id;
    public $idpay_id;
    public $link;
    public $amount;
    public $status;
    public $track_id;

    protected $cols = ['id', 'order_id', 'idpay_id', 'link', 'amount', 'status', 'track_id'];
}