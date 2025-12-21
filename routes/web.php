<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RecapController;
use App\Http\Controllers\Admin\SlotController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\HomeController;
use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return view("welcome");
});

// Dashboard User
Route::get("/dashboard", [HomeController::class, "index"])
    ->middleware(["auth", "verified"])
    ->name("dashboard");

// --- PERBAIKAN DI SINI ---
// Menggunakan Controller, bukan function(), agar data produk terkirim
Route::get("/custom", [HomeController::class, "customPage"])
    ->middleware(["auth", "verified"])
    ->name("custom");

// Rute untuk POST form Custom Cake (yang sebelumnya hilang)
Route::post("/custom/add", [CartController::class, "addCustomToCart"])
    ->middleware(["auth", "verified"])
    ->name("cart.custom.add");
// -------------------------

Route::get("/Contact", function () {
    return view("user.kontak");
})->middleware(["auth", "verified"])->name("Contact");

Route::get("/faq", function () {
    return view("user.faq");
})->middleware(["auth", "verified"])->name("faq");

// Order Routes
Route::get("/order/{id}", [App\Http\Controllers\User\OrderController::class, "show"])->name("order.show");
Route::post("/list-pesanan", [OrderController::class, "store"])->name("orders.store");

Route::middleware(["auth"])->group(function () {
    Route::get("/list-pesanan", [OrderController::class, "userOrders"])->name("user.list-pesanan");
    Route::post("/list-pesanan/cancel/{id}", [OrderController::class, "cancelOrder"])->name("user.list-pesanan.cancel");
    Route::get("/pesanan/{id}", [App\Http\Controllers\User\OrderController::class, "pesanan"])->name("user.pesanan");
});

// Authenticated User Routes
Route::middleware("auth")->group(function () {
    Route::get("/profile", [ProfileController::class, "edit"])->name("profile.edit");
    Route::patch("/profile", [ProfileController::class, "update"])->name("profile.update");
    Route::delete("/profile", [ProfileController::class, "destroy"])->name("profile.destroy");
    
    // Cart Routes
    Route::get("/cart", [CartController::class, "index"])->name("cart.index");
    Route::post("/cart/add/{id}", [CartController::class, "addToCart"])->name("cart.add");
    Route::patch("/cart/update/{id}", [CartController::class, "update"])->name("cart.update");
    Route::delete("/cart/remove/{id}", [CartController::class, "destroy"])->name("cart.destroy");
    
    // Checkout Routes
    Route::get("/checkout", [CartController::class, "checkout"])->name("cart.checkout");
    Route::post("/checkout", [CartController::class, "processCheckout"])->name("cart.checkout.process");
    
    // Review
    Route::post('/order/{order}/review', [App\Http\Controllers\Admin\OrderController::class, 'submitReview'])->name('orders.review');
});

// Admin Routes
Route::middleware(["auth", "admin"])
    ->prefix("admin")
    ->name("admin.")
    ->group(function () {
        Route::get("/dashboard", [DashboardController::class, "index"])->name("dashboard");
        Route::get("/orders", [OrderController::class, "index"])->name("orders.index");
        Route::post("/orders/{id}/update", [OrderController::class, "updateStatus"])->name("orders.update");
        Route::get("/notifications", [NotificationController::class, "index"])->name("notifications");
        Route::resource("/products", ProductController::class);
        Route::post("/orders/{order}/approve", [OrderController::class, "approve"])->name("orders.approve");
        Route::get("/slots/{date?}", [SlotController::class, "index"])->name("slots.index");
        Route::post("/slots/update", [SlotController::class, "update"])->name("slots.update");
        Route::get("/recap", [RecapController::class, "index"])->name("recap.index");
    });

require __DIR__ . "/auth.php";