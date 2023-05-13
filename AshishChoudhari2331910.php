<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            background-color: black;
            color: #0056b3;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap-reverse; /* Added flex-wrap property */
        }
        
        .weather-app {
            background-color: red;
            color: white;
            border: none;
            padding: 8px 20px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
        }
        
        .weather-app:hover {
            background-color: brown;
        }
        
        .search-form {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            color: #0056b3;
        }
        
        .search-form label {
            margin-right: 10px;
            
        }
        
        .search-form input[type="text"] {
            margin-right: 10px;
            padding: 6px;
            font-size: 14px;
            
        }
        
        .search-form input[type="submit"] {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            padding: 8px 20px;
            font-size: 16px;
            cursor: pointer;
            
        }
        
        .search-form input[type="submit"]:hover {
            background-color: brown;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            margin-top: 20px;
            background-color: black;
            color: #dddddd;
        }
        
        th, td {
            text-align: left;
            padding: 8px;
            border: 1px solid #dddddd;
        }
        
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div id="weather-data-container"></div>
    
<script>
function validateForm() {
    const city = document.getElementById("city").value;
    if (city.trim() === "") {
        alert("Please enter a city name");
        return false;
    }
    return true;
}
const saveToLocalStorage = (city, data) => {
    localStorage.setItem(city, JSON.stringify(data));
};
const displayWeatherData = (data) => {
    const container = document.getElementById('weather-data-container');
    // Construct the table header
    let html = "<table><tr><th>Date</th><th>City Name</th><th>Weather Description</th><th>Temperature (°C)</th><th>Feels Like (°C)</th><th>Humidity (%)</th><th>Wind Speed (km/h)</th><th>Visibility (km)</th><th>Total Precipitation (mm)</th><th>Weather Icon</th></tr>";
    // Display the data in the table
    data.forEach(row => {
        html += "<tr><td>" + row.date + "</td><td>" + row.city + "</td><td>" + row.weather_description + "</td><td>" + row.temperature + "</td><td>" + row.feels_like + "</td><td>" + row.humidity + "</td><td>" + row.wind_speed + "</td><td>" + row.visibility + "</td><td>" + row.totalprecip_mm + "</td><td><img src='" + row.weather_icon + "' alt='" + row.weather_description + "'></td></tr>";
    });
    // Close the table
    html += "</table>";
    // Add table to the container
    container.innerHTML = html;
};
const getDataFromLocalStorage = (city) => {
    const data = localStorage.getItem(city);
    return data ? JSON.parse(data) : null;
};
 // Get the city name from the local storage
const city = localStorage.getItem('lastSearchedCity') || 'Chickasaw';
 // Check if the saved data for the city exists in the local storage
const savedData = getDataFromLocalStorage(city);
if (savedData) {
    // If the saved data exists, display it
    displayWeatherData(savedData);
} else {
    // If no saved data exists, try to fetch it from the server
    // In this case, you might need to implement a JavaScript function to fetch data from your PHP script and save it to the local storage
    const url = "your-api-url-here";
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            saveToLocalStorage(city, data);
            displayWeatherData(data);
        })
        .catch(error => {
            console.error('There was a problem fetching the data:', error);
        });
}
</script>
</body>
<?php
// Establish a connection to the database 
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "weather"; 
$conn = mysqli_connect($servername, $username, $password, $dbname);
 // Check if the connection was successful 
if (!$conn) { 
    die("Connection failed: " . mysqli_connect_error()); 
}
 // Get the city name from GET parameter or default it to 'Chickasaw'
$city = isset($_GET["city"]) ? $_GET["city"] : "Chickasaw";
 // Set API key 
$apiKey = "ce0d464fb8dc4d789b070310232503"; 
 // Retrieve the data from the weather_data table
$sql = "SELECT * FROM weather_data WHERE city = '$city'";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die('Error fetching data: ' . mysqli_error($conn));
}
if (mysqli_num_rows($result) > 0) { 
    $fetchedData = []; 
    while ($row = mysqli_fetch_assoc($result)) { 
        $fetchedData[] = $row; 
    }
    // Pass the data to JavaScript
    echo "<script>
    const fetchedData = " . json_encode($fetchedData) . ";
    saveToLocalStorage('".$city."', fetchedData);
    displayWeatherData(fetchedData);
    </script>"; 
} else { 
    echo ""; 
}
 // Close the connection 
mysqli_close($conn); 
?>
<body onload="displayFromLocalStorage();">
    <div class="header">
        <button class="weather-app" onclick="location.href='AshishChoudhari2331910.html'">Weather App</button>
        <form action="" method="get" onsubmit="return validateForm();">
            <label for="city">Enter a city:</label>
            <input type="text" id="city" name="city">
            <input type="submit" value="Search">
        </form>
        <form method="post" action="" class="1">
            <input type="submit" name="delete_all_data" value="Delete Outdated and Current Data">
        </form>
    </div>
    <div id="weatherData"></div>
