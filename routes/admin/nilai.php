<?php

use App\Http\Controllers\Admin\NilaiController;
use Illuminate\Support\Facades\Route;

Route::resource('nilai', NilaiController::class);
