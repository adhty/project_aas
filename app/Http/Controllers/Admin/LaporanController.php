<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\Pengembalian;

class LaporanController extends Controller
{
    public function barang()
    {
        $barangs = Barang::with('kategoris')->get();
        return view('laporan.barang', compact('barangs'));
    }

    public function peminjaman()
    {
        $peminjamans = Peminjaman::with(['user', 'barang'])->get();
        return view('admin.laporan.peminjaman', compact('peminjamans'));
    }

    public function pengembalian(Request $request)
    {
        // Buat query dasar
        $query = Pengembalian::with(['peminjaman.user', 'peminjaman.barang'])
            ->where('status', 'diterima');
        
        // Filter berdasarkan tanggal mulai
        if ($request->has('tanggal_mulai') && $request->tanggal_mulai) {
            $query->whereDate('tanggal_pengembalian', '>=', $request->tanggal_mulai);
        }

        // Filter berdasarkan tanggal akhir
        if ($request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $query->whereDate('tanggal_pengembalian', '<=', $request->tanggal_akhir);
        }
        
        // Filter berdasarkan kondisi barang
        if ($request->has('kondisi') && $request->kondisi) {
            $query->where('kondisi_barang', $request->kondisi);
        }
        
        // Ambil data
        $pengembalians = $query->get()
            ->map(function ($pengembalian) {
                return (object)[
                    'id' => $pengembalian->id,
                    'user' => $pengembalian->peminjaman->user,
                    'barang' => $pengembalian->peminjaman->barang,
                    'jumlah' => $pengembalian->jumlah_kembali,
                    'tanggal_peminjaman' => $pengembalian->peminjaman->tanggal_pinjam,
                    'tanggal_pengembalian' => $pengembalian->tanggal_pengembalian,
                    'kondisi_barang' => $pengembalian->kondisi_barang,
                    'biaya_denda' => $pengembalian->biaya_denda,
                    'status' => $pengembalian->status
                ];
            });

        // Jika request meminta export Excel
        if ($request->has('export') && $request->export == 'excel') {
            return $this->exportPengembalianToExcel($pengembalians);
        }

        return view('admin.laporan.pengembalian', compact('pengembalians'));
    }

    private function exportPengembalianToExcel($pengembalians)
    {
        // Pastikan package fast-excel sudah diinstall
        // composer require rap2hpoutre/fast-excel
        
        // Format data untuk export
        $data = $pengembalians->map(function ($data, $index) {
            return [
                'No' => $index + 1,
                'Nama Peminjam' => $data->user->name ?? '-',
                'Nama Barang' => $data->barang->nama ?? '-',
                'Jumlah' => $data->jumlah,
                'Tanggal Pinjam' => $data->tanggal_peminjaman,
                'Tanggal Kembali' => $data->tanggal_pengembalian,
                'Kondisi Barang' => ucfirst($data->kondisi_barang),
                'Biaya Denda' => 'Rp ' . number_format($data->biaya_denda, 0, ',', '.')
            ];
        });

        // Buat nama file dengan timestamp
        $fileName = 'Laporan_Pengembalian_' . \Carbon\Carbon::now()->format('d-m-Y_H-i-s') . '.xlsx';

        // Export ke Excel dan download
        return (new \Rap2hpoutre\FastExcel\FastExcel($data))->download($fileName);
    }
}







