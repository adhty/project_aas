<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\PeminjamanController;
use App\Http\Controllers\Api\PengembalianController;

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    

    // Pengembalian
});
Route::post('/pengembalian', [PengembalianController::class, 'store']);
Route::put('/pengembalian/{id}', [PengembalianController::class, 'update']);
Route::get('/pengembalian/user/{userId}', [PengembalianController::class, 'getRiwayatUser']); // jika ada

Route::get('/pengembalian', [PengembalianController::class, 'index']);
Route::get('/pengembalian/{id}', [PengembalianController::class, 'show']);
// Peminjaman
    Route::post('/peminjaman', [PeminjamanController::class, 'store']);
    Route::get('/peminjaman/user/{userId}/aktif', [PengembalianController::class, 'index']);

// Barang (boleh public atau dipindah ke group juga kalau perlu login)
Route::get('/barang', [BarangController::class, 'index']);
Route::get('/barang/{id}', [BarangController::class, 'show']);
