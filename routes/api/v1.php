<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ColorController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\ProductDiscountController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderItemController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\SizeController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\ProductVariantController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\WishlistController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource('products', ProductController::class)->only(['index', 'show']);
    Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
    Route::apiResource('brands', BrandController::class)->only(['index', 'show']);
    Route::apiResource('reviews', ReviewController::class)->only(['index', 'show']);
    // Route::apiResource('payments', PaymentController::class)->only(['index', 'show']);

    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    // Routes dành cho user
    Route::middleware(['auth:sanctum', 'user'])->group(function () {
        Route::apiResource('users', UsersController::class)->only(['index', 'show']);
        Route::apiResource('carts', CartController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::apiResource('orders', OrderController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::apiResource('order-items', OrderItemController::class)->only(['index', 'show']);
        Route::apiResource('payments', PaymentController::class)->only(['index', 'show']);
        Route::apiResource('wishlists', WishlistController::class)->only(['index', 'store', 'show', 'destroy']);
    });

    // Routes dành cho admin
    Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
        Route::apiResources([
            'products' => ProductController::class,
            'categories' => CategoryController::class,
            'brands' => BrandController::class,
            'carts' => CartController::class,
            'orders' => OrderController::class,
            'order-items' => OrderItemController::class,
            'payments' => PaymentController::class,
            'variants' => ProductVariantController::class,
            'colors' => ColorController::class,
            'sizes' => SizeController::class,
            'images' => ImageController::class,
            'reviews' => ReviewController::class,
            'discounts' => DiscountController::class,
            'product-discounts' => ProductDiscountController::class,
            'users' => UsersController::class,
            'wishlists'=> WishlistController::class,
        ]);
    });

    Route::middleware(['auth:sanctum'])->post('/logout', [AuthController::class, 'logout']);
});
