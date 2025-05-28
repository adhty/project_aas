<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\PeminjamanController;
use App\Http\Controllers\Api\PengembalianController;
use App\Http\Controllers\Api\ProfileController;

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);


    // Profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']);
    Route::post('/profile/photo', [ProfileController::class, 'uploadPhoto']);
    Route::post('/profile/password', [ProfileController::class, 'updatePassword']);
});

// Pengembalian (butuh login)
    Route::post('/pengembalian', [PengembalianController::class, 'store']);
    Route::put('/pengembalian/{id}', [PengembalianController::class, 'update']);


// Peminjaman (butuh login)
    Route::post('/peminjaman', [PeminjamanController::class, 'store']);
    Route::get('/peminjaman/user/{userId}/aktif', [PeminjamanController::class, 'index']);

// Pengembalian (public view)
Route::get('/pengembalian', [PengembalianController::class, 'index']);
Route::get('/pengembalian/{id}', [PengembalianController::class, 'show']);
Route::get('/pengembalian/user/{userId}', [PengembalianController::class, 'getRiwayatUser']);

// Barang (boleh public untuk read, butuh auth untuk write)
Route::get('/barang', [BarangController::class, 'index']);
Route::get('/barang/{id}', [BarangController::class, 'show']);
Route::get('/barang/{id}/stock', [BarangController::class, 'checkStock']);

// Barang management (butuh auth)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/barang', [BarangController::class, 'store']);
    Route::put('/barang/{id}', [BarangController::class, 'update']);
    Route::delete('/barang/{id}', [BarangController::class, 'destroy']);
});







