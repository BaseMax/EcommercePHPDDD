<?php

namespace App\Presentation\Http\Controllers;

abstract class Controller
{
    protected string $mysqlDatabase = 'App\Infrastructure\Database\MysqlDatabase';
    protected string $idpayPaymentMethod = 'App\Infrastructure\PaymentMethod\IdpayPaymentMethod';

    protected function getJsonBody() : array{
        return json_decode(file_get_contents('php://input'), true);
    }
}