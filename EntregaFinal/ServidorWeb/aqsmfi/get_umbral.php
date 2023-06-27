
<?php

include('connection.php'); // Incluye el archivo de conexión a la base de datos

// Prepare SQL statement to retrieve "umbral" value
$sql = "SELECT parameterValue FROM settingparameters WHERE parameterName = 'umbral'";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    // Retrieve "umbral" value from the first row of the result set
    $row = $result->fetch_assoc();
    $umbral = $row["parameterValue"];
    echo $umbral;

} else {
    echo "No se encontro los parametros umbral  en la tabla";
}
// Close connection
$con->close();
?>

