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
            $q->where('uomName', 'like', "%{$search}%");
        });
    }

    // ambil parameter per_page dari request, default 10
    $perPage = $request->input('per_page', 10);

    $products = $query->orderBy('created_at', 'desc')
                      ->paginate($perPage)
                      ->appends(['search' => $request->search, 'per_page' => $perPage]);

    $uoms = Uom::orderBy('uomName')->get();

    return view('master_produk', compact('products', 'uoms'));
}


    public function store(Request $request)
{
    try {
        // Validasi input dasar
        $validated = $request->validate([
            'barcode' => 'required|unique:products',
            'sku' => 'required|unique:products',
            'name' => 'required',
            'uomId' => 'required|exists:uoms,uomId',
            'stock_warehouse' => 'required|numeric|min:0',
            'uom_prices' => 'required|array',
            'uom_prices.*.uom_id' => 'required|exists:uoms,uomId',
            'uom_prices.*.price_cents' => 'required|numeric|min:1',
            'uom_prices.*.konv_to_base' => 'required|numeric|min:1',
        ]);

        // Buat produk
        $product = Product::create([
            'barcode' => $validated['barcode'],
            'sku' => $validated['sku'],
            'name' => $validated['name'],
            'uomId' => $validated['uomId'],
            'stock_warehouse' => $validated['stock_warehouse'],
            'price_cents' => $request->input('uom_prices.0.price_cents') // Simpan harga dasar
        ]);

        // Simpan harga UOM
        foreach ($request->uom_prices as $uomPrice) {
            $product->uomPrices()->create([
                'uom_id' => $uomPrice['uom_id'],
                'price_cents' => $uomPrice['price_cents'],
                'konv_to_base' => $uomPrice['konv_to_base'],
                'is_base' => $uomPrice['uom_id'] == $validated['uomId']
            ]);
        }

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    } catch (\Exception $e) {
        return back()->withErrors(['msg' => 'Error: ' . $e->getMessage()])->withInput();
    }
}

    public function update(Request $request, Product $product)
{
    try {
        $validated = $request->validate([
            'barcode' => 'required|unique:products,barcode,' . $product->id,
            'sku' => 'required|unique:products,sku,' . $product->id,
            'name' => 'required',
            'uomId' => 'required|exists:uoms,uomId',
            'stock_warehouse' => 'required|numeric|min:0',
            'uom_prices' => 'required|array',
            'uom_prices.*.uom_id' => 'required|exists:uoms,uomId',
            'uom_prices.*.price_cents' => 'required|numeric|min:1',
            'uom_prices.*.konv_to_base' => 'required|numeric|min:1',
        ]);

        // Update produk
        $product->update([
            'barcode' => $validated['barcode'],
            'sku' => $validated['sku'],
            'name' => $validated['name'],
            'uomId' => $validated['uomId'],
            'stock_warehouse' => $validated['stock_warehouse'],
            'price_cents' => $request->input('uom_prices.0.price_cents')
        ]);

        // Hapus UOM prices lama
        $product->uomPrices()->delete();

        // Simpan UOM prices baru
        foreach ($request->uom_prices as $uomPrice) {
            $product->uomPrices()->create([
                'uom_id' => $uomPrice['uom_id'],
                'price_cents' => $uomPrice['price_cents'],
                'konv_to_base' => $uomPrice['konv_to_base'],
                'is_base' => $uomPrice['uom_id'] == $validated['uomId']
            ]);
        }

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui!');
    } catch (\Exception $e) {
        return back()->withErrors(['msg' => 'Error: ' . $e->getMessage()])->withInput();
    }
}

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
                         ->with('success', 'Produk berhasil dihapus!');
    }

    public function getUomPrices(Product $product)
    {
        return response()->json(
            $product->uomPrices()
                ->with('uom')
                ->get()
                ->map(function($price) {
                    return [
                        'uom_id' => $price->uom_id,
                        'price_cents' => $price->price_cents,
                        'konv_to_base' => $price->konv_to_base
                    ];
                })
        );
    }
}