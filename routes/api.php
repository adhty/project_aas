<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\PeminjamanController;
use App\Http\Controllers\Api\PengembalianController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

    // Barang routes
    Route::get('/barang', [BarangController::class, 'index']);
    Route::get('/barang/{id}', [BarangController::class, 'show']);
    Route::get('api/peminjaman', [PeminjamanController::class, 'index']);
    Route::post('/peminjaman', [PeminjamanController::class, 'store']);

    // Pengembalian routes
Route::get('/pengembalian', [PengembalianController::class, 'index']);
Route::get('/peminjaman/user/{userId}/aktif', [PengembalianController::class, 'getPeminjamanAktif']);
Route::post('/pengembalian', [PengembalianController::class, 'store']);
Route::get('/pengembalian/{id}', [PengembalianController::class, 'show']);
Route::put('/pengembalian/{id}', [PengembalianController::class, 'update']);