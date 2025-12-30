<?php

use App\Http\Controllers\Admin\UiConfigGroupController;

Route::resource('ui-config-group', UiConfigGroupController::class);
Route::get('ui-config-group/sort/order', [UiConfigGroupController::class, 'sort'])->name('ui-config-group.sort');
Route::post('ui-config-group/sort/order', [UiConfigGroupController::class, 'updateOrder'])->name('ui-config-group.updateOrder');