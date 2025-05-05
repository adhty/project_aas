<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;

use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\StockBarangController;
use App\Http\Controllers\Web\StokBarangController;
use App\Http\Controllers\PeminjamanController; // Import
use App\Http\Controllers\PengembalianController; // Import

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard route
Route::middleware(['auth'])->prefix('admin')->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Kategori
    Route::get('kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('kategori/{kategori}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');

    // Barang
    Route::get('barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('barang', [BarangController::class, 'store'])->name('barang.store');
    Route::get('barang/{barang}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::delete('barang/{barang}', [BarangController::class, 'destroy'])->name('barang.destroy'); 
    Route::put('barang/{barang}', [BarangController::class, 'update'])->name('barang.update');

    //stock
        Route::get('/', [StockBarangController::class, 'index'])->name('stock.index');
        Route::get('/create', [StockBarangController::class, 'create'])->name('stock.create');
        Route::post('/store', [StockBarangController::class, 'store'])->name('stock.store');
        Route::get('/edit/{id}', [StockBarangController::class, 'edit'])->name('stock.edit');
        Route::put('/update/{id}', [StockBarangController::class, 'update'])->name('stock.update');
        Route::delete('/destroy/{id}', [StockBarangController::class, 'destroy'])->name('stock.destroy');

});

Route::get('/user', function () {
    return ('user');
})->middleware('auth', 'role:user');