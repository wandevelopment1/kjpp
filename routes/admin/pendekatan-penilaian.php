<?php

use App\Http\Controllers\Admin\PendekatanPenilaianController;
use Illuminate\Support\Facades\Route;

Route::resource('pendekatan-penilaian', PendekatanPenilaianController::class);
