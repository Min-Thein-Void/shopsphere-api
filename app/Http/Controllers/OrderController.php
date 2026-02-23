<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        $orders = Order::with(['user', 'items.product'])->get();

        return response()->json(['message' => 'Order list retrieved successfully', 'data' => $orders], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email',
            'shipping_address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            $userId = Auth::id();
            $order = $this->orderService->createOrder($validated, $userId);

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'message' => 'Order placed successfully',
            ], 201);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function myOrders()
    {
        $orders = $this->orderService->getUserOrders(Auth::id());

        return response()->json($orders);
    }
}
