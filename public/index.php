<?php

session_start();
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

use App\Presentation\Http\Controllers\AppController;
use App\Presentation\Http\Controllers\ProductController;
use App\Presentation\Http\Controllers\CartController;
use App\Presentation\Http\Controllers\CheckoutController;
use App\Presentation\Http\Controllers\PaymentController;
use Dotenv\Dotenv;
use App\Presentation\Http\Router\Router;
use App\Presentation\Http\Response\Abort;

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

Router::get('/payment/pay/:orderId', [PaymentController::class, 'pay']);
Router::post('/payment/callback', [PaymentController::class, 'callback']);

Abort::notFound();