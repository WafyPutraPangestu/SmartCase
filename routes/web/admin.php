<?php

use App\Http\Controllers\Admin\GangguanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\TiketController;

Route::middleware(['admin'])->group(function () {
    Route::resource('kategori_gangguan', GangguanController::class);
    Route::resource('kategori_pelanggan', PelangganController::class);
    Route::put('/admin/tiket/{id}/status', [TiketController::class, 'updateStatus'])
    ->name('admin.tiket.updateStatus');
    Route::resource('admin/tiket', TiketController::class)
    ->only(['index','show','update'])
    ->names([
        'index' => 'admin.tiket.index',
        'show' => 'admin.tiket.show',
        'update' => 'admin.tiket.update',
    ]);
});
