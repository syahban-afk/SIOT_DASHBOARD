<?php

namespace App\Http\Controllers;

use App\Models\SensorReading;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    /**
     * Display the sensor dashboard
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $latestTemperature = SensorReading::getLatestTemperature();
        $latestHumidity = SensorReading::getLatestHumidity();

        return view('sensor', [
            'temperature' => $latestTemperature ? $latestTemperature->value : null,
            'humidity' => $latestHumidity ? $latestHumidity->value : null,
        ]);
    }

    /**
     * Store a new sensor reading
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sensor_type' => 'required|string|in:temperature,humidity',
            'value' => 'required|numeric',
            'unit' => 'nullable|string|max:10',
        ]);

        $reading = SensorReading::create($validated);

        return response()->json($reading, 201);
    }

    /**
     * API endpoint to get the latest sensor readings
     *
     * @return \Illuminate\Http\Response
     */
    public function getLatestReadings()
    {
        $temperature = SensorReading::getLatestTemperature();
        $humidity = SensorReading::getLatestHumidity();

        return response()->json([
            'temperature' => $temperature ? $temperature->value : null,
            'humidity' => $humidity ? $humidity->value : null,
        ]);
    }
}
