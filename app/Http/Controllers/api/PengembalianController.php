<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengembalian;
use App\Models\Peminjaman;
use App\Models\StockBarang;
use Carbon\Carbon;

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
            'nama_pengembali' => 'required|string|max:255',
            'jumlah_kembali' => 'required|integer|min:1',
            'biaya_denda' => 'nullable|numeric|min:0',
        ]);
        
        // Cek apakah peminjaman sudah ada pengembalian
        $existingPengembalian = Pengembalian::where('peminjaman_id', $request->peminjaman_id)->first();
        if ($existingPengembalian) {
            // Jika pengembalian sudah ada tapi statusnya ditolak, kita bisa update atau buat baru
            if ($existingPengembalian->status === 'ditolak') {
                // Hapus pengembalian yang ditolak
                $existingPengembalian->delete();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Peminjaman ini sudah memiliki pengembalian'
                ], 400);
            }
        }
        
        // Cek apakah peminjaman sudah disetujui
        $peminjaman = Peminjaman::find($request->peminjaman_id);
        if (!$peminjaman) {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman tidak ditemukan'
            ], 404);
        }
        
        if ($peminjaman->status !== 'disetujui') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya peminjaman yang disetujui yang dapat dikembalikan'
            ], 400);
        }
        
        // Cek jumlah pengembalian tidak melebihi jumlah peminjaman
        if ($request->jumlah_kembali > $peminjaman->jumlah) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah pengembalian tidak boleh melebihi jumlah peminjaman'
            ], 400);
        }
        
        // Hitung denda otomatis
        $biaya_denda = $request->biaya_denda ?? 0;
        
        // 1. Cek keterlambatan pengembalian
        $tanggal_kembali_seharusnya = Carbon::parse($peminjaman->tanggal_kembali);
        $tanggal_pengembalian_aktual = Carbon::parse($request->tanggal_pengembalian);
        
        if ($tanggal_pengembalian_aktual->gt($tanggal_kembali_seharusnya)) {
            // Hitung selisih hari
            $selisih_hari = $tanggal_pengembalian_aktual->diffInDays($tanggal_kembali_seharusnya);
            
            // Denda keterlambatan: 10000 per hari per barang
            $denda_terlambat = $selisih_hari * 10000 * $request->jumlah_kembali;
            $biaya_denda += $denda_terlambat;
        }
        
        // 2. Cek kondisi barang rusak atau hilang
        if ($request->kondisi_barang !== 'baik') {
            // Ambil harga barang dari data barang
            $harga_barang = $peminjaman->barang->harga ?? 0;
            
            if ($request->kondisi_barang === 'rusak') {
                // Denda untuk barang rusak (50% dari harga barang)
                $denda_kondisi = $harga_barang * 0.5 * $request->jumlah_kembali;
                $biaya_denda += $denda_kondisi;
            } elseif ($request->kondisi_barang === 'hilang') {
                // Denda untuk barang hilang (100% dari harga barang)
                $denda_kondisi = $harga_barang * $request->jumlah_kembali;
                $biaya_denda += $denda_kondisi;
            }
        }
        
        $pengembalian = Pengembalian::create([
            'peminjaman_id' => $request->peminjaman_id,
            'tanggal_pengembalian' => $request->tanggal_pengembalian,
            'kondisi_barang' => $request->kondisi_barang,
            'catatan' => $request->catatan,
            'status' => 'menunggu',
            'nama_pengembali' => $request->nama_pengembali,
            'jumlah_kembali' => $request->jumlah_kembali,
            'biaya_denda' => $biaya_denda,
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
        $pengembalian = Pengembalian::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:menunggu,diterima,ditolak'
        ]);
        
        $pengembalian->status = $request->status;
        $pengembalian->save();
        
        // Jika status diterima, update status peminjaman dan stok barang
        if ($request->status === 'diterima') {
            $peminjaman = Peminjaman::find($pengembalian->peminjaman_id);
            $peminjaman->status = 'dikembalikan';
            $peminjaman->save();
            
            // Update stok barang jika kondisi baik
            if ($pengembalian->kondisi_barang === 'baik') {
                $stock = \App\Models\StockBarang::where('barang_id', $peminjaman->barang_id)->first();
                if ($stock) {
                    $stock->jumlah += $pengembalian->jumlah_kembali;
                    $stock->save();
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Status pengembalian berhasil diperbarui',
            'data' => $pengembalian
        ]);
    }
    
    public function getRiwayatUser($userId)
    {
        $pengembalians = Pengembalian::with(['peminjaman.barang'])
            ->whereHas('peminjaman', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $pengembalians
        ]);
    }
}




