<?php

use App\Http\Controllers\Admin\TipePropertiController;
use Illuminate\Support\Facades\Route;

Route::resource('tipe-properti', TipePropertiController::class);
