# Weather API Project

This simple PHP project demonstrates how to integrate with the OpenWeatherMap API to fetch and display weather information.

## Setup

1. Clone this repository
2. Sign up for a free account at OpenWeatherMap to get an API key
3. Add your API key to `config.php`
4. Run a PHP server in the project directory: `php -S localhost:8000`
5. Open a web browser and navigate to `http://localhost:8000`

## Usage

To get weather for a specific city, use the `city` query parameter:
`http://localhost:8000/index.php?city=Tokyo`

## Note

This is a basic example and does not include input validation or advanced error handling.