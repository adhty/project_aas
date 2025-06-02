<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengembalian;
use App\Models\Peminjaman;
use App\Models\StockBarang;
use Carbon\Carbon;
use App\Models\User;

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

    public function riwayat($userId)
    {
        // Validasi user ID
        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        // Ambil data pengembalian berdasarkan user ID
        // Menggunakan relasi dari pengembalian -> peminjaman -> user
        $pengembalians = Pengembalian::with(['peminjaman.barang'])
            ->whereHas('peminjaman', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pengembalians->map(function ($item) {
                return [
                    'id' => $item->id,
                    'peminjaman_id' => $item->peminjaman_id,
                    'tanggal_pengembalian' => $item->tanggal_pengembalian,
                    'kondisi_barang' => $item->kondisi_barang,
                    'catatan' => $item->catatan,
                    'status' => $item->status,
                    'jumlah_kembali' => $item->jumlah_kembali,
                    'biaya_denda' => $item->biaya_denda,
                    'barang' => [
                        'nama_barang' => $item->peminjaman->barang->nama_barang ?? 'Barang tidak diketahui',
                    ],
                    'peminjaman' => [
                        'tanggal_pinjam' => $item->peminjaman->tanggal_pinjam ?? '-',
                        'tanggal_kembali' => $item->peminjaman->tanggal_kembali ?? '-',
                        'jumlah' => $item->peminjaman->jumlah ?? 0,
                    ],
                ];
            })
        ]);
    }
}



