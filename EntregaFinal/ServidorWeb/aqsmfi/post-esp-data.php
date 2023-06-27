<?php
// Keep this API Key value to be compatible with the ESP32 code provided in the project page. 
// If you change this value, the ESP32 sketch needs to match
$api_key_value = "tPmAT5Ab3j7F9";

$api_key= $sensor = $location = $turbidez = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $api_key = test_input($_POST["api_key"]);
    if($api_key == $api_key_value) {
        $sensorName = test_input($_POST["sensorName"]);
        $location = test_input($_POST["location"]);
        $readValue = test_input($_POST["readValue"]);
        

        include ('connection.php'); // Incluye el archivo de conexión a la base de datos

        $sql = "INSERT INTO sensordata (sensorName, location, readValue)
        VALUES ('" . $sensorName . "', '" . $location . "', '" . $readValue . "')";
        
        if ($con->query($sql) === TRUE) {
            echo "New record created successfully";
        } 
        else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }
    
        $con->close();
    }
    else {
        echo "Wrong API Key provided.";
    }

}
else {
    echo "No data posted with HTTP POST.";
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
