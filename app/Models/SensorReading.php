<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'sensor_type',
        'value',
        'unit',
    ];

    /**
     * Get the latest temperature reading
     *
     * @return \App\Models\SensorReading|null
     */
    public static function getLatestTemperature()
    {
        return self::where('sensor_type', 'temperature')
            ->latest()
            ->first();
    }

    /**
     * Get the latest humidity reading
     *
     * @return \App\Models\SensorReading|null
     */
    public static function getLatestHumidity()
    {
        return self::where('sensor_type', 'humidity')
            ->latest()
            ->first();
    }
}
