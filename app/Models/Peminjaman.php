<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans'; // nama tabel di database

    protected $fillable = [
        'user_id',         // jika kamu pakai relasi user
        'barang_id',       // foreign key ke barang
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
    ];

    public function user() {
    return $this->belongsTo(User::class);
}

public function barang() {
    return $this->belongsTo(Barang::class, 'barang_id');
}


}
