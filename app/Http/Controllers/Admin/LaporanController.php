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
        // Buat query dasar dengan eager loading yang benar
        $query = Pengembalian::with(['peminjaman.user', 'peminjaman.barang']);
        
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
        
        // Filter berdasarkan status pengembalian
        if ($request->has('status_pengembalian') && $request->status_pengembalian) {
            $query->where('status', $request->status_pengembalian);
        }
        
        // Ambil data
        $pengembalians = $query->orderBy('created_at', 'desc')->get();
        
        // Debug untuk memeriksa data
       

        // Jika request meminta export Excel
        if ($request->has('export') && $request->export == 'excel') {
            return $this->exportPengembalianToExcel($pengembalians);
        }

        return view('admin.laporan.pengembalian', compact('pengembalians'));
    }

    private function exportPengembalianToExcel($pengembalians)
    {
        // Format data untuk export
        $data = $pengembalians->map(function ($pengembalian, $index) {
            $userName = $pengembalian->peminjaman && $pengembalian->peminjaman->user 
                ? $pengembalian->peminjaman->user->name 
                : 'Data User Tidak Ditemukan';
            
            $barangName = $pengembalian->peminjaman && $pengembalian->peminjaman->barang 
                ? $pengembalian->peminjaman->barang->nama_barang 
                : 'Data Barang Tidak Ditemukan';
            
            $tanggalPinjam = $pengembalian->peminjaman 
                ? $pengembalian->peminjaman->tanggal_pinjam 
                : '-';
            
            $alasanPinjam = $pengembalian->peminjaman 
                ? $pengembalian->peminjaman->alasan_pinjam 
                : '-';
            
            return [
                'No' => $index + 1,
                'Nama Peminjam' => $userName,
                'Nama Barang' => $barangName,
                'Alasan' => $alasanPinjam,
                'Jumlah' => $pengembalian->jumlah_kembali,
                'Tanggal Pinjam' => $tanggalPinjam,
                'Tanggal Kembali' => $pengembalian->tanggal_pengembalian,
                'Kondisi Barang' => ucfirst($pengembalian->kondisi_barang),
                'Biaya Denda' => 'Rp ' . number_format($pengembalian->biaya_denda, 0, ',', '.'),
                'Status' => ucfirst($pengembalian->status)
            ];
        });

        // Buat nama file dengan timestamp
        $fileName = 'Laporan_Pengembalian_' . \Carbon\Carbon::now()->format('d-m-Y_H-i-s') . '.xlsx';

        // Export ke Excel dan download
        return (new \Rap2hpoutre\FastExcel\FastExcel($data))->download($fileName);
    }
}

















