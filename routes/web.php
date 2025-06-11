<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\StockBarangController;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Http\Controllers\Admin\PengembalianController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Web\StokBarangController; // Belum dipakai

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes (Prefix: /admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Kategori
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{kategori}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

    // Barang
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::get('/barang/{barang}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{barang}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{barang}', [BarangController::class, 'destroy'])->name('barang.destroy');

    // Stock Barang
    Route::get('/stock', [StockBarangController::class, 'index'])->name('stock.index');
    Route::get('/stock/create', [StockBarangController::class, 'create'])->name('stock.create');
    Route::post('/stock/store', [StockBarangController::class, 'store'])->name('stock.store');
    Route::get('/stock/edit/{id}', [StockBarangController::class, 'edit'])->name('stock.edit');
    Route::put('/stock/update/{id}', [StockBarangController::class, 'update'])->name('stock.update');
    Route::delete('/stock/destroy/{id}', [StockBarangController::class, 'destroy'])->name('stock.destroy');

    // Peminjaman
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::post('/peminjaman/{peminjaman}/kembali', [PeminjamanController::class, 'kembali'])->name('peminjaman.kembali');
    Route::post('/peminjaman/{peminjaman}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::post('/peminjaman/{peminjaman}/reject', [PeminjamanController::class, 'reject'])->name('peminjaman.reject');

    // Pengembalian
    Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('admin.pengembalian.index');
    Route::get('/pengembalian/create', [PengembalianController::class, 'create'])->name('admin.pengembalian.create');
    Route::post('/pengembalian', [PengembalianController::class, 'store'])->name('admin.pengembalian.store');
    Route::get('/pengembalian/{id}', [PengembalianController::class, 'show'])->name('admin.pengembalian.show');
    Route::post('/pengembalian/{id}/approve', [PengembalianController::class, 'approve'])->name('admin.pengembalian.approve');
    Route::get('/pengembalian/{id}/reject', [PengembalianController::class, 'reject'])->name('admin.pengembalian.reject');
    Route::post('/pengembalian/{id}/process-reject', [PengembalianController::class, 'processReject'])->name('admin.pengembalian.process-reject');
    Route::put('/pengembalian/{id}/update-denda', [PengembalianController::class, 'updateDenda'])->name('admin.pengembalian.update-denda');
  

    // Laporan
    Route::get('/laporan/barang', [LaporanController::class, 'barang'])->name('laporan.barang');
    Route::get('/laporan/peminjaman', [LaporanController::class, 'peminjaman'])->name('laporan.peminjaman');
    Route::get('/laporan/pengembalian', [LaporanController::class, 'pengembalian'])->name('laporan.pengembalian');

    // User Management Routes
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.users.create');
    Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.users.store');
});

/*
|--------------------------------------------------------------------------
| User Role Route
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user', [App\Http\Controllers\UserController::class, 'index'])->name('user.dashboard');
});

// route buar export excel
Route::get('/admin/laporan/peminjaman', [App\Http\Controllers\LaporanController::class, 'peminjaman'])->name('laporan.peminjaman');

// Tambahkan routes untuk profile
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.update.password');
});