</body>
</html>
<?php 
// Establish a connection to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "weather";
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
function displayWeatherData($result)
{
    // Construct the table header
    echo "<table>";
    echo "<tr>
            <th>Date</th>
            <th>City Name</th>
            <th>Weather Description</th>
            <th>Temperature (°C)</th>
            <th>Feels Like (°C)</th>
            <th>Humidity (%)</th>
            <th>Wind Speed (km/h)</th>
            <th>Visibility (km)</th>
            <th>Total Precipitation (mm)</th>
            <th>Weather Icon</th>
        </tr>";
    // Display the data in the table
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['date'] . "</td>";
        echo "<td>" . $row['city'] . "</td>";
        echo "<td>" . $row['weather_description'] . "</td>";
        echo "<td>" . $row['temperature'] . "</td>";
        echo "<td>" . $row['feels_like'] . "</td>";
        echo "<td>" . $row['humidity'] . "</td>";
        echo "<td>" . $row['wind_speed'] . "</td>";
        echo "<td>" . $row['visibility'] . "</td>";
        echo "<td>" . $row['totalprecip_mm'] . "</td>";
        echo "<td><img src='" . $row['weather_icon'] . "' alt='" . $row['weather_description'] . "'></td>";
        echo "</tr>";
    }
    // Close the table
    echo "</table>";
}
// If user clicks on delete all data button, delete all data from the weather_data table
if (isset($_POST['delete_all_data'])) {
    $sql = "DELETE FROM weather_data";
    if (mysqli_query($conn, $sql)) {
        echo "";
    } else {
        echo "Error deleting data: " . mysqli_error($conn) . "<br>";
    }
}
// By default, get the weather data for Chickasaw
$city = "Chickasaw";
// If the user searches for a city, get the weather data for that city instead
if (isset($_GET['city'])) {
    $city = $_GET['city'];
}
// Set API key
$apiKey = "ce0d464fb8dc4d789b070310232503";
// Loop through the past 7 days and fetch the weather data for each day
for ($i = 0; $i < 8; $i++) {
    $date = date('Y-m-d', strtotime('-' . $i . ' days'));
    // Check if the data exists in the database
    $sql = "SELECT * FROM weather_data WHERE city = '$city' AND date = '$date'";
    $result = mysqli_query($conn, $sql);
    $fetchFromAPI = false;
    if (mysqli_num_rows($result) == 0) {
        $fetchFromAPI = true;
    } else {
        // Check if the data is older than 1 day
        $row = mysqli_fetch_assoc($result);
        $storedDate = new DateTime($row['date']);
        $currentDate = new DateTime();
        $interval = $storedDate->diff($currentDate);
        if ($interval->days > 1) {
            $fetchFromAPI = true;
            // Delete old data
            $sql = "DELETE FROM weather_data WHERE city = '$city' AND date = '$date'";
            mysqli_query($conn, $sql);
        }
    }
    if ($fetchFromAPI) {
        // Data doesn't exist or is old, fetch it from the API
        $url = "http://api.weatherapi.com/v1/history.json?key=" . $apiKey . "&q=" . $city . "&dt=" . $date;
         // Check if the connection to the API is available
        if (@fopen($url, "r")) {
            $response = json_decode(file_get_contents($url));
            // If the response is ok, retrieve the weather data and insert it into the database
            if (isset($response->forecast->forecastday)) {
                $dayData = $response->forecast->forecastday[0];
                $day = $dayData->day;
                $cond = $day->condition;
                $iconUrl = $cond->icon;
                if (strpos($iconUrl, "//") === 0) {
                    $iconUrl = "http:".$iconUrl;
                }
                // Insert the data in the weather_data table
                $sql = "INSERT INTO weather_data (date, city, weather_description, temperature, feels_like, humidity, wind_speed, visibility, weather_icon, totalprecip_mm)
                        VALUES ('$date', '$city', '$cond->text', '$day->avgtemp_c', '$day->avgtemp_c', '$day->avghumidity', '$day->maxwind_kph', '$day->avgvis_km', '$iconUrl', '$day->totalprecip_mm')";
                if (mysqli_query($conn, $sql)) {
                    echo "";
                } else {
                    echo "Error inserting data: " . mysqli_error($conn) . "<br>";
                }
            } else {
                // If there's an error, display an error message in the browser
                echo "Error fetching weather data for " . $city . " on " . $date .  "<br>";
            }
        } else {
            // Display error message if no internet connection
            echo "Error: Cannot fetch weather data from the API due to no internet connection. Please check your internet connection and try again. Data is fetched from local storage.<br>";
        }
    }
}
// Retrieve the data from the weather_data table and display it on the webpage
$sql = "SELECT DISTINCT * FROM weather_data WHERE city = '$city'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $fetchedData = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $fetchedData[] = $row;
    }
    echo "<script>
    const fetchedData = " . json_encode($fetchedData) . ";
    saveToLocalStorage('".$city."', fetchedData);
    displayWeatherData(fetchedData);
</script>";
} else {
    // If there's an error, display an error message in the browser
    echo "No weather data found for " . $city . ". Please try again with a different city.<br>";
}
echo "<script>localStorage.setItem('lastSearchedCity', '".$city."');</script>";
// Close the connection
mysqli_close($conn);
?>