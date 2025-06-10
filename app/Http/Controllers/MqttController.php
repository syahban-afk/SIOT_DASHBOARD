<?php

namespace App\Http\Controllers;

use App\Models\SensorReading;
use Illuminate\Http\Request;

class MqttController extends Controller
{
    /**
     * Handle incoming MQTT data
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handleIncomingData(Request $request)
    {
        $validated = $request->validate([
            'topic' => 'required|string',
            'message' => 'required|string',
        ]);

        $topic = $validated['topic'];
        $message = $validated['message'];

        if ($topic === 'home/temp') {
            SensorReading::create([
                'sensor_type' => 'temperature',
                'value' => (float) $message,
                'unit' => '°C',
            ]);
        } elseif ($topic === 'home/humidity') {
            SensorReading::create([
                'sensor_type' => 'humidity',
                'value' => (float) $message,
                'unit' => '%',
            ]);
        }

        return response()->json(['status' => 'success']);
    }
}
