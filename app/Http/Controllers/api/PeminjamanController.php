<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;

class PeminjamanController extends Controller
{
    public function index()
    {
        return response()->json(Peminjaman::with(['user', 'barang'])->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'barang_id' => 'required|exists:barangs,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
        ]);
        
        Peminjaman::create([
            'user_id' => $request->user_id,
            'barang_id' => $request->barang_id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'status' => 'menunggu', // misalnya default
        ]); 
        

        // return response()->json([
        //     'message' => 'Peminjaman berhasil dibuat.',
        //     'data' => $peminjaman,
        // ], 201);
    }

    public function show($id)
    {
        $data = Peminjaman::with(['user', 'barang'])->find($id);

        if (!$data) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::find($id);
        if (!$peminjaman) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        $request->validate([
            'status' => 'in:menunggu,disetujui,ditolak,dikembalikan',
        ]);

        $peminjaman->update($request->only('status'));

        return response()->json([
            'message' => 'Peminjaman berhasil diperbarui.',
            'data' => $peminjaman
        ]);
    }

    public function destroy($id)
    {
        $peminjaman = Peminjaman::find($id);
        if (!$peminjaman) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        $peminjaman->delete();
        return response()->json(['message' => 'Peminjaman berhasil dihapus.']);
    }
}
