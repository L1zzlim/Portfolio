<?php
require_once 'WeatherApiClient.php';

$city = isset($_GET['city']) ? $_GET['city'] : 'London';

$client = new WeatherApiClient();

echo '<div>
    "Hello World"
</div>';

try {
    $weatherData = $client->getWeather($city);
    echo "Weather in $city:<br>";
    echo "Temperature: {$weatherData['temperature']} °C<br>";
    echo "Description: {$weatherData['description']}<br>";
    echo "Humidity: {$weatherData['humidity']}%<br>";
    echo "Wind Speed: {$weatherData['wind_speed']} m/s<br>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!-- <!DOCTYPE html> -->