<?php

use App\Http\Controllers\Admin\CkeditorController;

Route::post('/ckeditor/upload', [CkeditorController::class, 'upload'])->name('ckeditor.upload');