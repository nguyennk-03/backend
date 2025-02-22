<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');

    // Quản lý sản phẩm (Products)
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::post('/products/add', [AdminController::class, 'productAdd'])->name('productAdd');
    Route::get('/products/{id}/edit', [AdminController::class, 'productEdit'])->name('productEdit');
    Route::put('/products/{id}', [AdminController::class, 'productUpdate'])->name('productUpdate');
    Route::delete('/products/{id}', [AdminController::class, 'productDelete'])->name('productDelete');

    // Quản lý danh mục (Categories)
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::post('/categories/add', [AdminController::class, 'categoryAdd'])->name('categoryAdd');
    Route::get('/categories/{id}/edit', [AdminController::class, 'categoryEdit'])->name('categoryEdit');
    Route::put('/categories/{id}', [AdminController::class, 'categoryUpdate'])->name('categoryUpdate');
    Route::delete('/categories/{id}', [AdminController::class, 'categoryDelete'])->name('categoryDelete');

    // Quản lý thương hiệu (Brands)
    Route::get('/brands', [AdminController::class, 'brands'])->name('brands');
    Route::post('/brands/add', [AdminController::class, 'brandAdd'])->name('brandAdd');
    Route::get('/brands/{id}/edit', [AdminController::class, 'brandEdit'])->name('brandEdit');
    Route::put('/brands/{id}', [AdminController::class, 'brandUpdate'])->name('brandUpdate');
    Route::delete('/brands/{id}', [AdminController::class, 'brandDelete'])->name('brandDelete');

    // Quản lý người dùng (Users)
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/add', [AdminController::class, 'userAdd'])->name('userAdd');
    Route::get('/users/{id}/edit', [AdminController::class, 'userEdit'])->name('userEdit');
    Route::put('/users/{id}', [AdminController::class, 'userUpdate'])->name('userUpdate');
    Route::delete('/users/{id}', [AdminController::class, 'userDelete'])->name('userDelete');

    // Quản lý mã giảm giá (Discounts)
    Route::get('/discounts', [AdminController::class, 'discounts'])->name('discounts');
    Route::post('/discounts/add', [AdminController::class, 'discountAdd'])->name('discountAdd');
    Route::get('/discounts/{id}/edit', [AdminController::class, 'discountEdit'])->name('discountEdit');
    Route::put('/discounts/{id}', [AdminController::class, 'discountUpdate'])->name('discountUpdate');
    Route::delete('/discounts/{id}', [AdminController::class, 'discountDelete'])->name('discountDelete');

    // Quản lý đơn hàng (Orders)
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [AdminController::class, 'orderView'])->name('orderView');
    Route::get('/orders/{id}/edit', [AdminController::class, 'orderEdit'])->name('orderEdit');
    Route::put('/orders/{id}', [AdminController::class, 'orderUpdate'])->name('orderUpdate');
    Route::delete('/orders/{id}', [AdminController::class, 'orderDelete'])->name('orderDelete');

    // Quản lý Bình luận & Đánh giá
    Route::prefix('reviews')->group(function () {
        // Bình luận
        Route::get('/comments', [AdminController::class, 'comments'])->name('comments');
        Route::delete('/comments/{id}', [AdminController::class, 'commentDelete'])->name('commentDelete');

        // Đánh giá
        Route::get('/ratings', [AdminController::class, 'ratings'])->name('ratings');
        Route::delete('/ratings/{id}', [AdminController::class, 'ratingDelete'])->name('ratingDelete');
    });
});
