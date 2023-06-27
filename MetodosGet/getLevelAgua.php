
<?php

include('connection.php'); // Incluye el archivo de conexión a la base de datos

// Sentencia SQL para obtener el valor de la base de datos
$sql = "SELECT readValue FROM sensordata WHERE sensorName='proximidad' ORDER BY id DESC LIMIT 1";

// Ejecutar la consulta SQL
$result = $con->query($sql);

// Verificar si se obtuvieron resultados
if ($result->num_rows > 0) {
    // Obtener el valor de la cantidad de agua de la base de datos
    $row = $result->fetch_assoc();
    $readValue = $row["readValue"];

    // Devolver el valor como una respuesta JSON
    $response = array('valor' => $readValue);
    echo json_encode($response);
} else {
    echo "No se encontraron resultados.";
}

// Cerrar la conexión
$con->close();
?>