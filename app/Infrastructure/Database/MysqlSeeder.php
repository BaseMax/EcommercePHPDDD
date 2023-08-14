<?php

use App\Domain\Product\ProductRepository;
use Dotenv\Dotenv;

require_once '../../../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__.'/../../..');
$dotenv->load();

$mysqlDatabase = 'App\Infrastructure\Database\MysqlDatabase';

(new ProductRepository($mysqlDatabase))->create([
    'name' => 'Water',
    'price' => 50000
]);

(new ProductRepository($mysqlDatabase))->create([
    'name' => 'Cacke',
    'price' => 200000
]);

(new ProductRepository($mysqlDatabase))->create([
    'name' => 'Pizza',
    'price' => 2500000
]);