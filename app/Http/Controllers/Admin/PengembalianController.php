<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengembalian;
use App\Models\Peminjaman;
use App\Models\Barang;
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
            'alasan_pinjam' => 'nullable|string|max:1000',
            'kondisi_barang' => 'required|in:baik,rusak,hilang',
            'catatan' => 'nullable|string',
            'jumlah_kembali' => 'required|integer|min:1',
            'biaya_denda_manual' => 'nullable|numeric|min:0', // Tambahkan validasi untuk denda manual
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
            // Jika admin menentukan denda manual untuk barang rusak/hilang
            if ($request->has('biaya_denda_manual') && $request->biaya_denda_manual > 0) {
                $biaya_denda += $request->biaya_denda_manual;
            } else {
                // Gunakan perhitungan otomatis jika tidak ada input denda manual
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
        }

        // Buat catatan pengembalian
        Pengembalian::create([
            'peminjaman_id' => $request->peminjaman_id,
            'tanggal_pengembalian' => $request->tanggal_pengembalian,
            'kondisi_barang' => $request->kondisi_barang,
            'alasan_pinjam' => $request->alasan_pinjam,
            'catatan' => $request->catatan,
            'status' => 'menunggu',
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

        // Pastikan pengembalian belum disetujui sebelumnya
        if ($pengembalian->status !== 'menunggu') {
            return redirect()->back()->with('error', 'Pengembalian ini sudah diproses sebelumnya.');
        }

        // Update status pengembalian menjadi diterima
        $pengembalian->status = 'diterima';

        // Pastikan biaya denda sudah dihitung dengan benar
        $peminjaman = $pengembalian->peminjaman;
        $harga_barang = $peminjaman->barang->harga ?? 0;

        // Cek kondisi barang dan hitung denda jika rusak atau hilang
        if ($pengembalian->kondisi_barang === 'rusak') {
            // Denda untuk barang rusak (50% dari harga barang)
            $denda_kondisi = $harga_barang * 0.5 * $pengembalian->jumlah_kembali;
            $pengembalian->biaya_denda = $pengembalian->biaya_denda + $denda_kondisi;
        } elseif ($pengembalian->kondisi_barang === 'hilang') {
            // Denda untuk barang hilang (100% dari harga barang)
            $denda_kondisi = $harga_barang * $pengembalian->jumlah_kembali;
            $pengembalian->biaya_denda = $pengembalian->biaya_denda + $denda_kondisi;
        }

        $pengembalian->save();

        // Update status peminjaman menjadi dikembalikan
        $peminjaman->status = 'dikembalikan';
        $peminjaman->save();

        // Update stok barang hanya jika kondisi baik
        if (trim(strtolower($pengembalian->kondisi_barang)) === 'baik') {
            $barang = $peminjaman->barang;
            if ($barang) {
                $barang->jumlah_barang += $pengembalian->jumlah_kembali;
                $barang->save();
            }
        }


        return redirect()->route('admin.pengembalian.index')
            ->with('success', 'Pengembalian berhasil disetujui dengan biaya denda Rp ' . number_format($pengembalian->biaya_denda, 0, ',', '.'));
    }

    public function reject($id)
    {
        $pengembalian = Pengembalian::findOrFail($id);

        // Pastikan pengembalian belum diproses sebelumnya
        if ($pengembalian->status !== 'menunggu') {
            return redirect()->back()->with('error', 'Pengembalian ini sudah diproses sebelumnya.');
        }

        // Redirect ke form input denda manual
        return view('admin.pengembalian.reject', compact('pengembalian'));
    }

    public function processReject(Request $request, $id)
    {
        $request->validate([
            'biaya_denda_manual' => 'required|numeric|min:0',
            'alasan_penolakan' => 'required|string|max:1000',
        ]);

        $pengembalian = Pengembalian::with('peminjaman')->findOrFail($id);

        // Pastikan pengembalian belum diproses sebelumnya
        if ($pengembalian->status !== 'menunggu') {
            return redirect()->back()->with('error', 'Pengembalian ini sudah diproses sebelumnya.');
        }

        // Update status pengembalian menjadi ditolak dengan denda manual
        $pengembalian->status = 'ditolak';
        $pengembalian->biaya_denda = $request->biaya_denda_manual;
        
        // Simpan detail denda dalam format JSON
        $detail_denda = [
            'keterlambatan' => 0,
            'kondisi_barang' => 0,
            'denda_manual' => $request->biaya_denda_manual,
            'alasan_penolakan' => $request->alasan_penolakan,
            'total' => $request->biaya_denda_manual,
            'is_manual' => true
        ];
        
        $pengembalian->detail_denda = json_encode($detail_denda);
        $pengembalian->catatan = $pengembalian->catatan . "\n\nAlasan penolakan: " . $request->alasan_penolakan;
        $pengembalian->save();

        // Jika pengembalian ditolak, status peminjaman tetap 'disetujui'
        // sehingga peminjam masih bertanggung jawab atas barang tersebut

        return redirect()->route('admin.pengembalian.index')
            ->with('success', 'Pengembalian ditolak dengan denda Rp ' . number_format($request->biaya_denda_manual, 0, ',', '.'));
    }
}





