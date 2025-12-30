<?php

use App\Http\Controllers\Admin\PenggunaLaporan\JenisIndustriController;
use Illuminate\Support\Facades\Route;

Route::prefix('pengguna-laporan')->name('pengguna-laporan.')->group(function () {
    Route::resource('jenis-industri', JenisIndustriController::class);
});
