<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
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
        $orders = Order::with(['user', 'items.product'])->orderBy('created_at', 'desc')->get();

        return response()->json(['message' => 'Order list retrieved successfully', 'data' => $orders], 200);
    }

    public function store(StoreOrderRequest $request)
    {
        $validated = $request->validated();

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

    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,shipped,delivered,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return response()->json([
            'message' => 'Order status updated',
            'data' => $order,
        ]);
    }
}
