<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

// Trang chu
Route::get('/', [HomeController::class, 'index'])->name('trang-chu');

// Dang nhap & Dang ky (danh cho khach)
Route::get('dang-nhap', [AuthController::class, 'formLogin'])->name('dang-nhap');
Route::post('dang-nhap', [AuthController::class, 'handleLogin']);
Route::get('dang-ky', [AuthController::class, 'formRegister'])->name('dang-ky');
Route::post('dang-ky', [AuthController::class, 'handleRegister']);

// Quen mat khau
Route::get('quen-mat-khau', [AuthController::class, 'showForgotPasswordForm'])->name('hien-thi-form-quen-mat-khau');
Route::post('quen-mat-khau', [AuthController::class, 'sendResetLink'])->name('gui-link-dat-lai');
Route::get('dat-lai-mat-khau/{token}', [AuthController::class, 'showResetForm'])->name('hien-thi-form-dat-lai');
Route::post('dat-lai-mat-khau', [AuthController::class, 'resetPassword'])->name('dat-lai-mat-khau');

// Dang xuat
Route::post('dang-xuat', [AuthController::class, 'logout'])->name('dang-xuat')->middleware('auth');

// Trang quan ly cho ca User & Admin (yeu cau dang nhap)
Route::middleware('auth')->group(function () {
    Route::get('bang-dieu-khien', [AuthController::class, 'dashboard'])->name('bang-dieu-khien');
});

// Quan tri Admin (chi danh cho Admin)
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('bang-dieu-khien', [AdminController::class, 'dashboard'])->name('admin'); 
    Route::resource('nguoi-dung', UserController::class)->except(['create', 'show']);
    Route::resource('san-pham', ProductController::class)->except(['create', 'show']);
    Route::resource('danh-muc', CategoryController::class)->except(['create', 'show']);
    Route::resource('thuong-hieu', BrandController::class)->except(['create', 'show']);
    Route::resource('don-hang', OrderController::class)->except(['create']);
    Route::resource('khuyen-mai', DiscountController::class)->except(['create', 'show']);
    Route::resource('danh-gia', ReviewController::class)->except(['create', 'show']);
});