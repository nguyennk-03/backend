<?php

use App\Http\Controllers\Admin\BaiVietController;
use App\Http\Controllers\Admin\KichThuocController;
use App\Http\Controllers\Admin\MauSacController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\NguoiDungController;
use App\Http\Controllers\Admin\SanPhamController;
use App\Http\Controllers\Admin\DanhMucController;
use App\Http\Controllers\Admin\ThuongHieuController;
use App\Http\Controllers\Admin\DonHangController;
use App\Http\Controllers\Admin\GiamGiaController;
use App\Http\Controllers\Admin\DanhGiaController;
use App\Http\Controllers\AdminController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Trang chu
Route::get('/', [HomeController::class, 'index'])->name('trang-chu');

Route::get('dang-nhap', [AuthController::class, 'formLogin'])->name('dang-nhap');
Route::post('dang-nhap', [AuthController::class, 'handleLogin']);
Route::get('dang-ky', [AuthController::class, 'formRegister'])->name('dang-ky');
Route::post('dang-ky', [AuthController::class, 'handleRegister']);
Route::get('user/bang-dieu-khien', [AuthController::class, 'index'])->name('user.dashboard');

Route::get('google/redirect', [AuthController::class, 'redirectToGoogleWeb']);
Route::get('google/callback', [AuthController::class, 'handleGoogleCallbackWeb']);

Route::get('quen-mat-khau', [AuthController::class, 'showForgotPasswordForm'])->name('quen-mat-khau');
Route::post('quen-mat-khau', [AuthController::class, 'sendResetLink']);

Route::get('/dat-lai-mat-khau/{token}', [AuthController::class, 'showResetForm'])->name('dat-lai-mat-khau');
Route::post('dat-lai-mat-khau', [AuthController::class, 'resetPassword']);

Route::post('dang-xuat', [AuthController::class, 'logoutWeb'])->name('dang-xuat');

// Trang quan ly cho ca User & Admin (yeu cau dang nhap)
Route::middleware(['auth', 'user'])->prefix('user')->group(function () {
    Route::get('bang-dieu-khien', [AuthController::class, 'index'])->name('bang-dieu-khien');
});

// Quan tri Admin (chi danh cho Admin)
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('bang-dieu-khien', [AdminController::class, 'index'])->name('admin');
    Route::resource('nguoi-dung', NguoiDungController::class);
    Route::resource('san-pham', SanPhamController::class);
    Route::resource('mau-sac', MauSacController::class);
    Route::resource('kich-thuoc', KichThuocController::class);
    Route::resource('danh-muc', DanhMucController::class);
    Route::resource('thuong-hieu', ThuongHieuController::class);
    Route::resource('don-hang', DonHangController::class);
    Route::resource('giam-gia', GiamGiaController::class);
    Route::resource('danh-gia', DanhGiaController::class);
    Route::resource('bai-viet', BaiVietController::class);
});