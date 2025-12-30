<?php

use App\Http\Controllers\Admin\KepadaKabKotaController;
use Illuminate\Support\Facades\Route;

Route::resource('kepada-kab-kota', KepadaKabKotaController::class);
