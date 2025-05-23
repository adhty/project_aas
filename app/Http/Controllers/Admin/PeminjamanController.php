<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Barang;

class PeminjamanController extends Controller
{
    // Tampilkan semua data peminjaman
    public function index()
    {
        $peminjamans = Peminjaman::with(['user', 'barang'])->get();
        return view('admin.peminjaman.index', compact('peminjamans'));
    }

    // Tampilkan form tambah peminjaman
    public function create()
    {
        $users = User::all();
        $barangs = Barang::all();
        return view('admin.peminjaman.create', compact('users', 'barangs'));
    }

    // Simpan data peminjaman baru
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'barang_id' => 'required|exists:barangs,id',
            'alasan_pinjam' => 'nullable|string|max:1000',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
        ]);

        // Cek ketersediaan stok
        $stockBarang = \App\Models\StockBarang::where('barang_id', $request->barang_id)->first();
        
        if (!$stockBarang || $stockBarang->jumlah < $request->jumlah) {
            return redirect()->back()->with('error', 'Stok barang tidak mencukupi untuk dipinjam')->withInput();
        }

        Peminjaman::create([
            'user_id' => $request->user_id,
            'barang_id' => $request->barang_id,
            'alasan_pinjam' => $request->alasan_pinjam,
            'jumlah' => $request->jumlah,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'status' => 'menunggu',
        ]);

        return redirect()->route('admin.peminjaman.index')->with('success', 'Data peminjaman berhasil ditambahkan.');
    }

    // Tampilkan form edit peminjaman
    public function edit($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $users = User::all();
        $barangs = Barang::all();
        return view('admin.peminjaman.edit', compact('peminjaman', 'users', 'barangs'));
    }

    // Update data peminjaman
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'barang_id' => 'required|exists:barangs,id',
            'alasan_pinjam' => 'nullable|string|max:1000',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'status' => 'required|in:menunggu,disetujui,ditolak,dikembalikan'
        ]);

        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'user_id' => $request->user_id,
            'barang_id' => $request->barang_id,
            'alasan_pinjam' => $request->alasan_pinjam,
            'jumlah' => $request->jumlah,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.peminjaman.index')->with('success', 'Data peminjaman berhasil diperbarui.');
    }

    // Hapus data peminjaman
    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->delete();

        return redirect()->route('admin.peminjaman.index')->with('success', 'Data peminjaman berhasil dihapus.');
    }

    // Setujui peminjaman
    public function approve($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        // Cek ketersediaan stok
        $stockBarang = \App\Models\StockBarang::where('barang_id', $peminjaman->barang_id)->first();
        
        if (!$stockBarang || $stockBarang->jumlah < $peminjaman->jumlah) {
            return redirect()->back()->with('error', 'Stok barang tidak mencukupi untuk dipinjam');
        }
        
        // Kurangi stok barang
        $stockBarang->jumlah -= $peminjaman->jumlah;
        $stockBarang->save();
        
        $peminjaman->status = 'disetujui';
        $peminjaman->save();

        return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman disetujui.');
    }

    // Tolak peminjaman
    public function reject($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->status = 'ditolak';
        $peminjaman->save();

        return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman ditolak.');
    }
}


