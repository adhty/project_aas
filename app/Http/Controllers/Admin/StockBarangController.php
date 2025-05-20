<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockBarang;
use App\Models\Barang;

class StockBarangController extends Controller
{
    public function index()
    {
        $stocks = StockBarang::with('barangs')->get();
        return view('admin.stock.index', compact('stocks'));
    }

    public function create()
    {
        $barang = Barang::doesntHave('stock')->get(); // Biar barang yang udah ada stock nggak muncul lagi
        return view('admin.stock.create', compact('barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id|unique:stock_barang,barang_id',
            'jumlah' => 'required|integer|min:0',
        ]);

        StockBarang::create($request->only('id_barang', 'jumlah'));

        return redirect()->route('stock.create')->with('success', 'Stock berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $stock = StockBarang::with('barang')->findOrFail($id);
        $barang = Barang::all(); 
        return view('admin.stock.edit', compact('stock', 'barang'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:0',
        ]);

        $stock = StockBarang::findOrFail($id);
        $stock->update(['jumlah' => $request->jumlah]);

        return redirect()->route('stock.index')->with('success', 'Stock berhasil diupdate.');
    }

    public function destroy($id)
    {
        $stock = StockBarang::findOrFail($id);
        $stock->delete();

        return redirect()->route('stock.index')->with('success', 'Stock berhasil dihapus.');
    }
}
