<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;

class LaporanController extends Controller
{
    /**
     * Halaman laporan transaksi
     */
    public function transaksi(Request $request)
    {
        $query = Sale::with([
            'items.product.uom', 
            'items.uom', 
            'cashier'
        ]);

        // 🔹 Filter tanggal (range)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = $request->start_date . " 00:00:00";
            $end   = $request->end_date . " 23:59:59";
            $query->whereBetween('created_at', [$start, $end]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // 🔹 Cari berdasarkan No Transaksi
        if ($request->filled('search')) {
            $query->where('no_trans', 'like', '%' . $request->search . '%');
        }

        // 🔹 Urutkan & paginasi
        $sales = $query->orderBy('created_at', 'desc')->paginate(20);

        // Supaya filter tetap terbawa ke pagination
        $sales->appends($request->all());

        return view('laporan.transaksi', compact('sales'));
    }
    public function stok(Request $request)
{
    // Ambil semua produk dengan stok terakhir
    $products = \App\Models\Product::with('uom')->orderBy('name')->get();

    return view('laporan.stok', compact('products'));
}

public function keuangan(Request $request)
{
    $query = \App\Models\Sale::query();

    // Filter tanggal
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $start = $request->start_date . " 00:00:00";
        $end   = $request->end_date . " 23:59:59";
        $query->whereBetween('created_at', [$start, $end]);
    }

    $sales = $query->get();

    // Hitung total
    $total_penjualan = $sales->sum('total_cents');
    $total_modal = $sales->sum(function ($sale) {
        return $sale->items->sum(function ($item) {
            return $item->qty * ($item->product->cost_price ?? 0); // asumsi ada kolom cost_price di products
        });
    });
    $laba = $total_penjualan - $total_modal;

    return view('laporan.keuangan', compact('sales', 'total_penjualan', 'total_modal', 'laba'));
}
}
