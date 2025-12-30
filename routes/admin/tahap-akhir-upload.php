<?php

use App\Http\Controllers\Admin\TahapAkhirUploadController;
use Illuminate\Support\Facades\Route;

Route::resource('tahap-akhir-upload', TahapAkhirUploadController::class);
Route::get('tahap-akhir-upload/{tahap_akhir_upload}/download', [TahapAkhirUploadController::class, 'download'])
    ->name('tahap-akhir-upload.download');
