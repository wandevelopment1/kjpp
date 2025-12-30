<?php

use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;

Route::prefix('admin')->group(function () {
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('admin.password.request');

Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('admin.password.email');

Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('admin.password.reset');

Route::post('reset-password', [ResetPasswordController::class, 'reset'])
    ->name('admin.password.update');
});