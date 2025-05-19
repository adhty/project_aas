<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengembalian;
use App\Models\Peminjaman;

class PengembalianController extends Controller
{
    public function index()
    {
        $pengembalians = Pengembalian::with(['peminjaman.user', 'peminjaman.barang'])->get();
        
        return response()->json([
            'success' => true,
            'data' => $pengembalians
        ]);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjamans,id',
            'tanggal_pengembalian' => 'required|date',
            'kondisi_barang' => 'required|in:baik,rusak,hilang',
            'catatan' => 'nullable|string',
        ]);
        
        // Cek apakah peminjaman sudah ada pengembalian
        $existingPengembalian = Pengembalian::where('peminjaman_id', $request->peminjaman_id)->first();
        if ($existingPengembalian) {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman ini sudah memiliki pengembalian'
            ], 400);
        }
        
        // Cek apakah peminjaman sudah disetujui
        $peminjaman = Peminjaman::find($request->peminjaman_id);
        if ($peminjaman->status !== 'disetujui') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya peminjaman yang disetujui yang dapat dikembalikan'
            ], 400);
        }
        
        $pengembalian = Pengembalian::create([
            'peminjaman_id' => $request->peminjaman_id,
            'tanggal_pengembalian' => $request->tanggal_pengembalian,
            'kondisi_barang' => $request->kondisi_barang,
            'catatan' => $request->catatan,
            'status' => 'menunggu',
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Permintaan pengembalian berhasil dibuat',
            'data' => $pengembalian
        ], 201);
    }
    
    public function show($id)
    {
        $pengembalian = Pengembalian::with(['peminjaman.user', 'peminjaman.barang'])->find($id);
        
        if (!$pengembalian) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $pengembalian
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $pengembalian = Pengembalian::find($id);
        
        if (!$pengembalian) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
        
        $request->validate([
            'status' => 'required|in:menunggu,diterima,ditolak',
        ]);
        
        $pengembalian->status = $request->status;
        $pengembalian->save();
        
        // Jika status diterima, update status peminjaman
        if ($request->status === 'diterima') {
            $peminjaman = Peminjaman::find($pengembalian->peminjaman_id);
            $peminjaman->status = 'dikembalikan';
            $peminjaman->save();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Status pengembalian berhasil diperbarui',
            'data' => $pengembalian
        ]);
    }
}