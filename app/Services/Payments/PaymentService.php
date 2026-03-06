<?php

namespace App\Services\Payments;

use App\Models\Order;

class PaymentService {
    protected $payment;

    public function __construct(PaymentInterface $payment)
    {
        $this->payment = $payment;
    }

    public function process(Order $order){
        //FakePayment class htal ka pay() ko hlan call htar tar , new PaymentService(new FakePayment)
      return $this->payment->pay($order);
    }
}
