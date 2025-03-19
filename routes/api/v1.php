<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ColorController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderItemController;
use App\Http\Controllers\Api\SizeController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\ProductVariantController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\MomoController;
use App\Http\Controllers\Api\VNPayController;
use App\Http\Controllers\Api\ZaloPayController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProductDiscountController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource('products', ProductController::class)->only(['index', 'show']);
    Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
    Route::apiResource('brands', BrandController::class)->only(['index', 'show']);
    Route::apiResource('reviews', ReviewController::class)->only(['index', 'show']);
    Route::apiResource('variants', ProductVariantController::class)->only(['index', 'show']);
    Route::apiResource('colors', ColorController::class)->only(['index', 'show']);
    Route::apiResource('sizes', SizeController::class)->only(['index', 'show']);
    Route::apiResource('images', ImageController::class)->only(['index', 'show']);

    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware(['auth:sanctum', 'user'])->group(function () {
        Route::apiResource('users', UsersController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::apiResource('carts', CartController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::post('orders', [OrderController::class, 'processPayment']);
        Route::apiResource('order-items', OrderItemController::class)->only(['index', 'show']);
        Route::get('momo/success', [MoMoController::class, 'MoMoSuccess']);
        Route::get('momo/cancel', [MoMoController::class, 'MoMoCancel']);
        Route::get('zalopay/cancel', [ZaloPayController::class, 'ZaloPayCancel']);
        Route::get('zalopay/success', [ZaloPayController::class, 'ZaloPaySuccess']);
        Route::get('vnpay/success', [VNPayController::class, 'returnpayment']);
        Route::get('vnpay/cancel', [VNPayController::class, 'VNPayCancel']);
        Route::apiResource('wishlists', WishlistController::class)->only(['index', 'store', 'show', 'destroy']);
        Route::apiResource('discounts', DiscountController::class)->only(['index', 'show']);
        Route::apiResource('product-discounts', ProductDiscountController::class)->only(['index', 'show']);
        Route::apiResource('notifications', NotificationController::class);

        Route::post('logout', [AuthController::class, 'logout']);
    });
});
