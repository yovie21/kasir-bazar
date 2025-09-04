<?php 

use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\UomController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/', function () {
    return view('auth.login');
});

// Dashboard
Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Register user
    Route::get('/registeruser', [RegisteredUserController::class, 'create'])->name('registeruser');
    Route::post('/registeruser', [RegisteredUserController::class, 'store']);

    // CRUD Produk
    Route::resource('products', ProductController::class);

    // UOM Prices (ajax endpoint)
    Route::get('/products/{product}/uom-prices', [ProductController::class, 'getUomPrices'])
        ->name('products.uom-prices');

    // CRUD UOM
    Route::resource('uoms', UomController::class);

    Route::prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/', [SalesController::class, 'index'])->name('index');
    Route::post('/add-item', [SalesController::class, 'addItem'])->name('addItem');
    Route::post('/checkout', [SalesController::class, 'checkout'])->name('checkout');
    Route::get('/receipt/{id}', [SalesController::class, 'receipt'])->name('receipt');
});

    // ✅ CRUD Sales (riwayat transaksi untuk admin)
    Route::resource('sales', SalesController::class)->except(['create', 'store']);

    // Reports
    Route::get('/reports/stock', fn() => redirect()->route('dashboard'))->name('reports.stock');
    Route::get('/reports/finance', fn() => redirect()->route('dashboard'))->name('reports.finance');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
