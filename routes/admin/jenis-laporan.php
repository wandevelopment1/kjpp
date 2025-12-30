<?php

use App\Http\Controllers\Admin\JenisLaporanController;
use Illuminate\Support\Facades\Route;

Route::resource('jenis-laporan', JenisLaporanController::class);
