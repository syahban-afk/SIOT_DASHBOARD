<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>

<body>
    <header>
        <div class="container">
            <h2>Sensor Dashboard</h2>
        </div>
    </header>

    <div class="container">
        <div class="dashboard-grid">
            <div class="card">
                <h2 class="card-title">Temperature</h2>
                <div id="temperature" class="sensor-value">
                    {{ $temperature ? $temperature . '°C' : '--°C' }}
                </div>
            </div>

            <div class="card">
                <h2 class="card-title">Humidity</h2>
                <div id="humidity" class="sensor-value">
                    {{ $humidity ? $humidity . '%' : '--%' }}
                </div>
            </div>
        </div>

        <div class="card control-group">
            <h2 class="card-title">Servo Control</h2>

            <div class="slider-container">
                <label>Angle (0–180°)</label>
                <input type="range" class="slider" min="0" max="180" value="90" id="servo-angle">
                <div class="slider-labels">
                    <span>0°</span>
                    <span id="servo-value" style="color: var(--primary); font-weight: 600;">90°</span>
                    <span>180°</span>
                </div>
            </div>

            <div class="btn-group">
                @foreach ([0, 45, 90, 135, 180] as $angle)
                    <button onclick="setServoAngle({{ $angle }})" class="btn">
                        {{ $angle }}°
                    </button>
                @endforeach
            </div>

            <button id="send-servo" class="btn btn-primary">Send to Servo</button>
        </div>

        <div class="card control-group">
            <h2 class="card-title">LCD Display Control</h2>

            <div>
                <label>Text Message (max 32 chars)</label>
                <textarea id="lcd-text" maxlength="32" placeholder="Enter text to display on LCD..."></textarea>
                <div class="char-count"><span id="char-count">0</span>/32</div>
            </div>

            <div class="btn-group">
                <button onclick="setLcdText('Hello World!')" class="btn">Hello World!</button>
                <button onclick="setLcdText('Temperature: {{ $temperature ? $temperature . '°C' : '--°C' }}')"
                    class="btn">Current Temp</button>
                <button onclick="setLcdText('Humidity: {{ $humidity ? $humidity . '%' : '--%' }}')"
                    class="btn">Current Humidity</button>
            </div>

            <button id="send-lcd" class="btn btn-primary">Send to LCD</button>
        </div>
    </div>

    <script>
        window.MQTT_CONFIG = @json($mqttConfig);
    </script>

    <script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
    <script src="{{ asset('js/sensor.js') }}"></script>
</body>

</html>
