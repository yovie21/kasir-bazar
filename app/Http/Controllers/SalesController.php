<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index()
    {
        $sales = Sale::all();
    return view('sales', compact('sales'));
    }

    public function create()
    {
        return view('sales.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cashier_id' => 'required|exists:users,id',
            'no_trans' => 'required|unique:sales,no_trans',
            'subtotal_cents' => 'required|integer',
            'discount_cents' => 'nullable|integer',
            'total_cents' => 'required|integer',
            'paid_cents' => 'required|integer',
            'change_cents' => 'required|integer',
        ]);

        Sale::create($request->all());
        return redirect()->route('sales.index')->with('success', 'Sale berhasil ditambahkan.');
    }

    public function show(Sale $sale)
    {
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        return view('sales.edit', compact('sale'));
    }

    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'cashier_id' => 'required|exists:users,id',
            'no_trans' => 'required|unique:sales,no_trans,' . $sale->id,
            'subtotal_cents' => 'required|integer',
            'discount_cents' => 'nullable|integer',
            'total_cents' => 'required|integer',
            'paid_cents' => 'required|integer',
            'change_cents' => 'required|integer',
        ]);

        $sale->update($request->all());
        return redirect()->route('sales.index')->with('success', 'Sale berhasil diupdate.');
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Sale berhasil dihapus.');
    }
}
