<?php

namespace App\Presentation\Http\Controllers;

abstract class Controller
{
    protected $mysqlDatabase = 'App\Infrastructure\Database\MysqlDatabase';
    protected $idpayPaymentMethod = 'App\Infrastructure\PaymentMethod\IdpayPaymentMethod';

    protected function getJsonBody(){
        return json_decode(file_get_contents('php://input'), true);
    }
}