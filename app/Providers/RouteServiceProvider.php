<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';
    
    // ⭐ Tambahkan Konstanta Rute Khusus
    public const ADMIN_DASHBOARD = '/dashboard'; // Bisa juga /laporan atau /products
    public const KASIR_DASHBOARD = '/kasir';     // Arahkan ke halaman utama penjualan
    public const SPV_DASHBOARD = '/laporan/transaksi'; // Arahkan ke halaman laporan

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    // ... (sisa kode lainnya)
}
