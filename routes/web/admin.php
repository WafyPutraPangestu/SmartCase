<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GangguanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\TiketController;

Route::middleware(['admin'])->group(function () {
    Route::resource('kategori_gangguan', GangguanController::class);
    Route::resource('kategori_pelanggan', PelangganController::class);

    // =============================================================================================
    // ================================== Admin Dashboard API Routes ===============================
    // =============================================================================================
    
//      Route::get('/stats', [DashboardController::class, 'getStats'])
//      ->name('api.admin.dashboard.stats');
//  Route::prefix('tickets')->group(function () {
//      Route::get('/', [DashboardController::class, 'getTickets'])
//          ->name('api.admin.dashboard.tickets');
//      Route::post('/{id}/status', [DashboardController::class, 'updateTicketStatus'])
//          ->name('api.admin.dashboard.tickets.status');
//      Route::post('/{id}/priority', [DashboardController::class, 'updateTicketPriority'])
//          ->name('api.admin.dashboard.tickets.priority');
//      Route::post('/bulk-status', [DashboardController::class, 'bulkUpdateStatus'])
//          ->name('api.admin.dashboard.tickets.bulk-status');
//  });
//  Route::prefix('ml')->group(function () {
//      Route::get('/status', [DashboardController::class, 'getMLStatus'])
//          ->name('api.admin.dashboard.ml.status');
//      Route::post('/retrain', [DashboardController::class, 'triggerMLRetrain'])
//          ->name('api.admin.dashboard.ml.retrain');
//  });
//  Route::get('/users', [DashboardController::class, 'getUsers'])
//      ->name('api.admin.dashboard.users');
//  Route::get('/charts', [DashboardController::class, 'getChartsData'])
//      ->name('api.admin.dashboard.charts');
//  Route::get('/export', [DashboardController::class, 'exportData'])
//      ->name('api.admin.dashboard.export');

     Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.index');
    //  =============================================================================================
    // ================================== Admin Tiket Routes =============================================
    //  =============================================================================================
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
// API Routes untuk Dashboard Admin
Route::middleware(['auth', 'admin'])->prefix('api/admin')->name('api.admin.')->group(function () {
    
    // Dashboard Stats API
    Route::get('/stats', [DashboardController::class, 'getStats'])->name('stats');
    
    // Tickets API
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [DashboardController::class, 'getTickets'])->name('index');
        Route::post('/{id}/status', [DashboardController::class, 'updateTicketStatus'])->name('status');
        Route::post('/{id}/priority', [DashboardController::class, 'updateTicketPriority'])->name('priority');
        Route::post('/bulk-status', [DashboardController::class, 'bulkUpdateStatus'])->name('bulk-status');
    });
    
    // Users API
    Route::get('/users', [DashboardController::class, 'getUsers'])->name('users');
    
    // Charts API
    Route::get('/charts', [DashboardController::class, 'getChartsData'])->name('charts');
    
    // Export API
    Route::get('/export', [DashboardController::class, 'exportData'])->name('export');
});