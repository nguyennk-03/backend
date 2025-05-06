<?php

use App\Http\Controllers\Admin\BaiVietController;
use App\Http\Controllers\Admin\BinhLuanController;
use App\Http\Controllers\AuthWebController;
use App\Http\Controllers\Admin\NguoiDungController;
use App\Http\Controllers\Admin\SanPhamController;
use App\Http\Controllers\Admin\DanhMucController;
use App\Http\Controllers\Admin\ThuongHieuController;
use App\Http\Controllers\Admin\DonHangController;
use App\Http\Controllers\Admin\GiamGiaController;
use App\Http\Controllers\Admin\DanhGiaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('trang-chu', [HomeController::class, 'index'])->name('trang-chu');
Route::get('dang-nhap', [AuthWebController::class, 'formLogin'])->name('dang-nhap');
Route::post('dang-nhap', [AuthWebController::class, 'handleLogin']);
Route::get('dang-ky', [AuthWebController::class, 'formRegister'])->name('dang-ky');
Route::post('dang-ky', [AuthWebController::class, 'handleRegister']);
Route::get('user/bang-dieu-khien', [AuthWebController::class, 'index'])->name('user.dashboard');

Route::get('quen-mat-khau', [AuthWebController::class, 'showForgotPasswordForm'])->name('quen-mat-khau');
Route::post('quen-mat-khau', [AuthWebController::class, 'sendResetLink']);

Route::get('/dat-lai-mat-khau/{token}', [AuthWebController::class, 'showResetForm'])->name('dat-lai-mat-khau');
Route::post('dat-lai-mat-khau', [AuthWebController::class, 'resetPassword']);

Route::post('dang-xuat', [AuthWebController::class, 'logout'])->name('dang-xuat');

// Trang quan ly cho ca User & Admin (yeu cau dang nhap)
Route::middleware(['auth', 'user'])->prefix('user')->group(function () {
    Route::get('bang-dieu-khien', [AuthWebController::class, 'index'])->name('bang-dieu-khien');
});

// Quan tri Admin (chi danh cho Admin)
Route::prefix('admin')->middleware(['auth', 'user'])->group(function () {
    Route::get('bang-dieu-khien', [AdminController::class, 'index'])->name('admin');
    Route::resource('nguoi-dung', NguoiDungController::class);
    Route::resource('san-pham', SanPhamController::class);
    Route::resource('danh-muc', DanhMucController::class);
    Route::resource('thuong-hieu', ThuongHieuController::class);
    Route::resource('don-hang', DonHangController::class);
    Route::resource('giam-gia', GiamGiaController::class);
    Route::resource('danh-gia', DanhGiaController::class);
    Route::resource('binh-luan', BinhLuanController::class);
    Route::resource('bai-viet', BaiVietController::class);
});
