<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Payments\FakePayment;
use App\Services\Payments\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        $order = Order::with('items.product')->findOrFail($request->order_id);

        $paymentService = new PaymentService(new FakePayment);

        return response()->json([
            $paymentService->process($order),
        ]);
    }
}
