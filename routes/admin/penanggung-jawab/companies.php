<?php

use App\Http\Controllers\Admin\PenanggungJawab\CompanyController;
use Illuminate\Support\Facades\Route;

Route::prefix('penanggung-jawab')->name('penanggung-jawab.')->group(function () {
    Route::resource('companies', CompanyController::class);
});
