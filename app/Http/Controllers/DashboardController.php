<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalColumn = 'total_cents';
        $now = Carbon::now('Asia/Jakarta');
        $today = $now->copy()->startOfDay();

        // ========== FILTER TANGGAL CUSTOM ==========
        $startDateFilter = $request->input('start_date', $now->copy()->subDays(6)->format('Y-m-d'));
        $endDateFilter = $request->input('end_date', $now->format('Y-m-d'));
        
        $startDate = Carbon::parse($startDateFilter)->startOfDay();
        $endDate = Carbon::parse($endDateFilter)->endOfDay();

        // ========== STATISTIK UTAMA ==========
        $salesToday = (int) Sale::whereDate('created_at', $today)->sum($totalColumn);
        $totalSales = (int) Sale::sum($totalColumn);
        $totalProducts = Product::count();
        $totalStock = (int) Product::sum('stock_warehouse');

        // ========== TRANSAKSI HARI INI ==========
        $transactionsToday = Sale::whereDate('created_at', $today)->count();

        // ========== RATA-RATA NILAI TRANSAKSI ==========
        $avgTransactionValue = $transactionsToday > 0 
            ? (int) ($salesToday / $transactionsToday) 
            : 0;

        // ========== PERBANDINGAN BULAN INI VS BULAN LALU ==========
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        $salesThisMonth = (int) Sale::whereBetween('created_at', [$startOfMonth, $now])->sum($totalColumn);
        $salesLastMonth = (int) Sale::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->sum($totalColumn);
        
        $percentageChange = 0;
        if ($salesLastMonth > 0) {
            $percentageChange = (($salesThisMonth - $salesLastMonth) / $salesLastMonth) * 100;
        }

        // ========== GRAFIK PENJUALAN (DENGAN FILTER) ==========
        $salesData = DB::table('sales')
            ->selectRaw('DATE(created_at) as date, SUM(total_cents) as total')
            ->whereRaw('DATE(created_at) BETWEEN ? AND ?', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'), 'asc')
            ->get();

        $chartLabels = collect();
        $chartValues = collect();

        // Generate labels untuk range tanggal yang dipilih
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $formatted = $currentDate->format('Y-m-d');
            $row = $salesData->firstWhere('date', $formatted);
            $totalForDate = $row ? (int) $row->total : 0;

            $chartLabels->push($currentDate->format('d M'));
            $chartValues->push($totalForDate);
            
            $currentDate->addDay();
        }

        // ========== TOP 5 PRODUK TERLARIS ==========
        $topProducts = DB::table('sales_item')
            ->join('products', 'sales_item.product_id', '=', 'products.id')
            ->join('sales', 'sales_item.sale_id', '=', 'sales.id')
            ->whereBetween('sales.created_at', [$startDate, $endDate])
            ->select(
                'products.name',
                DB::raw('SUM(sales_item.qty) as total_quantity'),
                DB::raw('SUM(sales_item.subtotal_cents) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();

        $topProductNames = $topProducts->pluck('name');
        $topProductQuantities = $topProducts->pluck('total_quantity');

        // ========== PRODUK STOK MENIPIS (< 10) ==========
        $lowStockProducts = Product::where('stock_warehouse', '<', 35)
            ->where('stock_warehouse', '>', 0)
            ->orderBy('stock_warehouse', 'asc')
            ->limit(20)
            ->get(['id', 'name', 'stock_warehouse']);

        // ========== PRODUK STOK HABIS ==========
        $outOfStockCount = Product::where('stock_warehouse', 0)->count();

        // ========== GRAFIK KASIR TERBAIK ==========
        $topCashiers = DB::table('sales')
            ->join('users', 'sales.cashier_id', '=', 'users.id')
            ->whereBetween('sales.created_at', [$startDate, $endDate])
            ->select(
                'users.name',
                DB::raw('COUNT(sales.id) as total_transactions'),
                DB::raw('SUM(sales.total_cents) as total_sales')
            )
            ->groupBy('users.id', 'users.name')
            ->orderBy('total_transactions', 'desc')
            ->limit(5)
            ->get();

        $cashierNames = $topCashiers->pluck('name');
        $cashierTransactions = $topCashiers->pluck('total_transactions');

        // ========== JAM SIBUK (Peak Hours) ==========
        $peakHours = DB::table('sales')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COUNT(*) as total_transactions')
            )
            ->groupBy(DB::raw('HOUR(created_at)'))
            ->orderBy('total_transactions', 'desc')
            ->get();

        $hourLabels = collect();
        $hourValues = collect();
        
        for ($h = 0; $h < 24; $h++) {
            $hourData = $peakHours->firstWhere('hour', $h);
            $hourLabels->push(str_pad($h, 2, '0', STR_PAD_LEFT) . ':00');
            $hourValues->push($hourData ? $hourData->total_transactions : 0);
        }

        // ========== METODE PEMBAYARAN (DISABLED - kolom tidak ada) ==========
        // Jika ingin menggunakan fitur ini, tambahkan kolom payment_method ke tabel sales
        $paymentLabels = collect(['Cash']); // Default: semua Cash
        $paymentCounts = collect([Sale::whereBetween('created_at', [$startDate, $endDate])->count()]);

        return view('dashboard', [
            'salesToday' => $salesToday,
            'totalSales' => $totalSales,
            'totalProducts' => $totalProducts,
            'totalStock' => $totalStock,
            'transactionsToday' => $transactionsToday,
            'avgTransactionValue' => $avgTransactionValue,
            'salesThisMonth' => $salesThisMonth,
            'salesLastMonth' => $salesLastMonth,
            'percentageChange' => $percentageChange,
            'chartLabels' => $chartLabels,
            'chartValues' => $chartValues,
            'topProductNames' => $topProductNames,
            'topProductQuantities' => $topProductQuantities,
            'lowStockProducts' => $lowStockProducts,
            'outOfStockCount' => $outOfStockCount,
            'cashierNames' => $cashierNames,
            'cashierTransactions' => $cashierTransactions,
            'hourLabels' => $hourLabels,
            'hourValues' => $hourValues,
            'paymentLabels' => $paymentLabels,
            'paymentCounts' => $paymentCounts,
            'startDate' => $startDateFilter,
            'endDate' => $endDateFilter,
        ]);
    }

    // ========== EXPORT TO EXCEL (CSV) ==========
    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(6)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $sales = Sale::with(['cashier', 'items.product'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'sales_report_' . $startDate . '_to_' . $endDate . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($sales) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No. Transaksi', 'Tanggal', 'Kasir', 'Subtotal', 'Diskon', 'Total', 'Dibayar', 'Kembalian']);

            foreach ($sales as $sale) {
                fputcsv($file, [
                    $sale->no_trans,
                    $sale->created_at->format('d/m/Y H:i'),
                    $sale->cashier->name ?? '-',
                    $sale->subtotal_cents,
                    $sale->discount_cents,
                    $sale->total_cents,
                    $sale->paid_cents,
                    $sale->change_cents,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ========== GET NEW TRANSACTIONS (untuk notifikasi real-time) ==========
    public function getNewTransactions(Request $request)
    {
        $lastCheck = $request->input('last_check', now()->subMinutes(5));
        
        $newTransactions = Sale::where('created_at', '>', $lastCheck)
            ->with('cashier')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($sale) {
                return [
                    'id' => $sale->id,
                    'no_trans' => $sale->no_trans,
                    'total' => $sale->total_cents,
                    'cashier' => $sale->cashier->name ?? 'Unknown',
                    'time' => $sale->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'count' => $newTransactions->count(),
            'transactions' => $newTransactions,
        ]);
    }
}