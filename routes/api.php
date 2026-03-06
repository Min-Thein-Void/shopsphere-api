<?php

use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\RatingController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/user/avatar/{id}', [ProductsController::class, 'photoUpload']);
        Route::post('/order', [OrderController::class, 'store']);
        Route::get('/orders/my', [OrderController::class, 'myOrders']);
        Route::post('/rate', [RatingController::class, 'rate']);
        Route::get('/rating-average/{productId}', [RatingController::class, 'average']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
        Route::post('/payment/fake',[PaymentController::class, 'pay']);
    });
});

Route::middleware(['auth:sanctum', 'isAdmin'])->group(function () {
    Route::post('/create/product', [AdminProductController::class, 'StoreProduct']);
    Route::get('/delete/{product}', [AdminProductController::class, 'destroy']);
    Route::get('/edit/{product}', [AdminProductController::class, 'update']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::patch('/products/{id}/discount', [AdminProductController::class, 'setDiscount']);
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateOrderStatus']);
});

Route::get('/products', [ProductsController::class, 'AllProducts']);

Route::get('/random-products',[ProductsController::class, 'randomProducts']);

Route::get('/product/{product}', [ProductsController::class, 'SingleProduct']);

Route::get('/categories', [ProductsController::class, 'GetCategories']);

Route::post('/products/reduce-stock', [ProductsController::class, 'reduceStock']);

Route::post('/products/restore-stock', [ProductsController::class, 'restoreStock']);

Route::post('/products/search', [ProductsController::class, 'search']);

Route::post('/category/search', [ProductsController::class, 'searchByCategory']);


