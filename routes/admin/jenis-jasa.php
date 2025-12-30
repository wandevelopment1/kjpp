<?php

use App\Http\Controllers\Admin\JenisJasaController;
use Illuminate\Support\Facades\Route;

Route::resource('jenis-jasa', JenisJasaController::class);
