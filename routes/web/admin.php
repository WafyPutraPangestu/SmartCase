<?php

use App\Http\Controllers\Admin\GangguanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\TiketController;

Route::middleware(['admin'])->group(function () {
    
    Route::resource('kategori_gangguan', GangguanController::class);
    Route::resource('kategori_pelanggan', PelangganController::class);
    
    
    Route::resource('admin/tiket', TiketController::class)
        ->only(['index', 'show', 'update'])
        ->names([
            'index' => 'admin.tiket.index',
            'show' => 'admin.tiket.show',
            'update' => 'admin.tiket.update',
        ]);
    
    
    Route::put('/admin/tiket/{id}/status', [TiketController::class, 'updateStatus'])
        ->name('admin.tiket.updateStatus');
    
    
    Route::put('/admin/tiket/{id}/prioritas', [TiketController::class, 'updatePrioritas'])
        ->name('admin.tiket.updatePrioritas');
    
    
    Route::post('/admin/tiket/{id}/prioritas-api', [TiketController::class, 'updatePrioritasApi'])
        ->name('admin.tiket.updatePrioritasApi');
    
    
    Route::get('/admin/ml/dashboard', [TiketController::class, 'mlDashboard'])
        ->name('admin.ml.dashboard');
    
    
    Route::post('/admin/ml/retrain', [TiketController::class, 'manualRetrain'])
        ->name('admin.ml.retrain');
});