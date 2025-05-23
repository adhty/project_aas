<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Rap2hpoutre\FastExcel\FastExcel;

class LaporanController extends Controller
{
    public function peminjaman(Request $request)
    {
        $query = Peminjaman::with(['user', 'barang']);

        // Filter berdasarkan tanggal mulai
        if ($request->has('tanggal_mulai') && $request->tanggal_mulai) {
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_mulai);
        }

        // Filter berdasarkan tanggal akhir
        if ($request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_akhir);
        }

        // Filter berdasarkan status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $peminjamans = $query->orderBy('tanggal_pinjam', 'desc')->get();

        // Jika request meminta export Excel
        if ($request->has('export') && $request->export == 'excel') {
            return $this->exportToExcel($peminjamans);
        }

        return view('admin.laporan.peminjaman', compact('peminjamans'));
    }

    private function exportToExcel($peminjamans)
    {
        // Format data untuk export
        $data = $peminjamans->map(function ($pinjam) {
            return [
                'No' => $pinjam->id,
                'Nama Peminjam' => $pinjam->user->name ?? '-',
                'Nama Barang' => $pinjam->barang->nama ?? '-',
                'Jumlah' => $pinjam->jumlah,
                'Tanggal Pinjam' => $pinjam->tanggal_pinjam,
                'Tanggal Kembali' => $pinjam->tanggal_kembali,
                'Status' => ucfirst($pinjam->status)
            ];
        });

        // Buat nama file dengan timestamp
        $fileName = 'Laporan_Peminjaman_' . Carbon::now()->format('d-m-Y_H-i-s') . '.xlsx';

        // Export ke Excel dan download
        return (new FastExcel($data))->download($fileName);
    }
}
