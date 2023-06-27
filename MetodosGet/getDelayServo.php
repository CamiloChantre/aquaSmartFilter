
<?php

include('connection.php'); // Incluye el archivo de conexión a la base de datos

// Prepare SQL statement to retrieve "umbral" value
//$sql = "SELECT parameterValue FROM settingparameters WHERE parameterName = 'umbral'";  solo obtiene un valor
$sql = "SELECT parameterValue FROM settingparameters WHERE parameterName = 'delayServo'";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    // Retrieve "delayservo" value from the second row of the result set
    $row = $result->fetch_assoc();
    $delayServo = $row["parameterValue"];
    echo $delayServo ;

} else {
    echo "No se encontro los parametros umbral o delayServo en la tabla";
}

// Close connection
$con->close();
?>

