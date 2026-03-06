<?php

namespace App\Services\Payments;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class FakePayment implements PaymentInterface
{
    public function pay(Order $order): array
    {
        $success = true; // always success for testing

        if (! $success) {
            $order->payment_status = 'failed';
            $order->save();
            return [
                'status' => 'failed',
            ];
        }

        try {

             $order->loadMissing('items.product');

            DB::transaction(function () use ($order) {
                foreach ($order->items as $item) {
                    $product = $item->product;

                    if ($product->stock < $item->quantity) {
                        throw new \Exception('Not enough stock for ' . $product->name);
                    }

                    $product->stock -= $item->quantity;
                    $product->save();
                }

                $order->payment_status = 'paid';
                $order->save();
            });

            return [
                'status' => 'paid',
            ];
        } catch (\Exception $e) {
            $order->payment_status = 'failed';
            $order->save();

            return [
                'status' => 'failed',
                'message' => $e->getMessage(),
            ];
        }
    }
}
