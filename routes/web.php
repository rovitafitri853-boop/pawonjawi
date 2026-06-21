<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\MejaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\AdminDashboardController;

// Import Kasir Controllers
use App\Http\Controllers\Kasir\KasirDashboardController;
use App\Http\Controllers\Kasir\TransaksiController as KasirTransaksi; // Alias untuk Kasir
use App\Http\Controllers\Kasir\RiwayatController;

// Rute Publik
Route::get('/', function () { return view('welcome'); });
Route::get('/dashboard', function () { return view('dashboard'); })->middleware(['auth', 'verified'])->name('dashboard');

// Rute Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rute Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('kategori', KategoriController::class);
    Route::post('/menu/check', [MenuController::class, 'check'])->name('menu.check');
    Route::resource('menu', MenuController::class);
    Route::resource('meja', MejaController::class);
    Route::resource('user', UserController::class);
    
    
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
    Route::get('/laporan/download', [LaporanController::class, 'download'])->name('laporan.download');
    
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
});

// Rute Kasir
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/dashboard', [KasirDashboardController::class, 'index'])->name('dashboard');
    
   Route::get('/pos/{id?}', [KasirTransaksi::class, 'index'])->name('pos');
    
    // ALIAS: Agar error [kasir.pos.input] hilang
    Route::get('/pos/input/{id?}', [KasirTransaksi::class, 'index'])->name('pos.input');
    
    Route::get('/pos/edit/{id}', [KasirTransaksi::class, 'edit'])->name('pos.edit');
    Route::post('/transaksi/store', [KasirTransaksi::class, 'store'])->name('transaksi.store');
    
    // Aksi Keranjang
    Route::post('/transaksi/update/{id}', [KasirTransaksi::class, 'updateItem'])->name('updateItem');
    // Tambahkan baris ini di dalam grup 'kasir'
Route::put('/transaksi/update/{id}', [KasirTransaksi::class, 'update'])->name('transaksi.update');
    Route::delete('/transaksi/destroy/{id}', [KasirTransaksi::class, 'destroyItem'])->name('destroyItem');
    
    // DIPERBAIKI: Menggunakan GET agar link <a> bisa mengaksesnya
    Route::get('/transaksi/batal/{id}', [KasirTransaksi::class, 'batal'])->name('batal');
    
    // Simpan Pending
    Route::post('/transaksi/simpan-pending', [KasirTransaksi::class, 'simpanPending'])->name('simpanPending');
    
    // Pembayaran & Riwayat
    Route::post('/transaksi/bayar/{id}', [KasirTransaksi::class, 'bayar'])->name('bayar');
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat');

    // Rute Pembayaran Baru
    Route::get('/pembayaran/{id}', [KasirTransaksi::class, 'halamanPembayaran'])->name('pembayaran.detail');
    Route::get('/kasir/pembayaran/detail/{id}', [KasirTransaksi::class, 'detailPembayaran'])->name('kasir.pembayaran.detail');
    Route::post('/proses-bayar/{id}', [KasirTransaksi::class, 'prosesBayar'])->name('proses.bayar');
});

require __DIR__.'/auth.php';