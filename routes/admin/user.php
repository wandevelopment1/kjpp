<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;

Route::resource('user', UserController::class);
Route::post('/user/{id}/sync-role', [UserController::class, 'syncRole'])->name('user.syncRole');
