<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\PeminjamanController;
use App\Http\Controllers\Api\PengembalianController;

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    

    // Pengembalian (butuh login)
    Route::post('/pengembalian', [PengembalianController::class, 'store']);
    Route::put('/pengembalian/{id}', [PengembalianController::class, 'update']);
});

// Peminjaman (butuh login)
    Route::post('/peminjaman', [PeminjamanController::class, 'store']);
    Route::get('/peminjaman/user/{userId}/aktif', [PeminjamanController::class, 'getPeminjamanAktifByUser']);
    
// Pengembalian (public view)
Route::get('/pengembalian', [PengembalianController::class, 'index']);
Route::get('/pengembalian/{id}', [PengembalianController::class, 'show']);
Route::get('/pengembalian/user/{userId}', [PengembalianController::class, 'getRiwayatUser']);

// Barang (boleh public)
Route::get('/barang', [BarangController::class, 'index']);
Route::get('/barang/{id}', [BarangController::class, 'show']);
Route::get('/barang/{id}/stock', [BarangController::class, 'checkStock']);

