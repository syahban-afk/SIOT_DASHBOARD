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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <header class="bg-primary text-white py-3 mb-4">
        <div class="container">
            <h1 class="h3 mb-0">Sensor Dashboard</h1>
        </div>
    </header>

    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Temperature</h5>
                        <h2 id="temperature" class="display-6 text-primary">
                            {{ $temperature ? $temperature . '°C' : '--°C' }}
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Humidity</h5>
                        <h2 id="humidity" class="display-6 text-primary">
                            {{ $humidity ? $humidity . '%' : '--%' }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Servo Control</h5>
                <div class="mb-3">
                    <label for="servo-angle" class="form-label">Angle (0–180°)</label>
                    <input type="range" class="form-range" min="0" max="180" value="90"
                        id="servo-angle">
                    <div class="d-flex justify-content-between text-muted small mt-1">
                        <span>0°</span>
                        <span id="servo-value" class="fw-semibold text-primary">90°</span>
                        <span>180°</span>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="btn-group flex-wrap" role="group">
                        @foreach ([0, 45, 90, 135, 180] as $angle)
                            <button type="button" onclick="setServoAngle({{ $angle }})"
                                class="btn btn-outline-secondary mb-2">{{ $angle }}°</button>
                        @endforeach
                    </div>
                </div>

                <button id="send-servo" class="btn btn-primary">Send to Servo</button>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">LCD Display Control</h5>

                <div class="mb-3">
                    <label for="lcd-text" class="form-label">Text Message (max 32 chars)</label>
                    <textarea class="form-control" id="lcd-text" rows="2" maxlength="32"
                        placeholder="Enter text to display on LCD..."></textarea>
                    <div class="form-text text-end"><span id="char-count">0</span></div>
                </div>

                <div class="mb-3">
                    <div class="btn-group flex-wrap" role="group">
                        <button type="button" onclick="setLcdText('Hello World!')"
                            class="btn btn-outline-secondary mb-2">Hello World!</button>
                        <button type="button" onclick="setLcdText('Temperature: {{ $temperature ? $temperature . '°C' : '--°C' }}')"
                            class="btn btn-outline-secondary mb-2">New Temperature</button>
                        <button type="button" onclick="setLcdText('Humidity: {{ $humidity ? $humidity . '%' : '--%' }}')"
                            class="btn btn-outline-secondary mb-2">New Humidity</button>
                    </div>
                </div>

                <button id="send-lcd" class="btn btn-primary">Send to LCD</button>
            </div>
        </div>

    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <script>
        window.MQTT_CONFIG = @json($mqttConfig);
    </script>

    <script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
    <script src="{{ asset('js/sensor.js') }}"></script>
</body>

</html>
