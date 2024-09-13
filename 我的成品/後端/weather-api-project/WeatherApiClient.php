<?php
require_once 'config.php';

class WeatherApiClient
{
    // private $apiKey;
    // private $apiUrl;

    public function getWeather($city)
    {
        $url = OPENWEATHER_API_URL . "?q=$city&appid=" . OPENWEATHER_API_KEY . "&units=metric";

        $response = file_get_contents($url);
        if ($response === false) {
            throw new Exception("Failed to get weather data");
        }

        $data = json_decode($response, true);
        if(!isset($data['main']) || !isset($data['weather'])) {
            throw new Exception("Invalid response from API");
        }

        return [
            'temperature' => $data['main']['temp'],
            'description' => $data['weather'][0]['description'],
            'humidity' => $data['main']['humidity'],
            'wind_speed' => $data['wind']['speed'],
        ];
    }
}

// $weatherApiClient = new WeatherApiClient();