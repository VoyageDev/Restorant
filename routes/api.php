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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('auth/login', [AuthController::class, 'authenticate']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me', function (Request $request) {
            return response()->json([
                'data' => $request->user(),
            ]);
        });

        Route::get('dashboard', [DashboardController::class, 'index']);
        Route::get('history', [HistoryController::class, 'index']);
        Route::get('pendapatan', [PendapatanController::class, 'index']);
        Route::get('invoice/{id}/download', [InvoiceController::class, 'download']);

        Route::apiResource('kategori', KategoriController::class);
        Route::apiResource('menu', MenuController::class);
        Route::apiResource('meja', MejaController::class);
        Route::apiResource('karyawan', KaryawanController::class);
        Route::apiResource('users', ManajemenUserController::class);

        Route::get('pesanan', [PesananController::class, 'index']);
        Route::post('pesanan', [PesananController::class, 'store']);

        Route::get('pembayaran', [PembayaranController::class, 'index']);
        Route::put('pembayaran/{id}', [PembayaranController::class, 'update']);

        Route::post('reservasi', [MejaController::class, 'storeReservasi']);
        Route::put('reservasi/{reservasi}', [MejaController::class, 'updateReservasi']);
        Route::delete('reservasi/{reservasi}', [MejaController::class, 'destroyReservasi']);
    });
});
