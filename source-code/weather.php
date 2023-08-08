<?php
$apiKey = '97a736f146b2a40fe365a2fe789b1430';
$city = $_GET['city'];
$apiUrl = "https://api.openweathermap.org/data/2.5/forecast?q=$city&units=metric&appid=$apiKey";

$response = file_get_contents($apiUrl);
$data = json_decode($response, true);


// Extract data from the API 
$currentTemp = $data['list'][0]['main']['temp'];
$feelsLikeTemp = $data['list'][0]['main']['feels_like'];
$maxTemp = $data['list'][0]['main']['temp_max'];
$minTemp = $data['list'][0]['main']['temp_min'];
$windSpeed = $data['list'][0]['wind']['speed'];
$humidity = $data['list'][0]['main']['humidity'];

$daysOfWeek = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'); 

$forecastData = array();
$currentDayIndex = date('w'); //index of the current day (0 for Sunday, 1 for Monday......)

for ($i = 1; $i <= 5; $i++) {
    $forecastTemp = $data['list'][$i]['main']['temp'];
    $forecastDayIndex = ($currentDayIndex + $i) % 7; 
    $forecastDayName = $daysOfWeek[$forecastDayIndex];
    $forecastData[] = array('temp' => $forecastTemp, 'day' => $forecastDayName);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Weather Forecasting</title>
</head>

<body>
    <div class="container">
        <h1>Weather <span>Forecasting</span> </h1>
        <form action="weather.php" method="GET">
            <input type="text" name="city" placeholder="Enter city: " required>
            <button type="submit">Get Update</button>
        </form>

       <!-- weather data displayed in the div below -->
       <h2>Current Weather in <?php echo $city; ?></h2>
        <div class="weather-info">
            <p>Temperature: <?php echo $currentTemp; ?> &#8451;</p>
            <p>Feels Like: <?php echo $feelsLikeTemp; ?> &#8451;</p>
            <p>Max Temperature: <?php echo $maxTemp; ?> &#8451;</p>
            <p>Min Temperature: <?php echo $minTemp; ?> &#8451;</p>
            <p>Wind Speed: <?php echo $windSpeed; ?> m/s</p>
            <p>Humidity: <?php echo $humidity; ?>%</p>
        </div>
        <h2>5 Days <span>Forecasting</span></h2>

        <div class="forecast">
            <!-- 5-day forecasting -->
            <?php
            $daysOfWeek = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

            foreach ($forecastData as $forecast) {
                $forecastDayTimestamp = strtotime($forecast['day']);
                $dayName = $daysOfWeek[date('w', $forecastDayTimestamp)];
            
                echo "<div class='forecast-box'>";
                echo "<p>Day: {$dayName}</p>";
                echo "<p>Temp: {$forecast['temp']} &#8451;</p>";
                echo "</div>";
            }
            ?>
        </div>

    </div>
    </div>
</body>
</html>
