<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\MqttController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/sensor', [SensorController::class, 'index']);

Route::post('/api/mqtt/data', [MqttController::class, 'handleIncomingData']);
Route::get('/api/sensor/latest', [SensorController::class, 'getLatestReadings']);
