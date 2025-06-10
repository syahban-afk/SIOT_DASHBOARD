const mqttClient = {
    client: null,
    server: 'server mu masseh',
    username: 'username mu mas',
    password: 'pw jan lupa ya',
    clientId: 'ini mah bebas bae',

    connect: function () {
        const options = {
            keepalive: 60,
            clientId: this.clientId,
            protocolId: 'MQTT',
            protocolVersion: 4,
            clean: true,
            reconnectPeriod: 1000,
            connectTimeout: 30 * 1000,
            username: this.username,
            password: this.password,
        };

        console.log('Connecting to MQTT broker...');
        this.client = mqtt.connect(this.server, options);

        this.client.on('connect', () => {
            console.log('Connected to MQTT broker');
            this.client.subscribe('home/temp');
            this.client.subscribe('home/humidity');
        });

        this.client.on('message', (topic, message) => {
            const value = message.toString();
            console.log(`Received message on ${topic}: ${value}`);

            if (topic === 'home/temp') {
                this.updateTemperature(value);
                this.saveToDatabase('temperature', value);
            } else if (topic === 'home/humidity') {
                this.updateHumidity(value);
                this.saveToDatabase('humidity', value);
            }
        });

        this.client.on('error', (error) => {
            console.error('MQTT Error:', error);
        });
    },

    publishServoAngle: function (angle) {
        if (!this.client || !this.client.connected) {
            console.error('MQTT client not connected');
            return false;
        }

        console.log(`Publishing servo angle: ${angle}`);
        this.client.publish('home/servo', angle.toString());
        return true;
    },

    publishLcdText: function (text) {
        if (!this.client || !this.client.connected) {
            console.error('MQTT client not connected');
            return false;
        }

        console.log(`Publishing LCD text: ${text}`);
        this.client.publish('home/lcd', text);
        return true;
    },

    updateTemperature: function (value) {
        const temperatureElement = document.getElementById('temperature');
        if (temperatureElement) {
            temperatureElement.textContent = `${value}°C`;
        }
    },

    updateHumidity: function (value) {
        const humidityElement = document.getElementById('humidity');
        if (humidityElement) {
            humidityElement.textContent = `${value}%`;
        }
    },

    saveToDatabase: function (sensorType, value) {
        fetch('/api/mqtt/data', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                topic: sensorType === 'temperature' ? 'home/temp' : 'home/humidity',
                message: value
            })
        })
            .then(response => response.json())
            .then(data => console.log('Saved to database:', data))
            .catch(error => console.error('Error saving to database:', error));
    },

    loadInitialData: function () {
        fetch('/api/sensor/latest')
            .then(response => response.json())
            .then(data => {
                if (data.temperature !== null) {
                    this.updateTemperature(data.temperature);
                }
                if (data.humidity !== null) {
                    this.updateHumidity(data.humidity);
                }
            })
            .catch(error => console.error('Error loading initial data:', error));
    }
};

function updateServoValue() {
    const servoAngle = document.getElementById('servo-angle').value;
    document.getElementById('servo-value').textContent = `${servoAngle}°`;
}

function setServoAngle(angle) {
    const servoSlider = document.getElementById('servo-angle');
    servoSlider.value = angle;
    updateServoValue();
}

function updateCharCount() {
    const lcdText = document.getElementById('lcd-text').value;
    document.getElementById('char-count').textContent = `${lcdText.length}/32`;
}

function setLcdText(text) {
    const lcdTextarea = document.getElementById('lcd-text');
    lcdTextarea.value = text;
    updateCharCount();
}

document.addEventListener('DOMContentLoaded', () => {
    mqttClient.connect();
    mqttClient.loadInitialData();

    const servoSlider = document.getElementById('servo-angle');
    if (servoSlider) {
        servoSlider.addEventListener('input', updateServoValue);
        updateServoValue();
    }

    const sendServoBtn = document.getElementById('send-servo');
    if (sendServoBtn) {
        sendServoBtn.addEventListener('click', () => {
            const angle = document.getElementById('servo-angle').value;
            if (mqttClient.publishServoAngle(angle)) {
                sendServoBtn.classList.add('bg-green-600');
                sendServoBtn.textContent = 'Sent!';
                setTimeout(() => {
                    sendServoBtn.classList.remove('bg-green-600');
                    sendServoBtn.textContent = 'Send to Servo';
                }, 1500);
            }
        });
    }

    const lcdTextarea = document.getElementById('lcd-text');
    if (lcdTextarea) {
        lcdTextarea.addEventListener('input', updateCharCount);
        updateCharCount();
    }

    const sendLcdBtn = document.getElementById('send-lcd');
    if (sendLcdBtn) {
        sendLcdBtn.addEventListener('click', () => {
            const text = document.getElementById('lcd-text').value;
            if (text.trim() === '') {
                alert('Please enter text to display on LCD');
                return;
            }

            if (mqttClient.publishLcdText(text)) {
                sendLcdBtn.classList.add('bg-green-600');
                sendLcdBtn.textContent = 'Sent!';
                setTimeout(() => {
                    sendLcdBtn.classList.remove('bg-green-600');
                    sendLcdBtn.textContent = 'Send to LCD';
                }, 1500);
            }
        });
    }
});
