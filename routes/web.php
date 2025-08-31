<?php 

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\UomController;

Route::get('/', function () {
    return view('welcome');
});

// Update dashboard route to use view
Route::get('/dashboard', function() {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // CRUD Product
    Route::resource('products', ProductController::class);
    
    // Route for UOM prices
    Route::get('/products/{product}/uom-prices', [ProductController::class, 'getUomPrices'])
        ->name('products.uom-prices');

    // CRUD Sales
    Route::resource('sales', SalesController::class);

    // CRUD UOM
    Route::resource('uoms', UomController::class);

    // Reports (temporarily redirect to dashboard)
    Route::get('/reports/stock', function() {
        return redirect()->route('dashboard');
    })->name('reports.stock');
    
    Route::get('/reports/finance', function() {
        return redirect()->route('dashboard');
    })->name('reports.finance');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';