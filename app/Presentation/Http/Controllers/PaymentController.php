<?php

namespace App\Presentation\Http\Controllers;

use App\Domain\Order\OrderRepository;
use App\Presentation\Http\Router\Redirect;
use App\Domain\Payment\PaymentRepository;

class PaymentController extends Controller
{
    public function pay($orderId){
        $order = (new OrderRepository($this->mysqlDatabase))->where('id', $orderId)->first();
        if($order == null){
            return $this->cleanUpOnError(null, null);
        }
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
          (new PaymentRepository($this->mysqlDatabase))->create([
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
        $order = (new OrderRepository($this->mysqlDatabase))->where('id', intval($_POST['order_id']))->first();
        if($order == null){
            return $this->cleanUpOnError(null, $order);
        }
        
        $payment = (new PaymentRepository($this->mysqlDatabase))->where('idpay_id', $_POST['id'])->first();
        if($payment == null){
            return $this->cleanUpOnError($payment, $order);
        }
        
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
            (new PaymentRepository($this->mysqlDatabase))->update($payment);
            if($resultArray['status'] == 100){
                $order->status = "done";
                (new OrderRepository($this->mysqlDatabase))->update($order);
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