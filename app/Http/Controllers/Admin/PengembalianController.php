<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengembalian;
use App\Models\Peminjaman;
use App\Models\StockBarang;

class PengembalianController extends Controller
{
    public function index()
    {
        $pengembalians = Pengembalian::with(['peminjaman.user', 'peminjaman.barang'])->get();
        return view('admin.pengembalian.index', compact('pengembalians'));
    }
    
    public function create()
    {
        $peminjamans = Peminjaman::where('status', 'disetujui')
            ->whereDoesntHave('pengembalian')
            ->with(['user', 'barang'])
            ->get();
        return view('admin.pengembalian.create', compact('peminjamans'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjamans,id',
            'tanggal_pengembalian' => 'required|date',
            'kondisi_barang' => 'required|in:baik,rusak,hilang',
            'catatan' => 'nullable|string',
        ]);
        
        Pengembalian::create([
            'peminjaman_id' => $request->peminjaman_id,
            'tanggal_pengembalian' => $request->tanggal_pengembalian,
            'kondisi_barang' => $request->kondisi_barang,
            'catatan' => $request->catatan,
            'status' => 'menunggu',
        ]);
        
        return redirect()->route('admin.pengembalian.index')
            ->with('success', 'Permintaan pengembalian berhasil dibuat.');
    }
    
    public function show($id)
    {
        $pengembalian = Pengembalian::with(['peminjaman.user', 'peminjaman.barang'])->findOrFail($id);
        return view('admin.pengembalian.show', compact('pengembalian'));
    }
    
    public function approve($id)
    {
        $pengembalian = Pengembalian::with('peminjaman')->findOrFail($id);
        $pengembalian->status = 'diterima';
        $pengembalian->save();
        
        // Update status peminjaman menjadi dikembalikan
        $peminjaman = $pengembalian->peminjaman;
        $peminjaman->status = 'dikembalikan';
        $peminjaman->save();
        
        // Update stock barang
        if ($pengembalian->kondisi_barang == 'baik') {
            $stock = StockBarang::where('id_barang', $peminjaman->barang_id)->first();
            if ($stock) {
                $stock->jumlah += $peminjaman->jumlah;
                $stock->save();
            }
        }
        
        return redirect()->route('admin.pengembalian.index')
            ->with('success', 'Pengembalian berhasil disetujui.');
    }
    
    public function reject($id)
    {
        $pengembalian = Pengembalian::findOrFail($id);
        $pengembalian->status = 'ditolak';
        $pengembalian->save();
        
        return redirect()->route('admin.pengembalian.index')
            ->with('success', 'Pengembalian ditolak.');
    }
}

