<?php

use App\Http\Controllers\Admin\TujuanController;
use Illuminate\Support\Facades\Route;

Route::resource('tujuan', TujuanController::class);
