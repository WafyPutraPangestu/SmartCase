<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistrasiController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('home');
});




foreach (glob(__DIR__ . '/web/*.php') as $routeFile) {
    require $routeFile;
}