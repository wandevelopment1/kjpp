<?php

use App\Http\Controllers\Admin\PenggunaLaporan\NamaController;
use Illuminate\Support\Facades\Route;

Route::prefix('pengguna-laporan')->name('pengguna-laporan.')->group(function () {
    Route::resource('nama', NamaController::class);
});
