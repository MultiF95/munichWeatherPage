<?php
$url = "https://api.weather.com/v2/turbo/vt1hourlyforecast?apiKey=d522aa97197fd864d36b418f39ebb323&format=json&geocode=48.14%2C11.58&language=de-DE&units=m";
$jwc = file_get_contents($url);
$jwc = json_decode($jwc);
echo $jwc
?>
