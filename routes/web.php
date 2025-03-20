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

Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication routes (dành cho guest)
Route::get('login', [AuthController::class, 'formLogin'])->name('login');
Route::post('login', [AuthController::class, 'handleLogin'])->name('login');
Route::get('register', [AuthController::class, 'formRegister'])->name('register');
Route::post('register', [AuthController::class, 'handleRegister'])->name('register');

// Password Reset
Route::get('forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Logout
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Route yêu cầu đăng nhập (cho cả user và admin)
Route::middleware('auth')->group(function () {
    Route::get('dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
});

// Admin routes (yêu cầu auth và vai trò admin)
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin');
    Route::resource('users', UserController::class)->except(['create', 'show']);
    Route::resource('products', ProductController::class)->except(['create', 'show']);
    Route::resource('categories', CategoryController::class)->except(['create', 'show']);
    Route::resource('brands', BrandController::class)->except(['create', 'show']);
    Route::resource('orders', OrderController::class)->except(['create']);
    Route::resource('discounts', DiscountController::class)->except(['create', 'show']);
    Route::resource('reviews', ReviewController::class)->except(['create', 'show']);
});