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

    $product = Product::with('uomPrices.uom')
        ->where('barcode', $request->barcode)
        ->first();

    if (!$product) {
        return response()->json(['error' => 'Produk tidak ditemukan'], 404);
    }

    // Ambil semua uom dan harga
    $uoms = $product->uomPrices->map(function ($p) {
        return [
            'uom_id'   => $p->uom_id,
            'uom_name' => $p->uom?->uomName,
            'harga'    => $p->price_cents
        ];
    })->values();

    if ($uoms->isEmpty()) {
        return response()->json(['error' => 'Harga produk belum diatur'], 422);
    }

    // default ambil UOM pertama
    $default = $uoms->first();

    return response()->json([
        'id'       => $product->id,
        'barcode'  => $product->barcode,
        'nama'     => $product->name,
        'uoms'     => $uoms,
        'uom_id'   => $default['uom_id'],
        'uom'      => $default['uom_name'],
        'harga'    => $default['harga'],
        'jumlah'   => 1,
        'subtotal' => $default['harga'],
    ]);
}

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

    $total   = $subtotal;
    $kembali = $bayar - $total;

    if ($kembali < 0) {
        return response()->json(['error' => 'Uang pembayaran kurang'], 422);
    }

    $sale = Sale::create([
        'cashier_id'     => Auth::id() ?? 1,
        'no_trans'       => 'TRX' . now()->format('YmdHis') . rand(100, 999),
        'subtotal_cents' => $subtotal,
        'discount_cents' => 0,
        'total_cents'    => $total,
        'paid_cents'     => $bayar,
        'change_cents'   => $kembali,
    ]);

    foreach ($items as $item) {
        // Simpan item ke tabel sales_items
        SalesItem::create([
            'sale_id'       => $sale->id,
            'product_id'    => $item['id'],
            'uomId'         => $item['uomId'], // ✅ konsisten camelCase
            'qty'           => $item['jumlah'],
            'price_cents'   => $item['harga'],
            'subtotal_cents'=> $item['harga'] * $item['jumlah'],
        ]);

        // Cari konversi UOM
        $uomPrice = \App\Models\ProductUomPrice::where('product_id', $item['id'])
            ->where('uom_id', $item['uomId']) // ✅ pakai uomId
            ->first();

        if ($uomPrice) {
            // Hitung qty dalam base UOM
            $qtyBase = $item['jumlah'] * $uomPrice->konv_to_base;

            // Kurangi stok UOM yang dipakai
            $uomPrice->decrement('stock', $item['jumlah']);

            // Kurangi stok pada base UOM
            $baseUomPrice = \App\Models\ProductUomPrice::where('product_id', $item['id'])
                ->where('is_base', 1)
                ->first();

            if ($baseUomPrice) {
                $baseUomPrice->decrement('stock', $qtyBase);
            }

            // Update stok di master product supaya sinkron
            $product = Product::find($item['id']);
            if ($product) {
                $product->decrement('stock_warehouse', $qtyBase);
            }
        }
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
