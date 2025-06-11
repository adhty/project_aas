<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $table = 'barangs';
    protected $fillable = [
        'nama',
        'jumlah_barang',
        'id_kategori',
        'foto'

    ];

    protected $appends = ['foto_url'];

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'barang_id');
    }

    public function kategori() {
        return $this->belongsTo(Kategori::class, 'id_kategori' );
    }

    public function stock()
    {
        return $this->hasOne(StockBarang::class, 'barang_id');
    }

    

    // Accessor untuk URL foto
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }
        return null;
    }

}


