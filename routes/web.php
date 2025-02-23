<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');

    // Quản lý sản phẩm (products)
    Route::get('/products', [AdminController::class, 'products'])->name('products.index');
    Route::get('/products/add', [AdminController::class, 'productadd'])->name('products.add');
    Route::get('/products/edit/{id}', [AdminController::class, 'productedit'])->name('products.edit');
    Route::post('/products/update/{id}', [AdminController::class, 'productupdate'])->name('products.update');
    Route::post('/products/delete/{id}', [AdminController::class, 'productdelete'])->name('products.delete');

    // Quản lý danh mục (categories)
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::post('/categories/add', [AdminController::class, 'categoryadd'])->name('categoryadd');
    Route::get('/categories/{id}/edit', [AdminController::class, 'categoryedit'])->name('categoryedit');
    Route::put('/categories/{id}', [AdminController::class, 'categoryupdate'])->name('categoryupdate');
    Route::delete('/categories/{id}', [AdminController::class, 'categorydelete'])->name('categorydelete');

    // Quản lý thương hiệu (brands)
    Route::get('/brands', [AdminController::class, 'brands'])->name('brands');
    Route::post('/brands/add', [AdminController::class, 'brandadd'])->name('brandadd');
    Route::get('/brands/{id}/edit', [AdminController::class, 'brandedit'])->name('brandedit');
    Route::put('/brands/{id}', [AdminController::class, 'brandupdate'])->name('brandupdate');
    Route::delete('/brands/{id}', [AdminController::class, 'branddelete'])->name('branddelete');

    // Quản lý người dùng (users)
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/add', [AdminController::class, 'useradd'])->name('useradd');
    Route::get('/users/{id}/edit', [AdminController::class, 'useredit'])->name('useredit');
    Route::put('/users/{id}', [AdminController::class, 'userupdate'])->name('userupdate');
    Route::delete('/users/{id}', [AdminController::class, 'userdelete'])->name('userdelete');

    // Quản lý mã giảm giá (discounts)
    Route::get('/discounts', [AdminController::class, 'discounts'])->name('discounts');
    Route::post('/discounts/add', [AdminController::class, 'discountadd'])->name('discountadd');
    Route::get('/discounts/{id}/edit', [AdminController::class, 'discountedit'])->name('discountedit');
    Route::put('/discounts/{id}', [AdminController::class, 'discountupdate'])->name('discountupdate');
    Route::delete('/discounts/{id}', [AdminController::class, 'discountdelete'])->name('discountdelete');

    // Quản lý đơn hàng (orders)
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [AdminController::class, 'orderview'])->name('orderview');
    Route::get('/orders/{id}/edit', [AdminController::class, 'orderedit'])->name('orderedit');
    Route::put('/orders/{id}', [AdminController::class, 'orderupdate'])->name('orderupdate');
    Route::delete('/orders/{id}', [AdminController::class, 'orderdelete'])->name('orderdelete');

    // Quản lý bình luận (comments)
    Route::get('/comments', [AdminController::class, 'comments'])->name('comments');
    Route::get('/comments/{id}', [AdminController::class, 'commentview'])->name('commentview');
    Route::get('/comments/{id}/edit', [AdminController::class, 'commentedit'])->name('commentedit');
    Route::put('/comments/{id}', [AdminController::class, 'commentupdate'])->name('commentupdate');
    Route::delete('/comments/{id}', [AdminController::class, 'commentdelete'])->name('commentdelete');

    // Quản lý đánh giá (ratings)
    Route::get('/ratings', [AdminController::class, 'ratings'])->name('ratings');
    Route::get('/ratings/{id}', [AdminController::class, 'ratingview'])->name('ratingview');
    Route::get('/ratings/{id}/edit', [AdminController::class, 'ratingedit'])->name('ratingedit');
    Route::put('/ratings/{id}', [AdminController::class, 'ratingupdate'])->name('ratingupdate');
    Route::delete('/ratings/{id}', [AdminController::class, 'ratingdelete'])->name('ratingdelete');
});
