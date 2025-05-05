<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockBarang extends Model
{
    use HasFactory;

    protected $fillable = ['id_barang', 'jumlah'];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
