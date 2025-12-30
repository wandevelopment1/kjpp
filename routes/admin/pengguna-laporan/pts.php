<?php

use App\Http\Controllers\Admin\PenggunaLaporan\PtController;
use Illuminate\Support\Facades\Route;

Route::prefix('pengguna-laporan')->name('pengguna-laporan.')->group(function () {
    Route::resource('pts', PtController::class);
});
