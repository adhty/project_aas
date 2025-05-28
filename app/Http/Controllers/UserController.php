<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Peminjaman;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the user dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $peminjaman_aktif = Peminjaman::where('user_id', $user->id)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->with('barang')
            ->get();
            
        $peminjaman_selesai = Peminjaman::where('user_id', $user->id)
            ->whereIn('status', ['dikembalikan', 'ditolak'])
            ->with('barang')
            ->get();
            
        $barang_tersedia = Barang::where('jumlah_barang', '>', 0)->get();
        
        return view('user.index', compact('user', 'peminjaman_aktif', 'peminjaman_selesai', 'barang_tersedia'));
    }
    
    /**
     * Show the form for creating a new peminjaman.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function createPeminjaman()
    {
        $barangs = Barang::where('jumlah_barang', '>', 0)->get();
        return view('user.peminjaman.create', compact('barangs'));
    }
    
    /**
     * Store a newly created peminjaman in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storePeminjaman(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'alasan_pinjam' => 'required|string|max:1000',
        ]);
        
        $barang = Barang::find($request->barang_id);
        
        // Cek ketersediaan stok
        if ($barang->jumlah_barang < $request->jumlah) {
            return back()->with('error', 'Jumlah barang yang tersedia tidak mencukupi.');
        }
        
        // Buat peminjaman baru
        $peminjaman = Peminjaman::create([
            'user_id' => Auth::id(),
            'barang_id' => $request->barang_id,
            'jumlah' => $request->jumlah,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'alasan_pinjam' => $request->alasan_pinjam,
            'status' => 'menunggu',
        ]);
        
        return redirect()->route('user.index')->with('success', 'Permintaan peminjaman berhasil dibuat dan sedang menunggu persetujuan.');
    }
    
    /**
     * Show the user profile.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }
    
    /**
     * Update the user profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        User::find(Auth::id())->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->filled('password') ? bcrypt($request->password) : Auth::user()->password,
        ]);
        
        return redirect()->route('user.profile')->with('success', 'Profil berhasil diperbarui.');
    }
    
    /**
     * Show the detail of a peminjaman.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showPeminjaman($id)
    {
        $peminjaman = Peminjaman::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['barang'])
            ->firstOrFail();
            
        return view('user.peminjaman.show', compact('peminjaman'));
    }
    
    /**
     * Cancel a peminjaman request.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancelPeminjaman($id)
    {
        $peminjaman = Peminjaman::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'menunggu')
            ->firstOrFail();
            
        $peminjaman->status = 'ditolak';
        $peminjaman->save();
        
        return redirect()->route('user.index')->with('success', 'Permintaan peminjaman berhasil dibatalkan.');
    }
}

