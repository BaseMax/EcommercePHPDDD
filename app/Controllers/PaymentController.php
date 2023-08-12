<?php

namespace App\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Response\Response;
use App\Router\Redirect;

class PaymentController extends Controller
{
    public function pay($orderId){
        $order = (new Order())->where('id', $orderId)->get();
        if(count($order) == 0){
            return $this->cleanUpOnError(null, null);
        }
        $order = $order[0];
        $params = array(
            'order_id' => $order->id,
            'amount' => $order->total_price,
            'callback' => $_ENV['APP_URL'].'/payment/callback',
          );
          
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, 'https://api.idpay.ir/v1.1/payment');
          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'X-API-KEY: '.$_ENV['ID_PAY_API_KEY'],
            'X-SANDBOX: 1'
          ));
          
          $result = curl_exec($ch);

          if (!curl_errno($ch)) {
            switch ($httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
              case 201:
                break;
              default:
                return $this->cleanUpOnError(null, $order);
            }
          } else {
            return $this->cleanUpOnError(null, $order);
          }
          curl_close($ch);
          
          $resultArray = (array) json_decode($result);
          (new Payment())->create([
            'order_id' => $orderId,
            'idpay_id' => $resultArray['id'],
            'link' => $resultArray['link'],
            'amount' => $order->total_price,
            'status' => 0,
            'track_id' => 0,
          ]);
          Redirect::to($resultArray['link']);
    }

    public function callback(){
        $order = (new Order())->where('id', intval($_POST['order_id']))->get();
        if(count($order) == 0){
            return $this->cleanUpOnError(null, $order);
        }
        $order = $order[0];

        $payment = (new Payment())->where('idpay_id', $_POST['id'])->get();
        if(count($payment) == 0){
            return $this->cleanUpOnError($payment, $order);
        }
        $payment = $payment[0];

        $payment->track_id = intval($_POST['track_id']);
        if(intval($_POST['status']) != 10){
            return $this->cleanUpOnError($payment, $order);
        }

        $params = array(
            'id' => $_POST['id'],
            'order_id' => $_POST['order_id'],
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.idpay.ir/v1.1/payment/verify');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'X-API-KEY: '.$_ENV['ID_PAY_API_KEY'],
            'X-SANDBOX: 1',
        ));
          
        $result = curl_exec($ch);
        if (!curl_errno($ch)) {
            switch ($httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                case 200:
                    break;
                default:
                    return $this->cleanUpOnError($payment, $order);
        }
        } else {
            return $this->cleanUpOnError($payment, $order);
        }
        curl_close($ch);
        $resultArray = (array) json_decode($result);

        if(intval($resultArray['track_id']) == $payment->track_id && $resultArray['id'] == $payment->idpay_id){
            $payment->status = $resultArray['status'];
            $payment->update();
            if($resultArray['status'] == 100){
                $order->status = "done";
                $order->update();
                return $this->success();
            }
        } else {
            return $this->cleanUpOnError($payment, $order);
        }
    }

    private function cleanUpOnError($payment, $order){
        if($payment != null){
            $payment->delete();
        }
        if($order != null){
            $order->delete();
        }
        Redirect::to('/checkout/result/canceled');
    }

    private function success(){
        $_SESSION['cart'] = [];
        Redirect::to('/checkout/result/ok');
    }
}