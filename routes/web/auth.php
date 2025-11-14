<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistrasiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::resource('login', LoginController::class)->only(['index', 'store']);
    Route::resource('register', RegistrasiController::class)->only(['index', 'store']);
});

Route::middleware(['auth'])->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
});