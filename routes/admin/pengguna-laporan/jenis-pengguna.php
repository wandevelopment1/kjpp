<?php

use App\Http\Controllers\Admin\PenggunaLaporan\JenisPenggunaController;
use Illuminate\Support\Facades\Route;

Route::prefix('pengguna-laporan')->name('pengguna-laporan.')->group(function () {
    Route::resource('jenis-pengguna', JenisPenggunaController::class);
});
