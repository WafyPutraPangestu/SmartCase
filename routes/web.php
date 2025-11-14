<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistrasiController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('home');
});

Route::middleware(['guest'])->group(function () {
    Route::resource('login', LoginController::class)->only(['index', 'store']);
    Route::resource('register', RegistrasiController::class)->only(['index', 'store']);
});

Route::middleware(['auth'])->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
});

Route::middleware(['admin'])->group(function () {
    // Route untuk admin
});

Route::middleware(['user'])->group(function () {
    // Route untuk user
});