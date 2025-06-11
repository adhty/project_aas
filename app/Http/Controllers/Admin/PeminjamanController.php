<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Barang;
use App\Models\StockBarang;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['user', 'barang'])->get();
        return view('admin.peminjaman.index', compact('peminjamans'));
    }

    public function create()
    {
        $users = User::all();
        $barangs = Barang::all();
        return view('admin.peminjaman.create', compact('users', 'barangs'));
    }

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

        // Tidak mengurangi stok saat permintaan dibuat, hanya saat disetujui
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

    public function edit($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $users = User::all();
        $barangs = Barang::all();
        return view('admin.peminjaman.edit', compact('peminjaman', 'users', 'barangs'));
    }

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
        $oldStatus = $peminjaman->status;

        $peminjaman->update([
            'user_id' => $request->user_id,
            'barang_id' => $request->barang_id,
            'alasan_pinjam' => $request->alasan_pinjam,
            'jumlah' => $request->jumlah,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'status' => $request->status,
        ]);

        $barang = Barang::where('id', $request->barang_id)->first();

        // Jika baru disetujui, kurangi stok
        if ($oldStatus !== 'disetujui' && $request->status === 'disetujui') {
            if (!$barang || $barang->jumlah < $request->jumlah) {
                $peminjaman->update(['status' => $oldStatus]); // rollback
                return redirect()->back()->with('error', 'Stok tidak mencukupi untuk menyetujui peminjaman.');
            }
            $barang->jumlah -= $request->jumlah;
            $barang->save();
        }

        // Jika sebelumnya disetujui lalu ditolak/dikembalikan → kembalikan stok
        if ($oldStatus === 'disetujui' && in_array($request->status, ['ditolak', 'dikembalikan'])) {
            if ($barang) {
                $barang->jumlah += $request->jumlah;
                $barang->save();
            }
        }

        return redirect()->route('admin.peminjaman.index')->with('success', 'Data peminjaman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // Jika status disetujui, kembalikan stok sebelum dihapus
        if ($peminjaman->status === 'disetujui') {
            $barang = barang::where('barang_id', $peminjaman->barang_id)->first();
            if ($barang) {
                $barang->jumlah += $peminjaman->jumlah;
                $barang->save();
            }
        }

        $peminjaman->delete();

        return redirect()->route('admin.peminjaman.index')->with('success', 'Data peminjaman berhasil dihapus.');
    }

    public function approve($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'menunggu') {
            return redirect()->back()->with('error', 'Peminjaman ini sudah diproses.');
        }

        $barang = barang::where('id', $peminjaman->barang_id)->first();

        if (!$barang || $barang->jumlah_barang < $peminjaman->jumlah) {
            return redirect()->back()->with('error', 'Stok barang tidak mencukupi untuk dipinjam');
        }

        $barang->jumlah_barang -= $peminjaman->jumlah;
        $barang->save();

        $peminjaman->status = 'disetujui';
        $peminjaman->save();

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman disetujui.');
    }

    public function reject($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status === 'disetujui') {
            $stockBarang = StockBarang::where('barang_id', $peminjaman->barang_id)->first();
            if ($stockBarang) {
                $stockBarang->jumlah += $peminjaman->jumlah;
                $stockBarang->save();
            }
        }

        $peminjaman->status = 'ditolak';
        $peminjaman->save();

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman ditolak.');
    }
}

