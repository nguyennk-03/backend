<?php

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
use Illuminate\Support\Facades\Route;

// Nhóm API với tiền tố "api/v1/"
Route::prefix('v1')->group(function () {

    // Routes quản lý sản phẩm
    Route::apiResource('products', ProductController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('brands', BrandController::class);

    // Routes quản lý giỏ hàng, đơn hàng, thanh toán
    Route::apiResource('carts', CartController::class);
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('order-items', OrderItemController::class);
    Route::apiResource('payments', PaymentController::class);

    // Routes quản lý biến thể sản phẩm
    Route::apiResource('variants', ProductVariantController::class);
    Route::apiResource('colors', ColorController::class);
    Route::apiResource('sizes', SizeController::class);

    // Routes quản lý hình ảnh, đánh giá, giảm giá
    Route::apiResource('images', ImageController::class);
    Route::apiResource('reviews', ReviewController::class);
    Route::apiResource('discounts', DiscountController::class);
    Route::apiResource('product-discounts', ProductDiscountController::class);

    // Routes quản lý người dùng
    Route::apiResource('users', UsersController::class);
});