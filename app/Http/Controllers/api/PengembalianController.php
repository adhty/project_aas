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
    public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'peminjaman_id'       => 'required|exists:peminjamans,id',
        'tanggal_pengembalian'=> 'required|date',
        'kondisi_barang'      => 'required|in:baik,rusak,hilang',
        'catatan'             => 'nullable|string',
        'nama_pengembali'     => 'required|string|max:255',
        'jumlah_kembali'      => 'required|integer|min:1',
        'biaya_denda'         => 'nullable|numeric|min:0',
    ]);

    // Cek apakah sudah ada pengembalian untuk peminjaman ini
    $existingPengembalian = Pengembalian::where('peminjaman_id', $request->peminjaman_id)->first();

    if ($existingPengembalian) {
        if ($existingPengembalian->status !== 'ditolak') {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman ini sudah memiliki pengembalian'
            ], 400);
        }

        // Hapus pengembalian sebelumnya jika statusnya ditolak
        $existingPengembalian->delete();
    }

    // Ambil data peminjaman
    $peminjaman = Peminjaman::with('barang')->find($request->peminjaman_id);

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

    if ($request->jumlah_kembali > $peminjaman->jumlah) {
        return response()->json([
            'success' => false,
            'message' => 'Jumlah pengembalian tidak boleh melebihi jumlah peminjaman'
        ], 400);
    }

    // Hitung denda
    $biaya_denda = $request->biaya_denda ?? 0;

    // Denda keterlambatan
    $tanggal_kembali_seharusnya = Carbon::parse($peminjaman->tanggal_kembali);
    $tanggal_pengembalian_aktual = Carbon::parse($request->tanggal_pengembalian);

    if ($tanggal_pengembalian_aktual->gt($tanggal_kembali_seharusnya)) {
        $selisih_hari = $tanggal_pengembalian_aktual->diffInDays($tanggal_kembali_seharusnya);
        $biaya_denda += $selisih_hari * 10000 * $request->jumlah_kembali;
    }

    // Denda kondisi barang
    $harga_barang = $peminjaman->barang->harga ?? 0;

    if ($request->kondisi_barang === 'rusak') {
        $biaya_denda += 0.5 * $harga_barang * $request->jumlah_kembali;
    } elseif ($request->kondisi_barang === 'hilang') {
        $biaya_denda += 1.0 * $harga_barang * $request->jumlah_kembali;
    }

    // Simpan pengembalian
    $pengembalian = Pengembalian::create([
        'peminjaman_id'        => $request->peminjaman_id,
        'tanggal_pengembalian' => $request->tanggal_pengembalian,
        'kondisi_barang'       => $request->kondisi_barang,
        'catatan'              => $request->catatan,
        'status'               => 'menunggu',
        'nama_pengembali'      => $request->nama_pengembali,
        'jumlah_kembali'       => $request->jumlah_kembali,
        'biaya_denda'          => $biaya_denda,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Permintaan pengembalian berhasil dibuat',
        'data'    => $pengembalian
    ], 201);
}

}




