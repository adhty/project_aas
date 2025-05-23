<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengembalian;
use App\Models\Peminjaman;
use App\Models\StockBarang;
use Carbon\Carbon;

class PengembalianController extends Controller
{
    public function index()
    {
        $pengembalians = Pengembalian::with(['peminjaman.user', 'peminjaman.barang'])->get();
        return view('admin.pengembalian.index', compact('pengembalians'));
    }
    
    public function create()
    {
        $peminjamans = Peminjaman::where('status', 'disetujui')
            ->whereDoesntHave('pengembalian')
            ->with(['user', 'barang'])
            ->get();
        return view('admin.pengembalian.create', compact('peminjamans'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjamans,id',
            'tanggal_pengembalian' => 'required|date',
            'kondisi_barang' => 'required|in:baik,rusak,hilang',
            'catatan' => 'nullable|string',
            'nama_pengembali' => 'required|string|max:255',
            'jumlah_kembali' => 'required|integer|min:1',
        ]);
        
        // Cek apakah peminjaman sudah ada pengembalian
        $existingPengembalian = Pengembalian::where('peminjaman_id', $request->peminjaman_id)->first();
        if ($existingPengembalian) {
            // Jika pengembalian sudah ada tapi statusnya ditolak, kita bisa update atau buat baru
            if ($existingPengembalian->status === 'ditolak') {
                // Hapus pengembalian yang ditolak
                $existingPengembalian->delete();
            } else {
                return redirect()->back()->with('error', 'Peminjaman ini sudah memiliki pengembalian')->withInput();
            }
        }
        
        // Ambil data peminjaman
        $peminjaman = Peminjaman::with('barang')->findOrFail($request->peminjaman_id);
        
        // Cek jumlah pengembalian tidak melebihi jumlah peminjaman
        if ($request->jumlah_kembali > $peminjaman->jumlah) {
            return redirect()->back()->with('error', 'Jumlah pengembalian tidak boleh melebihi jumlah peminjaman')->withInput();
        }
        
        // Hitung denda secara otomatis
        $biaya_denda = 0;
        
        // 1. Cek keterlambatan pengembalian
        $tanggal_kembali_seharusnya = Carbon::parse($peminjaman->tanggal_kembali);
        $tanggal_pengembalian_aktual = Carbon::parse($request->tanggal_pengembalian);
        
        if ($tanggal_pengembalian_aktual->gt($tanggal_kembali_seharusnya)) {
            // Hitung selisih hari
            $selisih_hari = $tanggal_pengembalian_aktual->diffInDays($tanggal_kembali_seharusnya);
            
            // Denda keterlambatan: 10000 per hari per barang
            $denda_terlambat = $selisih_hari * 10000 * $request->jumlah_kembali;
            $biaya_denda += $denda_terlambat;
        }
        
        // 2. Cek kondisi barang rusak atau hilang
        if ($request->kondisi_barang !== 'baik') {
            // Ambil harga barang
            $harga_barang = $peminjaman->barang->harga ?? 0;
            
            if ($request->kondisi_barang === 'rusak') {
                // Denda untuk barang rusak (50% dari harga barang)
                $denda_kondisi = $harga_barang * 0.5 * $request->jumlah_kembali;
                $biaya_denda += $denda_kondisi;
            } elseif ($request->kondisi_barang === 'hilang') {
                // Denda untuk barang hilang (100% dari harga barang)
                $denda_kondisi = $harga_barang * $request->jumlah_kembali;
                $biaya_denda += $denda_kondisi;
            }
        }
        
        // Buat catatan pengembalian
        Pengembalian::create([
            'peminjaman_id' => $request->peminjaman_id,
            'tanggal_pengembalian' => $request->tanggal_pengembalian,
            'kondisi_barang' => $request->kondisi_barang,
            'catatan' => $request->catatan,
            'status' => 'menunggu',
            'nama_pengembali' => $request->nama_pengembali,
            'jumlah_kembali' => $request->jumlah_kembali,
            'biaya_denda' => $biaya_denda,
        ]);
        
        return redirect()->route('admin.pengembalian.index')
            ->with('success', 'Permintaan pengembalian berhasil dibuat.');
    }
    
    public function show($id)
    {
        $pengembalian = Pengembalian::with(['peminjaman.user', 'peminjaman.barang'])->findOrFail($id);
        return view('admin.pengembalian.show', compact('pengembalian'));
    }
    
    public function approve($id)
    {
        $pengembalian = Pengembalian::with('peminjaman.barang')->findOrFail($id);
        $pengembalian->status = 'diterima';
        $pengembalian->save();
        
        // Update status peminjaman menjadi dikembalikan
        $peminjaman = $pengembalian->peminjaman;
        $peminjaman->status = 'dikembalikan';
        $peminjaman->save();
        
        // Update stok barang hanya jika kondisi baik
        if ($pengembalian->kondisi_barang == 'baik') {
            $stock = \App\Models\StockBarang::where('barang_id', $peminjaman->barang_id)->first();
            if ($stock) {
                $stock->jumlah += $pengembalian->jumlah_kembali;
                $stock->save();
            }
        }
        
        return redirect()->route('admin.pengembalian.index')
            ->with('success', 'Pengembalian berhasil disetujui.');
    }
    
    public function reject($id)
    {
        $pengembalian = Pengembalian::findOrFail($id);
        $pengembalian->status = 'ditolak';
        $pengembalian->save();
        
        return redirect()->route('admin.pengembalian.index')
            ->with('success', 'Pengembalian ditolak.');
    }
}






