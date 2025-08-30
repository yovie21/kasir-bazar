<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Uom;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('uom');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            })->orWhereHas('uom', function ($q) use ($search) {
                // gunakan kolom yang benar pada tabel uoms
                $q->where('uomName', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('created_at', 'desc')
                          ->paginate(10)
                          ->withQueryString();

        $uoms = Uom::orderBy('uomName')->get();

        return view('master_produk', compact('products', 'uoms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barcode'          => 'required',
            'sku'              => 'required',
            'name'             => 'required',
            'uomId'            => 'required|exists:uoms,uomId',
            'price_cents'      => 'required|numeric',
            'stock_warehouse'  => 'required|numeric',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
                         ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'barcode'          => 'required',
            'sku'              => 'required',
            'name'             => 'required',
            'uomId'            => 'required|exists:uoms,uomId',
            'price_cents'      => 'required|numeric',
            'stock_warehouse'  => 'required|numeric',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
                         ->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
                         ->with('success', 'Produk berhasil dihapus!');
    }
}
