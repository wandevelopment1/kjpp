<?php

use App\Http\Controllers\Admin\KepemilikanController;
use Illuminate\Support\Facades\Route;

Route::resource('kepemilikan', KepemilikanController::class);
