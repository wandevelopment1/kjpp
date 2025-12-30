<?php

use App\Http\Controllers\Admin\BidangUsahaController;
use Illuminate\Support\Facades\Route;

Route::resource('bidang-usaha', BidangUsahaController::class);
