<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\StockBarang;
use App\Models\Barang;
use App\Models\User;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['barang', 'user'])->get();

        return response()->json([
            'success' => true,
            'data' => $peminjamans->map(function ($item) {
                return [
                    'id' => $item->id,
                    'peminjaman_id' => $item->id,
                    'user_id' => $item->user_id,
                    'barang_id' => $item->barang_id,
                    'alasan_pinjam' => $item->alasan_pinjam,
                    'jumlah' => $item->jumlah,
                    'tanggal_pinjam' => $item->tanggal_pinjam,
                    'tanggal_kembali' => $item->tanggal_kembali,
                    'status' => $item->status,
                    'barang' => [
                        'nama_barang' => $item->barang?->nama_barang ?? 'Barang tidak diketahui',
                    ],
                    'user' => [
                        'name' => $item->user?->name ?? 'User tidak diketahui',
                    ],
                ];
            }),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'alasan_pinjam' => 'nullable|string',
            'jumlah' => 'nullable|integer|min:1',
        ]);

        $userId = $request->user_id;
        $user = User::find($userId); 
        $jumlah = $request->jumlah ?? 1;

        $barang = Barang::where('id', $request->barang_id)->first();

        if (!$barang || $barang->jumlah_barang < $jumlah) {
            return response()->json([
                'success' => false,
                'message' => 'Stok barang tidak mencukupi untuk dipinjam'
            ], 400);
        }

        $peminjaman = Peminjaman::create([
            'user_id' => $user->id,
            'barang_id' => $request->barang_id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'status' => 'menunggu',
            'alasan_pinjam' => $request->alasan_pinjam,
            'jumlah' => $jumlah,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permintaan peminjaman berhasil dibuat',
            'data' => $peminjaman
        ], 201);
    }

    public function show($id)
    {
        $data = Peminjaman::with(['barang', 'user'])->find($id);

        if (!$data) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $data->id,
                'peminjaman_id' => $data->id,
                'user_id' => $data->user_id,
                'barang_id' => $data->barang_id,
                'alasan_pinjam' => $data->alasan_pinjam,
                'jumlah' => $data->jumlah,
                'tanggal_pinjam' => $data->tanggal_pinjam,
                'tanggal_kembali' => $data->tanggal_kembali,
                'status' => $data->status,
                'barang' => [
                    'nama_barang' => $data->barang?->nama_barang ?? 'Barang tidak diketahui',
                ],
                'user' => [
                    'name' => $data->user?->name ?? 'User tidak diketahui',
                ],
            ],
        ]);
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

        $oldStatus = $peminjaman->status;

        $peminjaman->update($request->only('status'));

        if ($oldStatus !== 'disetujui' && $peminjaman->status === 'disetujui') {
            $stockBarang = StockBarang::where('barang_id', $peminjaman->barang_id)->first();

            if ($stockBarang) {
                if ($stockBarang->jumlah < $peminjaman->jumlah) {
                    $peminjaman->status = $oldStatus;
                    $peminjaman->save();

                    return response()->json([
                        'success' => false,
                        'message' => 'Stok barang tidak mencukupi untuk dipinjam'
                    ], 400);
                }

                $stockBarang->jumlah -= $peminjaman->jumlah;
                $stockBarang->save();
            }
        }

        if ($oldStatus === 'disetujui' && in_array($peminjaman->status, ['ditolak', 'dikembalikan'])) {
            if ($peminjaman->status === 'ditolak') {
                $stockBarang = StockBarang::where('barang_id', $peminjaman->barang_id)->first();
                if ($stockBarang) {
                    $stockBarang->jumlah += $peminjaman->jumlah;
                    $stockBarang->save();
                }
            }
        }

        $response = $peminjaman->toArray();
        $response['peminjaman_id'] = $peminjaman->id;

        return response()->json([
            'message' => 'Peminjaman berhasil diperbarui.',
            'data' => $response
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

