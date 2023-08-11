<?php

session_start();
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

use App\Controllers\AppController;
use App\Controllers\ProductController;
use App\Controllers\CartController;
use App\Controllers\CheckoutController;
use App\Controllers\PaymentController;
use Dotenv\Dotenv;
use App\Router\Router;
use App\Response\Abort;

require_once '../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();

Router::get('/', [AppController::class, 'index']);

Router::get('/products', [ProductController::class, 'products']);
Router::get('/product/:id', [ProductController::class, 'product']);

Router::get('/cart', [CartController::class, 'cart']);
Router::post('/cart/add', [CartController::class, 'add']);

Router::get('/checkout/review', [CheckoutController::class, 'review']);
Router::post('/checkout', [CheckoutController::class, 'checkout']);
Router::get('/checkout/result/:status', [CheckoutController::class, 'result']);

Router::get('/payment/pay', [PaymentController::class, 'pay']);
Router::get('/payment/callback', [PaymentController::class, 'callback']);

Abort::notFound();