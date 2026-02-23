<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected $orderRepo;

    protected $productRepo;

    public function __construct(
        OrderRepository $orderRepo,
        ProductRepository $productRepo
    ) {
        $this->orderRepo = $orderRepo;
        $this->productRepo = $productRepo;
    }

    public function createOrder(array $validated, int $userId)
    {
        return DB::transaction(function () use ($validated, $userId) {
            $order = $this->orderRepo->createOrder([
                'user_id' => $userId,
                'fullname' => $validated['fullname'],
                'email' => $validated['email'],
                'shipping_address' => $validated['shipping_address'],
                'phone' => $validated['phone'],
                'status' => 'pending',
                'total' => 0,
            ]);

            $total = 0;

            foreach ($validated['items'] as $item) {
                $product = $this->productRepo->findWithLock($item['product_id']);

                if (! $product) {
                    throw new \Exception('Product not found');
                }

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("{$product->name} stock မလုံလောက်ပါ");
                }

                $this->productRepo->reduceStock($product, $item['quantity']);

                $this->orderRepo->createOrderItem([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);

                $total += $product->price * $item['quantity'];
            }

            $this->orderRepo->updateOrderTotal($order, $total);

            return $order;
        });
    }

    public function getUserOrders(int $userId){
        return $this->orderRepo->getUserOrders($userId);
    }
}
