<?php

namespace App\Presentation\Http\Controllers;

use App\Domain\Order\OrderRepository;
use App\Presentation\Http\Router\Redirect;
use App\Domain\Payment\PaymentRepository;
use App\Domain\Payment\Method\Repository;
use Exception;

class PaymentController extends Controller
{
    public function pay($orderId){
        $order = (new OrderRepository($this->mysqlDatabase))->where('id', $orderId)->first();
        if($order == null){
            return $this->cleanUpOnError(null, null);
        }

        try{
            $paymentRepository = new Repository($this->idpayPaymentMethod, $order);
            $result = $paymentRepository->paymentRequest();
        } catch(Exception $e){
            return $this->cleanUpOnError(null, $order);
        }

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

        try{
            $paymentRepository = new Repository($this->idpayPaymentMethod, $order);
            $result = $paymentRepository->paymentVerifyRequest($_POST['id'], $_POST['order_id']);
        } catch(Exception $e){
            return $this->cleanUpOnError($payment, $order);
        }

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
            (new PaymentRepository($this->mysqlDatabase))->delete($payment->id);
        }
        if($order != null){
            (new OrderRepository($this->mysqlDatabase))->delete($payment->id);
        }
        Redirect::to('/checkout/result/canceled');
    }

    private function success(){
        $_SESSION['cart'] = [];
        Redirect::to('/checkout/result/ok');
    }
}