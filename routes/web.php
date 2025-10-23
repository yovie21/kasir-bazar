<?php 

use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\UomController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\LaporanController; 
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware('auth')->group(function () {
    // ========================================
    // 📊 DASHBOARD (dengan semua fitur baru)
    // ========================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export/excel', [DashboardController::class, 'exportExcel'])->name('dashboard.export.excel');
    Route::get('/dashboard/notifications', [DashboardController::class, 'getNewTransactions'])->name('dashboard.notifications');

    // ========================================
    // 👤 REGISTER USER
    // ========================================
    Route::get('/registeruser', [RegisteredUserController::class, 'create'])->name('registeruser');
    Route::post('/registeruser', [RegisteredUserController::class, 'store']);

    // ========================================
    // 📦 CRUD PRODUK
    // ========================================
    Route::resource('products', ProductController::class);
    Route::get('/products/{product}/uom-prices', [ProductController::class, 'getUomPrices'])
        ->name('products.uom-prices');

    // ========================================
    // 📏 CRUD UOM
    // ========================================
    Route::resource('uoms', UomController::class);

    // ========================================
    // 💰 KASIR & TRANSAKSI
    // ========================================
    Route::prefix('kasir')->name('kasir.')->group(function () {
        Route::get('/', [SalesController::class, 'index'])->name('index');
        Route::post('/add-item', [SalesController::class, 'addItem'])->name('addItem');
        Route::post('/checkout', [SalesController::class, 'checkout'])->name('checkout');
        Route::get('/receipt/{id}', [SalesController::class, 'receipt'])->name('receipt');
    });

    // Riwayat transaksi untuk admin
    Route::resource('sales', SalesController::class)->except(['create', 'store']);

    // ========================================
    // 📋 LAPORAN
    // ========================================
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/transaksi', [LaporanController::class, 'transaksi'])->name('transaksi');
        Route::get('/stok', [LaporanController::class, 'stok'])->name('stok');
        Route::get('/keuangan', [LaporanController::class, 'keuangan'])->name('keuangan');
    });

    // ========================================
    // 👨‍💼 PROFILE
    // ========================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';