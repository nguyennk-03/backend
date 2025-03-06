<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\{
    UserController,
    ProductController,
    CategoryController,
    BrandController,
    OrderController,
    DiscountController,
    ReviewController,
    AdminController
};
use Illuminate\Support\Facades\Route;

// Trang chủ
Route::get("/", [HomeController::class, "index"])->name("home");

// Authentication
Route::get("login", [AuthController::class, "formLogin"])->name("login");
Route::post("login", [AuthController::class, "handleLogin"]);
Route::get("register", [AuthController::class, "formRegister"])->name("register");
Route::post('register', [AuthController::class, 'handleRegister']);
Route::post("logout", [AuthController::class, "logout"])->name("logout");

Route::middleware('auth')->group(function () {
    Route::get("dashboard", [AuthController::class, "dashboard"])->name("dashboard");
    Route::get("admin", [AdminController::class, "dashboard"])->name("admin");
});

Route::prefix("admin")->middleware("auth")->group(function () {
    Route::resource("users", UserController::class)->except(["create", "show"]);
    Route::resource("products", ProductController::class)->except(["create", "show"]);
    Route::resource("categories", CategoryController::class)->except(["create", "show"]);
    Route::resource("brands", BrandController::class)->except(["create", "show"]);
    Route::resource("orders", OrderController::class)->except(["create"]);
    Route::resource("discounts", DiscountController::class)->except(["create", "show"]);
    Route::resource("reviews", ReviewController::class)->except(["create", "show"]);
});
