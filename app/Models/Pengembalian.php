<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;
    protected $table = 'pengembalians';
    protected $fillable = [
        'peminjaman_id',
        'tanggal_pengembalian',
        'kondisi_barang',
        'catatan',
        'status',
        'nama_pengembali',
        'jumlah_kembali',
        'biaya_denda',
        'detail_denda'
    ];
    
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }
    
    // Relasi langsung ke user dan barang melalui peminjaman
    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            Peminjaman::class,
            'id', // Foreign key di peminjaman
            'id', // Foreign key di user
            'peminjaman_id', // Local key di pengembalian
            'user_id' // Local key di peminjaman
        );
    }
    
    public function barang()
    {
        return $this->hasOneThrough(
            Barang::class,
            Peminjaman::class,
            'id', // Foreign key di peminjaman
            'id', // Foreign key di barang
            'peminjaman_id', // Local key di pengembalian
            'barang_id' // Local key di peminjaman
        );
    }
}





