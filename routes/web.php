<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ManajemenUserController;
use App\Http\Controllers\MejaController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PendapatanController;
use App\Http\Controllers\PesananController;
use Illuminate\Support\Facades\Route;

// ============================================
// AUTH & LOGIN
// ============================================
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    // ============================================
    // DASHBOARD & HOME
    // ============================================
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // ============================================
    // RESOURCE ROUTES (CRUD dengan custom index route)
    // ============================================

    // Kategori Menu
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori');
    Route::resource('kategori', KategoriController::class)->except(['index']);

    // Menu (Stock Menu)
    Route::get('/stock-menu', [MenuController::class, 'index'])->name('stock-menu');
    Route::resource('menu', MenuController::class)->except(['index']);

    // Meja
    Route::get('/meja', [MejaController::class, 'index'])->name('meja');
    Route::resource('mejas', MejaController::class)->except(['index']);

    // Pesanan
    Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan');
    Route::resource('pesanan', PesananController::class)->except(['index']);

    // Pembayaran
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran');
    Route::resource('pembayaran', PembayaranController::class)->except(['index']);

    // User Management
    Route::get('/manajemen-user', [ManajemenUserController::class, 'index'])->name('manajemen-user');
    Route::resource('users', ManajemenUserController::class)->except(['index']);

    // Karyawan
    Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan');
    Route::resource('karyawan', KaryawanController::class)->except(['index']);

    // Reservasi (custom routes since it's related to Meja)
    Route::post('/reservasi', [MejaController::class, 'storeReservasi'])->name('reservasi.store');
    Route::put('/reservasi/{reservasi}', [MejaController::class, 'updateReservasi'])->name('reservasi.update');
    Route::delete('/reservasi/{reservasi}', [MejaController::class, 'destroyReservasi'])->name('reservasi.destroy');

    // ============================================
    // ANALITIK & LAPORAN
    // ============================================
    Route::get('/pendapatan', [PendapatanController::class, 'index'])->name('pendapatan');
    Route::get('/history', [HistoryController::class, 'index'])->name('history');
    Route::get('/invoice/{id}/download', [InvoiceController::class, 'download'])->name('invoice.download');
});
