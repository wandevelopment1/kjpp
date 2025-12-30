<?php

use App\Http\Controllers\Admin\PenawaranController;
use Illuminate\Support\Facades\Route;

Route::resource('penawaran', PenawaranController::class);
Route::post('penawaran/{penawaran}/approve', [PenawaranController::class, 'approve'])
    ->name('penawaran.approve');
Route::patch('penawaran/{penawaran}/laporan', [PenawaranController::class, 'updateLaporan'])
    ->name('penawaran.laporan');
Route::get('penawaran/{penawaran}/export-template/{group}', [PenawaranController::class, 'exportTemplate'])
    ->name('penawaran.export-template');
Route::post('penawaran/{penawaran}/template/{group}/upload', [PenawaranController::class, 'uploadTemplateFile'])
    ->name('penawaran.template.upload');
Route::get('penawaran/{penawaran}/template/{group}/view', [PenawaranController::class, 'viewTemplateFile'])
    ->name('penawaran.template.view');
Route::get('penawaran/{penawaran}/invoice-final', [PenawaranController::class, 'downloadFinalInvoice'])
    ->name('penawaran.invoice-final');
