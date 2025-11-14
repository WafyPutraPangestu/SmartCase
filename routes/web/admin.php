<?php

use App\Http\Controllers\Admin\GangguanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PelangganController;

Route::middleware(['admin'])->group(function () {
    Route::resource('kategori_gangguan', GangguanController::class);
Route::resource('kategori_pelanggan', PelangganController::class);
});
