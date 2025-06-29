<?php

use Illuminate\Support\Facades\Route;
// Pastikan untuk mengimpor semua controller yang akan digunakan
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;


Route::get('orders', [OrderController::class, 'index'])->name('admin.orders.index');

// Rute BARU untuk mengubah status pesanan
Route::patch('orders/{transaction}/complete', [OrderController::class, 'complete'])->name('admin.orders.complete');

Route::get('/payment/show', [TransactionController::class, 'showPaymentPage'])->name('payment.show');
Route::post('/payment/confirm', [TransactionController::class, 'confirmPayment'])->name('payment.confirm');

// Halaman utama yang menampilkan menu
Route::get('/', [MenuController::class, 'index'])->name('menu');

// Alur checkout yang ditangani oleh TransactionController
Route::post('/checkout', [TransactionController::class, 'checkout'])->name('checkout.page');
Route::get('/payment', [TransactionController::class, 'paymentPage'])->name('payment.page');
// PERBAIKAN PENTING: Form checkout harus mengarah ke rute ini
Route::post('/payment', [TransactionController::class, 'store'])->name('checkout.store'); 
Route::get('/order/success/{transaction}', [TransactionController::class, 'success'])->name('order.success');

// Grup untuk rute yang bisa diakses sebelum login admin (Guest)
Route::prefix('admin')->middleware('guest:admin')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('admin.login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    
});

// Grup untuk semua rute admin yang hanya bisa diakses setelah login
Route::prefix('admin')->middleware('auth:admin')->name('admin.')->group(function () {
    Route::patch('orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // CRUD Produk
    Route::resource('products', ProductController::class);
    
    // Manajemen Order
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::patch('orders/{transaction}/complete', [OrderController::class, 'complete'])->name('orders.complete');

    // Manajemen Profil Admin
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Route ini untuk menampilkan halaman sukses setelah pesanan disimpan
Route::get('/order/success/{order}', [OrderController::class, 'success'])->name('order.success');

// == CONTOH ROUTE UNTUK ADMIN ==
Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::patch('/orders/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
});

    Route::get('/order/success/{order}', [OrderController::class, 'success'])->name('order.success');

    Route::prefix('admin')->middleware('auth:admin')->group(function () {
    // Rute Dashboard BARU
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rute Orders yang sudah ada
    Route::get('/orders', [OrderController::class, 'index'])->name('dashboard.orders');
    // Rute BARU untuk menandai pesanan selesai
    Route::patch('/orders/{transaction}/complete', [OrderController::class, 'complete'])->name('dashboard.orders.complete');
});

// Rute untuk halaman pemesanan pelanggan
Route::get('/', [MenuController::class, 'index'])->name('menu');

Route::prefix('admin')->middleware('auth:admin')->group(function () {
    // Rute untuk Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
});

// Grup untuk Rute Tamu (Guest) Admin -> bisa diakses sebelum login
Route::prefix('admin')->middleware('guest:admin')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('admin.login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    // Anda bisa menambahkan rute register admin di sini jika perlu
});

// Grup untuk Rute Admin yang Terproteksi -> hanya bisa diakses setelah login
Route::prefix('admin')->middleware('auth:admin')->group(function () {
    // Rute Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); // Pastikan Anda punya DashboardController

    // Rute Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('admin.logout');

    // Rute untuk Resource Product (CRUD)
    Route::resource('products', ProductController::class)->names([
        'index' => 'dashboard.products',
        'create' => 'dashboard.products.create',
        'store' => 'dashboard.products.store',
        'edit' => 'dashboard.products.edit',
        'update' => 'dashboard.products.update',
        'destroy' => 'dashboard.products.destroy',
    ]);
    
    // Rute untuk Resource Order
    Route::get('orders', [OrderController::class, 'index'])->name('dashboard.orders');

});