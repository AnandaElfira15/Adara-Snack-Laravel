<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;

// Halaman awal
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    if (auth()->user()->role === 'user') {
        return redirect()->route('products.index');
    }

    return redirect()->route('dashboard');
});

// Login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.store');
});

// Semua akun yang sudah login
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    // Profil: semua role boleh membuka profil
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    // Semua role boleh melihat daftar dan detail produk
    Route::get('/products', [ProductController::class, 'index'])
        ->name('products.index');

    Route::get('/products/{product}', [ProductController::class, 'show'])
        ->whereNumber('product')
        ->name('products.show');

    // Dashboard dan transaksi hanya untuk Admin dan Staff
    Route::middleware('role:admin,staff')->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Pembelian
        Route::get('/purchases', [PurchaseController::class, 'index'])
            ->name('purchases.index');

        Route::get('/purchases/create', [PurchaseController::class, 'create'])
            ->name('purchases.create');

        Route::post('/purchases', [PurchaseController::class, 'store'])
            ->name('purchases.store');

        Route::get('/purchases/{purchase}', [PurchaseController::class, 'show'])
            ->name('purchases.show');

        // Penjualan
        Route::get('/sales', [SaleController::class, 'index'])
            ->name('sales.index');

        Route::get('/sales/create', [SaleController::class, 'create'])
            ->name('sales.create');

        Route::post('/sales', [SaleController::class, 'store'])
            ->name('sales.store');

        Route::get('/sales/{sale}', [SaleController::class, 'show'])
            ->name('sales.show');

        // Laporan transaksi
        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports.index');
    });

    // Hanya Admin yang boleh mengelola data
    Route::middleware('role:admin')->group(function () {

        // Kategori
        Route::resource('categories', CategoryController::class)
            ->except(['show']);

        // Pengelolaan produk
        Route::resource('products', ProductController::class)
            ->except(['index', 'show']);

        // Supplier
        Route::resource('suppliers', SupplierController::class)
            ->except(['show']);

        // Pengguna
        Route::resource('users', UserController::class)
            ->except(['show']);

        // Pembatalan pembelian
        Route::post('/purchases/{purchase}/cancel', [
            PurchaseController::class,
            'cancel'
        ])->name('purchases.cancel');

        // Pembatalan penjualan
        Route::post('/sales/{sale}/cancel', [
            SaleController::class,
            'cancel'
        ])->name('sales.cancel');
    });
});
