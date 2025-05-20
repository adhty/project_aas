<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Peminjaman;

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

    public function pengembalian()
    {
        $pengembalians = Peminjaman::with(['user', 'barang'])
            ->where('status', 'kembali')
            ->get();

        return view('laporan.pengembalian', compact('pengembalians'));
    }
}
