<?php

namespace App\Services\Payments;

use App\Models\Order;

interface PaymentInterface{
    public function pay(Order $order) :array;
}

//set a rule for every payment should've pay() method.
