<?php

use App\Http\Controllers\Admin\PenanggungJawab\ReviewerController;
use Illuminate\Support\Facades\Route;

Route::prefix('penanggung-jawab')->name('penanggung-jawab.')->group(function () {
    Route::resource('reviewers', ReviewerController::class);
});
