<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StokController;
use App\Http\Controllers\Admin\ScanController;
use App\Http\Controllers\Admin\DendaController;
use App\Http\Controllers\Admin\LaporanController;

// ==========================================
// ROUTE AUTH (tanpa middleware)
// ==========================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.proses');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.proses');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// ROUTE USER (perlu login)
// ==========================================
Route::middleware(['cek.login'])->group(function () {

    // Beranda
    Route::get('/', [UserController::class, 'home'])->name('home');

    // Katalog
    Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog');
    Route::get('/katalog/{id}', [KatalogController::class, 'detail'])->name('katalog.detail');

    // Keranjang & Checkout
    Route::post('/keranjang/tambah', [ReservasiController::class, 'tambahKeranjang'])->name('keranjang.tambah');
    Route::get('/keranjang', [ReservasiController::class, 'keranjang'])->name('keranjang');
    Route::delete('/keranjang/{index}', [ReservasiController::class, 'hapusDariKeranjang'])->name('keranjang.hapus');
    Route::get('/checkout', [ReservasiController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [ReservasiController::class, 'prosesCheckout'])->name('checkout.proses');

    // Pembayaran
    Route::get('/pembayaran/{reservation_id}', [PembayaranController::class, 'show'])->name('pembayaran');
    Route::post('/pembayaran/{payment_id}/konfirmasi', [PembayaranController::class, 'konfirmasiManual'])->name('pembayaran.konfirmasi');
    Route::get('/etiket/{reservation_id}', [PembayaranController::class, 'etiket'])->name('etiket');

    // Bayar Denda
    Route::get('/bayar-denda/{reservation_id}', [PembayaranController::class, 'showBayarDenda'])->name('bayar.denda');
    Route::post('/bayar-denda/{payment_id}/konfirmasi', [PembayaranController::class, 'konfirmasiBayarDenda'])->name('bayar.denda.konfirmasi');

    // Transaksi
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/{id}', [TransaksiController::class, 'detail'])->name('transaksi.detail');

    // Notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi');

    // Profil
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
    Route::post('/profil', [ProfilController::class, 'update'])->name('profil.update');
});

// ==========================================
// ROUTE ADMIN (perlu login + role admin)
// ==========================================
Route::middleware(['cek.login', 'cek.admin'])->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Stok Barang
    Route::get('/stok', [StokController::class, 'index'])->name('admin.stok.index');
    Route::get('/stok/tambah', [StokController::class, 'create'])->name('admin.stok.create');
    Route::post('/stok', [StokController::class, 'store'])->name('admin.stok.store');
    Route::get('/stok/{id}/edit', [StokController::class, 'edit'])->name('admin.stok.edit');
    Route::put('/stok/{id}', [StokController::class, 'update'])->name('admin.stok.update');
    Route::delete('/stok/{id}', [StokController::class, 'destroy'])->name('admin.stok.destroy');

    // Scan Pickup & Return
    Route::get('/scan/pickup', [ScanController::class, 'showPickup'])->name('admin.scan.pickup');
    Route::post('/scan/pickup', [ScanController::class, 'prosesPickup'])->name('admin.scan.pickup.proses');
    Route::post('/scan/pickup/{id}/konfirmasi', [ScanController::class, 'konfirmasiPickup'])->name('admin.scan.pickup.konfirmasi');
    Route::get('/scan/return', [ScanController::class, 'showReturn'])->name('admin.scan.return');
    Route::post('/scan/return', [ScanController::class, 'prosesReturn'])->name('admin.scan.return.proses');

    // Inspeksi
    Route::get('/inspeksi/{id}', [ScanController::class, 'inspeksi'])->name('admin.inspeksi');
    Route::post('/inspeksi/{id}', [ScanController::class, 'prosesInspeksi'])->name('admin.inspeksi.proses');

    // Denda
    Route::get('/denda/{reservation_id}', [DendaController::class, 'inputForm'])->name('admin.denda.input');
    Route::post('/denda/{reservation_id}', [DendaController::class, 'simpanDenda'])->name('admin.denda.simpan');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('admin.laporan');
    Route::get('/laporan/cetak-pdf', [LaporanController::class, 'cetakPdf'])->name('admin.laporan.pdf');
});
