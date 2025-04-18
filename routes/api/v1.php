<?php

use App\Http\Controllers\AuthController;
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
use App\Http\Controllers\Api\NotifiController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource('products', ProductController::class)->only(['index', 'show']);
    Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
    Route::apiResource('brands', BrandController::class)->only(['index', 'show']);
    
    Route::apiResource('variants', ProductVariantController::class)->only(['index', 'show']);
    Route::apiResource('colors', ColorController::class)->only(['index', 'show']);
    Route::apiResource('sizes', SizeController::class)->only(['index', 'show']);
    Route::apiResource('images', ImageController::class)->only(['index', 'show']);
    Route::apiResource('news', NewsController::class)->only(['index', 'show']);


    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
    Route::post('password/email', [AuthController::class, 'sendResetLinkApi']);
    Route::post('password/reset', [AuthController::class, 'resetPasswordApi']);

    Route::middleware(['auth:sanctum', 'user'])->group(function () {
        Route::apiResource('users', UsersController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::apiResource('carts', CartController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::post('orders/payment', [OrderController::class, 'processPayment']);
        Route::apiResource('orders', OrderController::class)->only(['index', 'show']);
        Route::get('momo/success', [MoMoController::class, 'MoMoSuccess']); 
    Route::post('momo/notify', [MoMoController::class, 'MoMoNotify']); 
        Route::post('momo/cancel', [MoMoController::class, 'MoMoCancel']);
        Route::get('zalopay/success', [ZaloPayController::class, 'ZaloPaySuccess']);
        Route::post('zalopay/cancel', [ZaloPayController::class, 'ZaloPayCancel']);
        Route::get('vnpay/success', [VNPayController::class, 'VNPaySuccess']);
        Route::post('vnpay/cancel', [VNPayController::class, 'VNPayCancel']);
        Route::apiResource('order-items', OrderItemController::class)->only(['index', 'show']);
        Route::apiResource('wishlists', WishlistController::class)->only(['index', 'store', 'show', 'destroy']);
        Route::apiResource('discounts', DiscountController::class)->only(['index', 'show']);
        Route::apiResource('notifis', NotificationController::class);
        Route::apiResource('reviews', ReviewController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
        Route::apiResource('comments', CommentController::class);

        Route::post('logout', [AuthController::class, 'logout']);
    });
});
