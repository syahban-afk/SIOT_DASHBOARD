<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/sensor/latest', [SensorController::class, 'getLatestReadings']);
Route::post('/mqtt/data', [SensorController::class, 'store']);
