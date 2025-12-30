<?php

use App\Http\Controllers\Admin\StatusKepemilikanController;
use Illuminate\Support\Facades\Route;

Route::resource('status-kepemilikan', StatusKepemilikanController::class);
