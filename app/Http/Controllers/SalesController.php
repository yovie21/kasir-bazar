<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SalesItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    /**
     * Halaman kasir
     */
    public function index()
    {
        return view('sales.kasir'); // pastikan view ada di resources/views/sales/kasir.blade.php
    }

    /**
     * Tambah item ke keranjang via AJAX
     */
    public function addItem(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string'
        ]);

        $product = Product::with('uomPrices.uom')->where('barcode', $request->barcode)->first();

        if (!$product) {
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }

        $uomName = $product->uom?->uomName ?? '';
        $harga = 0;

        // Ambil harga dari master uom prices (langsung rupiah)
        $baseUomPrice = $product->uomPrices->where('is_default', 1)->first();
        if ($baseUomPrice && $baseUomPrice->price_cents > 0) {
            $harga = $baseUomPrice->price_cents;
            $uomName = $baseUomPrice->uom?->uomName ?? $uomName;
        }
        // Jika ada uomId di product
        elseif ($product->uomId) {
            $productUomPrice = $product->uomPrices->where('uom_id', $product->uomId)->first();
            if ($productUomPrice && $productUomPrice->price_cents > 0) {
                $harga = $productUomPrice->price_cents;
                $uomName = $productUomPrice->uom?->uomName ?? $uomName;
            }
        }
        // Ambil uom pertama yang ada harga
        elseif ($product->uomPrices->isNotEmpty()) {
            $firstUomPrice = $product->uomPrices->where('price_cents', '>', 0)->first();
            if ($firstUomPrice) {
                $harga = $firstUomPrice->price_cents;
                $uomName = $firstUomPrice->uom?->uomName ?? $uomName;
            }
        }
        // Jika tidak ada uomPrices, pakai harga langsung dari produk
        elseif ($product->price_cents > 0) {
            $harga = $product->price_cents;
        }

        if ($harga <= 0) {
            return response()->json(['error' => 'Harga produk belum diatur'], 422);
        }

        return response()->json([
            'id'       => $product->id,
            'barcode'  => $product->barcode,
            'nama'     => $product->name,
            'uom'      => $uomName,
            'harga'    => $harga,   // langsung rupiah
            'jumlah'   => 1,
            'subtotal' => $harga * 1,
        ]);
    }

    /**
     * Proses checkout
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'bayar' => 'required|numeric|min:0',
        ]);

        $items = $request->items;
        $bayar = $request->bayar;

        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item['harga'] * $item['jumlah'];
        }

        $total = $subtotal; // kalau ada diskon/ppn bisa ditambah di sini
        $kembali = $bayar - $total;

        if ($kembali < 0) {
            return response()->json(['error' => 'Uang pembayaran kurang'], 422);
        }

        $sale = Sale::create([
            'cashier_id'     => Auth::id() ?? 1,
            'no_trans'       => 'TRX' . now()->format('YmdHis') . rand(100, 999),
            'subtotal_cents' => $subtotal, // sudah rupiah
            'discount_cents' => 0,
            'total_cents'    => $total,
            'paid_cents'     => $bayar,
            'change_cents'   => $kembali,
        ]);

        foreach ($items as $item) {
            SalesItem::create([
                'sale_id'     => $sale->id,
                'product_id'  => $item['id'],
                'qty'         => $item['jumlah'],
                'price_cents' => $item['harga'], // simpan rupiah
            ]);
        }

        return response()->json([
            'success'   => true,
            'sale_id'   => $sale->id,
            'kembalian' => $kembali,
        ]);
    }

    /**
     * Cetak struk
     */
    public function receipt($id)
    {
        $sale = Sale::with(['items.product', 'cashier'])->findOrFail($id);
        return view('sales.receipt', compact('sale'));
    }

    /**
     * Riwayat transaksi
     */
    public function show($id)
    {
        $sale = Sale::with(['items.product', 'cashier'])->findOrFail($id);
        return view('sales.show', compact('sale'));
    }
}
