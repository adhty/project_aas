<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Konstruktor untuk middleware role
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Menampilkan dashboard admin
    public function index()
    {
        // Menghitung data untuk kartu
        $totalBarang = Barang::count();
        $totalKategori = Kategori::count();
        $totalPeminjaman = Peminjaman::count();
        $totalPengembalian = Pengembalian::count();
        
        // Mengambil data peminjaman untuk 30 hari terakhir
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();
        
        // Mendapatkan jumlah peminjaman harian untuk 30 hari terakhir
        $peminjamanData = Peminjaman::select(
            DB::raw('DATE(tanggal_pinjam) as tanggal'),
            DB::raw('COUNT(*) as jumlah')
        )
        ->where('tanggal_pinjam', '>=', $startDate)
        ->groupBy('tanggal')
        ->orderBy('tanggal')
        ->get();
        
        // Format data untuk grafik
        $peminjamanTanggal = [];
        $peminjamanJumlah = [];
        
        // Membuat array untuk semua tanggal dalam 30 hari terakhir
        $rentangTanggal = [];
        $tanggalSaatIni = clone $startDate;
        while ($tanggalSaatIni <= $endDate) {
            $rentangTanggal[$tanggalSaatIni->format('Y-m-d')] = 0;
            $tanggalSaatIni->addDay();
        }
        
        // Mengisi jumlah peminjaman aktual
        foreach ($peminjamanData as $data) {
            $rentangTanggal[$data->tanggal] = $data->jumlah;
        }
        
        // Konversi ke array untuk grafik
        foreach ($rentangTanggal as $tanggal => $jumlah) {
            $peminjamanTanggal[] = Carbon::parse($tanggal)->format('d M');
            $peminjamanJumlah[] = $jumlah;
        }
        
        // Mendapatkan barang terpopuler berdasarkan frekuensi peminjaman
        $barangPopuler = Peminjaman::select(
            'barang_id',
            DB::raw('COUNT(*) as jumlah')
        )
        ->with('barang:id,nama')
        ->groupBy('barang_id')
        ->orderByDesc('jumlah')
        ->limit(5)
        ->get();
        
        $namaBarangPopuler = [];
        $jumlahBarangPopuler = [];
        
        foreach ($barangPopuler as $item) {
            if ($item->barang) {
                $namaBarangPopuler[] = $item->barang->nama;
                $jumlahBarangPopuler[] = $item->jumlah;
            }
        }
        
        return view('admin.dashboard', compact(
            'totalBarang',
            'totalKategori',
            'totalPeminjaman',
            'totalPengembalian',
            'peminjamanTanggal',
            'peminjamanJumlah',
            'namaBarangPopuler',
            'jumlahBarangPopuler'
        ));
    }
}



