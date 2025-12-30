<?php

use App\Http\Controllers\Admin\PenanggungJawab\InspeksiController;
use Illuminate\Support\Facades\Route;

Route::prefix('penanggung-jawab')->name('penanggung-jawab.')->group(function () {
    Route::resource('inspeksi', InspeksiController::class);
});
