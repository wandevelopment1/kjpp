<?php

use App\Http\Controllers\Admin\MetodePenilaianController;
use Illuminate\Support\Facades\Route;

Route::resource('metode-penilaian', MetodePenilaianController::class);
