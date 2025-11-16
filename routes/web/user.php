<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistrasiController;
use App\Http\Controllers\User\TiketController;
use Illuminate\Support\Facades\Route;

Route::middleware(['user'])->group(function () {
    Route::resource('tiket', TiketController::class);
});