<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // opsional jika relasi ke user
            $table->foreignId('barang_id')->constrained('barang')->onDelete('cascade'); // relasi ke barang
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali')->nullable(); // boleh kosong jika belum dikembalikan
            $table->string('status')->default('menunggu'); // status: menunggu, disetujui, ditolak, dll
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
