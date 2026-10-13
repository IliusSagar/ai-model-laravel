<?php

use App\Http\Controllers\AiController;
use Illuminate\Support\Facades\Route;

Route::post('/ai/generate', [
    AiController::class,
    'generate',
])->middleware('throttle:5,1');