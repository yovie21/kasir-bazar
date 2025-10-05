<?php

namespace App\Http\Controllers;

use App\Models\Uom;
use Illuminate\Http\Request;

class UomController extends Controller
{
    // Tampilkan daftar UOM + form Tambah/Edit
    public function index()
    {
        $uoms = Uom::orderBy('uomName')->paginate(10);
        return view('master_uom', compact('uoms')); // pakai view master_uom.blade.php
    }

    // Tampilkan form edit (pakai index + data edit)
    public function edit($uomId)
    {
        $uom = Uom::findOrFail($uomId);
        $uoms = Uom::orderBy('uomName')->paginate(10);

        return view('master_uom', compact('uom', 'uoms'));
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'uomKode' => 'required|unique:uoms,uomKode|max:10',
            'uomName' => 'required|max:50',
            'konvPcs' => 'required|integer|min:1',
        ]);

        Uom::create($request->only(['uomKode', 'uomName', 'konvPcs']));

        return redirect()->route('uoms.index')->with('success', 'UOM berhasil ditambahkan.');
    }

    // Update data
    public function update(Request $request, $uomId)
    {
        $uom = Uom::findOrFail($uomId);

        $request->validate([
            'uomKode' => 'required|max:10|unique:uoms,uomKode,' . $uomId . ',uomId',
            'uomName' => 'required|max:50',
            'konvPcs' => 'required|integer|min:1',
        ]);

        $uom->update($request->only(['uomKode', 'uomName', 'konvPcs']));

        return redirect()->route('uoms.index')->with('success', 'UOM berhasil diperbarui.');
    }

    // Hapus data
    public function destroy($uomId)
    {
        $uom = Uom::findOrFail($uomId);
        $uom->delete();

        return redirect()->route('uoms.index')->with('success', 'UOM berhasil dihapus.');
    }
}
