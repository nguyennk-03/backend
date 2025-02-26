<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin');

Route::prefix('admin')->group(function () {
    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::post('/users/add', [UserController::class, 'store'])->name('userAdd');
    Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('userEdit');
    Route::post('/users/update/{id}', [UserController::class, 'update'])->name('userUpdate');
    Route::get('/users/delete/{id}', [UserController::class, 'destroy'])->name('userDelete');

    // Products
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::post('/products/add', [ProductController::class, 'store'])->name('productAdd');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('productView');
    Route::get('/products/edit/{id}', [ProductController::class, 'edit'])->name('productEdit');
    Route::post('/products/update/{id}', [ProductController::class, 'update'])->name('productUpdate');
    Route::get('/products/delete/{id}', [ProductController::class, 'destroy'])->name('productDelete');

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::post('/categories/add', [CategoryController::class, 'store'])->name('categoryAdd');
    Route::get('/categories/edit/{id}', [CategoryController::class, 'edit'])->name('categoryEdit');
    Route::post('/categories/update/{id}', [CategoryController::class, 'update'])->name('categoryUpdate');
    Route::get('/categories/delete/{id}', [CategoryController::class, 'destroy'])->name('categoryDelete');

    // Brands
    Route::get('/brands', [BrandController::class, 'index'])->name('brands');
    Route::post('/brands/add', [BrandController::class, 'store'])->name('brandAdd');
    Route::get('/brands/edit/{id}', [BrandController::class, 'edit'])->name('brandEdit');
    Route::post('/brands/update/{id}', [BrandController::class, 'update'])->name('brandUpdate');
    Route::get('/brands/delete/{id}', [BrandController::class, 'destroy'])->name('brandDelete');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::post('/orders/add', [OrderController::class, 'store'])->name('orderAdd');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orderView');
    Route::get('/orders/edit/{id}', [OrderController::class, 'edit'])->name('orderEdit');
    Route::post('/orders/update/{id}', [OrderController::class, 'update'])->name('orderUpdate');
    Route::get('/orders/delete/{id}', [OrderController::class, 'destroy'])->name('orderDelete');

    // Discounts
    Route::get('/discounts', [DiscountController::class, 'index'])->name('discounts');
    Route::post('/discounts/add', [DiscountController::class, 'store'])->name('discountAdd');
    Route::get('/discounts/edit/{id}', [DiscountController::class, 'edit'])->name('discountEdit');
    Route::post('/discounts/update/{id}', [DiscountController::class, 'update'])->name('discountUpdate');
    Route::get('/discounts/delete/{id}', [DiscountController::class, 'destroy'])->name('discountDelete');

    // Reviews
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews'); 
    Route::post('/reviews/add', [ReviewController::class, 'store'])->name('reviewAdd'); 
    Route::get('/reviews/edit/{id}', [ReviewController::class, 'edit'])->name('reviewEdit'); 
    Route::put('/reviews/update/{id}', [ReviewController::class, 'update'])->name('reviewUpdate'); 
    Route::delete('/reviews/delete/{id}', [ReviewController::class, 'destroy'])->name('reviewDelete'); 

});
