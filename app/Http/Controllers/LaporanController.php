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
        $query = Sale::with(['items.product.uom', 'items.uom', 'cashier']);


        // Filter tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Cari berdasarkan No Transaksi
        if ($request->filled('search')) {
            $query->where('no_trans', 'like', '%' . $request->search . '%');
        }

        $sales = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('laporan.transaksi', compact('sales'));
    }
}
