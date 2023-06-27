<?php
include('log_in.php');
//username lo saco de log in
//$username  
include('connection.php'); // Incluye el archivo de conexión a la base de datos

$quantity = $_POST['quantity']; // Obtiene el valor del campo de cantidad del formulario enviado por POST
$desiredTime = $_POST['desiredTime']; // Obtiene el valor del campo de tiempo deseado del formulario enviado por POST
$diaSemana = $_POST['diaSemana']; // Obtiene el valor del campo name=diaSemana deseado del formulario 

// Convertir el valor a formato de 24 horas
$desiredTime24 = date("H:i:s", strtotime($desiredTime));

// Obtener solo el valor de la hora
$hour = date("H", strtotime($desiredTime24));

// Prepara la consulta SQL con sentencia preparada
$sql = "UPDATE requests SET hour_" . $hour . " = ?  WHERE dia = ? ";

// Prepara la consulta SQL con sentencia preparada
//$sql = "UPDATE requests SET hour_" . $hour . " = ?, userName = ?  WHERE dia = ? AND EXISTS (SELECT 1 FROM login WHERE userName = ?)";

// Prepara la sentencia
$stmt = $con->prepare($sql);

// Verifica si ocurrió un error al preparar la sentencia
if (!$stmt) {
    die("Error al preparar la sentencia: " . $con->error);
}

// Vincula los parámetros a la sentencia preparada
//$stmt->bind_param("ssss", $quantity, $username, $diaSemana, $username);

$stmt->bind_param("ss", $quantity,$diaSemana);

// Ejecuta la sentencia
if ($stmt->execute()) {
     // La inserción fue exitosa
     echo "La petición se ejecutó con éxito";
     echo '<meta http-equiv="refresh" content="2; url=interfaz.php">';
     exit();
} else {
    // Ocurrió un error al ejecutar la sentencia
    echo "Error en la configuracion de parametros";
    echo '<meta http-equiv="refresh" content="2; url=interfaz.php">';
     exit();
}
// Cierra la sentencia preparada
$stmt->close();

// Cierra la conexión a la base de datos
$con->close();
?>
