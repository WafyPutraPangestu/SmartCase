<?php

use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\Route;


Route::post('/predict-prioritas', [ApiController::class, 'predict']);
