<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockBarang extends Model
{
    use HasFactory;

    protected $table = 'stock_barang';

    // Perbaikan disini: ubah id_barang jadi barang_id agar sesuai dengan nama kolom database
    protected $fillable = ['barang_id', 'jumlah'];

    //Perbaikan relasi: gunakan default foreign key 'barang_id'
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
