<?php
include('connection.php'); // Incluye el archivo de conexión a la base de datos
date_default_timezone_set('America/Bogota');

$hora_actual = date('H'); // Obtiene la hora actual en formato de 24 horas
$dia_actual = date("l");

//echo $dia_actual;
//echo $hora_actual; 

// Construir el nombre de la columna dinámicamente
$columnaHora = 'hour_'.$hora_actual; // Nombre de la columna específica concatenada

// Sentencia SQL para obtener el valor de la base de datos
$sql = "SELECT $columnaHora FROM requests WHERE dia = ?";
//echo $sql;

// Prepara la sentencia
$stmt = $con->prepare($sql);

// Verifica si ocurrió un error al preparar la sentencia
if (!$stmt) {
    die("Error al preparar la sentencia: " . $con->error);
}

// Vincula los parámetros a la sentencia preparada
$stmt->bind_param("s", $dia_actual);

// Ejecuta la consulta SQL
$stmt->execute();

// Obtiene el resultado de la consulta
$result = $stmt->get_result();

// Verificar si se obtuvieron resultados
if ($result->num_rows > 0) {
    // Obtener el valor de la cantidad de agua de la base de datos
    $row = $result->fetch_assoc();
    $cantidad = $row[$columnaHora]; // Utiliza la variable $columnaHora para acceder al valor correspondiente
 
    // Devolver el valor como una respuesta JSON
   // $respuesta = array($cantidad);
    //echo json_encode($respuesta);
    
    
    echo $cantidad;
  
 
} else {
    echo "No se encontraron resultados.";
}

// Cierra la sentencia preparada
$stmt->close();

// Cerrar la conexión
$con->close();
?>
