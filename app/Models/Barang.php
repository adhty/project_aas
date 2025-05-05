<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nama',
        'jumlah_barang',
        'id_kategori',
    ];

    public function Kategori() {
        return $this->belongsTo(Kategori::class, 'id_kategori' );
    }

    public function stock()
    {
        return $this->hasOne(StockBarang::class, 'id_barang');
    }
    
}

