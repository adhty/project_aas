<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;

class BarangController extends Controller
{
    public function index()
    {

        $barang = Barang::all();
        // return view('admin.barang.index', compact("barang"));
        return response()->json(Barang::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah_barang' => 'required|integer',
            'id_kategori' => 'required|exists:kategoris,id',
        ]);

        $barang = Barang::create($validated);

        return response()->json($barang, 201);
    }

    public function show($id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json(['message' => 'Barang tidak ditemukan'], 404);
        }

        return response()->json($barang);
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json(['message' => 'Barang tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'nama' => 'string|max:255',
            'jumlah_barang' => 'integer',
            'id_kategori' => 'exists:kategoris,id',
        ]);

        $barang->update($validated);

        return response()->json($barang);
    }

    public function destroy($id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json(['message' => 'Barang tidak ditemukan'], 404);
        }

        $barang->delete();

        return response()->json(['message' => 'Barang berhasil dihapus']);
    }
}

