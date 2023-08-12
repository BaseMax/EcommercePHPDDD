<?php

use App\Models\Product;
use Dotenv\Dotenv;

require_once '../../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__.'/../..');
$dotenv->load();

(new Product())->create([
    'name' => 'Water',
    'price' => 50000
]);

(new Product())->create([
    'name' => 'Cacke',
    'price' => 200000
]);

(new Product())->create([
    'name' => 'Pizza',
    'price' => 2500000
]);