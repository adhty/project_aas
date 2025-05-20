<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $fillable = ['nama'];
    
    /**
     * Get the barangs for the kategori.
     */
    public function barangs()
    {
        return $this->hasMany(Barang::class, 'id_kategori');
    }
}

