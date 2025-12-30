<?php
use App\Http\Controllers\Admin\DashboardController;
Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
