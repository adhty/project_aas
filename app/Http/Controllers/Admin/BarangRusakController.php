<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarangRusak;
use App\Models\Barang;

class BarangRusakController extends Controller
{
    public function index(){
        $barangRusak = BarangRusak::with('barang')->orderBy('created_at', 'desc')->get();
        $totalRusak = $barangRusak->count();

        return view('admin.barangrusak.index', compact('barangRusak', 'totalRusak'));
    }

    public function create()
    {
        $barangList = Barang::all();
        return view('admin.barangrusak.create', compact('barangList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        BarangRusak::create([
            'barang_id' => $request->barang_id,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin.barangrusak.index')->with('success', 'Data barang rusak berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $barangRusak = BarangRusak::findOrFail($id);
        $barangRusak->delete();

        return redirect()->route('admin.barangrusak.index')->with('success', 'Data barang rusak berhasil dihapus.');
    }
}

