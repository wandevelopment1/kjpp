<?php

use App\Http\Controllers\Admin\UiConfigController;

Route::get('ui-config', [UiConfigController::class, 'index'])->name('ui-config.index');
Route::get('ui-config/create', [UiConfigController::class, 'create'])->name('ui-config.create');
Route::post('ui-config', [UiConfigController::class, 'store'])->name('ui-config.store');
Route::get('ui-config/{uiConfig}/edit', [UiConfigController::class, 'edit'])->name('ui-config.edit');
Route::delete('ui-config/{uiConfig}', [UiConfigController::class, 'destroy'])->name('ui-config.destroy');
Route::put('ui-config/{uiConfig}', [UiConfigController::class, 'update'])->name('ui-config.update');


// Route::put('ui-config/{uiConfig}', [UiConfigController::class, 'updateValue'])->name('ui-config.update');



Route::get('ui-config/{uiConfig}', [UiConfigController::class, 'show'])->name('ui-config.show');
Route::get('ui-config/editValue/{uiConfig}', [UiConfigController::class, 'edit2'])->name('ui-config.editValue');

