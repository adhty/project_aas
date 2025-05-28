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
        $validated = $request->validate([
            'peminjaman_id'        => 'required|exists:peminjamans,id',
            'tanggal_pengembalian' => 'required|date',
            'kondisi_barang'       => 'required|string|in:baik,rusak,hilang',
            'catatan'              => 'nullable|string',
            'status'               => 'required|string',
            'jumlah_kembali'       => 'required|integer|min:1',
            'biaya_denda'          => 'nullable|numeric|min:0',
        ]);

        // Cek apakah sudah ada pengembalian (selain ditolak)
        $existing = Pengembalian::where('peminjaman_id', $validated['peminjaman_id'])->first();

        if ($existing && $existing->status !== 'ditolak') {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman ini sudah memiliki pengembalian.'
            ], 400);
        }

        if ($existing && $existing->status === 'ditolak') {
            $existing->delete();
        }

        // Ambil data peminjaman
        $peminjaman = Peminjaman::with('barang')->find($validated['peminjaman_id']);

        if (!$peminjaman) {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman tidak ditemukan.'
            ], 404);
        }

        if ($peminjaman->status !== 'disetujui') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya peminjaman yang disetujui yang bisa dikembalikan.'
            ], 400);
        }

        if ($validated['jumlah_kembali'] > $peminjaman->jumlah) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah pengembalian melebihi jumlah peminjaman.'
            ], 400);
        }

        // Hitung denda
        $denda = $validated['biaya_denda'] ?? 0;

        // Denda keterlambatan
        $tgl_kembali_seharusnya = Carbon::parse($peminjaman->tanggal_kembali);
        $tgl_pengembalian = Carbon::parse($validated['tanggal_pengembalian']);

        if ($tgl_pengembalian->gt($tgl_kembali_seharusnya)) {
            $hari_telat = $tgl_pengembalian->diffInDays($tgl_kembali_seharusnya);
            $denda += $hari_telat * 10000 * $validated['jumlah_kembali'];
        }

        // Denda kondisi barang
        $harga_barang = $peminjaman->barang->harga ?? 0;
        if ($validated['kondisi_barang'] === 'rusak') {
            $denda += 0.5 * $harga_barang * $validated['jumlah_kembali'];
        } elseif ($validated['kondisi_barang'] === 'hilang') {
            $denda += 1.0 * $harga_barang * $validated['jumlah_kembali'];
        }

        // Simpan pengembalian
        $pengembalian = Pengembalian::create([
            'peminjaman_id'        => $validated['peminjaman_id'],
            'tanggal_pengembalian' => $validated['tanggal_pengembalian'],
            'kondisi_barang'       => $validated['kondisi_barang'],
            'catatan'              => $validated['catatan'],
            'status'               => 'menunggu',
            'jumlah_kembali'       => $validated['jumlah_kembali'],
            'biaya_denda'          => $denda,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permintaan pengembalian berhasil dibuat.',
            'data'    => $pengembalian
        ], 201);
    }
}
