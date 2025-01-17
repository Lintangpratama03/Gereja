<?php

use App\Http\Controllers\ListAcaraController;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PemesananController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/pengaturan', [App\Http\Controllers\UserController::class, 'create'])->name('pengaturan');
    Route::post('/edit/name', [App\Http\Controllers\UserController::class, 'name'])->name('edit.name');
    Route::post('/edit/password', [App\Http\Controllers\UserController::class, 'password'])->name('edit.password');
    Route::get('/transaksi/{kode}', [App\Http\Controllers\LaporanController::class, 'show'])->name('transaksi.show');

    Route::middleware(['petugas'])->group(function () {
        Route::get('/pembayaran/{id}', [App\Http\Controllers\LaporanController::class, 'pembayaran'])->name('pembayaran');
        Route::get('/petugas', [App\Http\Controllers\LaporanController::class, 'petugas'])->name('petugas');
        Route::post('/petugas', [App\Http\Controllers\LaporanController::class, 'kode'])->name('petugas.kode');

        Route::middleware(['admin'])->group(function () {
            Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
            Route::resource('/category', App\Http\Controllers\CategoryController::class);
            Route::resource('/acara', App\Http\Controllers\CategoryAcaraController::class);
            Route::resource('/transportasi', App\Http\Controllers\TransportasiController::class);
            Route::resource('/rute', App\Http\Controllers\RuteController::class);
            Route::resource('/list', App\Http\Controllers\ListAcaraController::class);
            Route::resource('/user', App\Http\Controllers\UserController::class);
            Route::get('/transaksi', [App\Http\Controllers\LaporanController::class, 'index'])->name('transaksi');
        });
    });

    Route::middleware(['penumpang'])->group(function () {
        Route::get('/pemesanan-list', [PemesananController::class, 'list'])->name('pemesanan.list');
        Route::get('/pemesanan-pesan/{id}', [PemesananController::class, 'pesan'])->name('pemesanan.pesan');
        Route::post('/pemesanan/upload-bukti/{id}', [PemesananController::class, 'uploadBukti'])->name('pemesanan.uploadBukti');
        // Route::get('/pesan/{kursi}/{data}', [App\Http\Controllers\PemesananController::class, 'pesan'])->name('pesan');
        Route::get('/cari/kursi/{data}', [App\Http\Controllers\PemesananController::class, 'edit'])->name('cari.kursi');
        Route::resource('/', App\Http\Controllers\PemesananController::class);
        Route::get('/history', [App\Http\Controllers\LaporanController::class, 'history'])->name('history');
        Route::get('/{id}/{data}', [App\Http\Controllers\PemesananController::class, 'show'])->name('show');
    });
});

Route::get('/homependeta', [App\Http\Controllers\HomeController::class, 'indexpendeta'])->name('homependeta');
Route::resource('/validasi', App\Http\Controllers\ValidasiController::class);
Route::patch('/rute/{id}/update-status', [App\Http\Controllers\ValidasiController::class, 'updateStatus'])->name('rute.updateStatus');
Route::patch('/rute/{id}/soft-delete', [App\Http\Controllers\ValidasiController::class, 'softDelete'])->name('rute.softDelete');

Route::get('/get-price', [App\Http\Controllers\PemesananController::class, 'getPrice'])->name('getPrice');
Route::post('/check-schedule', [PemesananController::class, 'checkSchedule'])->name('check.schedule');

// Route::post('/acara-confirm/{id}', [ListAcaraController::class, 'confirm'])->name('acara.confirm');
Route::post('/acara/confirm/{id}', [ListAcaraController::class, 'confirm'])->name('acara.confirm');
