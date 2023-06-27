<?php
include('connection.php'); // Incluir el archivo de conexión a la base de datos

$cantidad = $_POST['cantidad']; // Obtener el valor del campo de cantidad del formulario enviado por POST
$sensorname = $_POST['sensorname']; // Obtener el valor del campo de sensor del formulario enviado por POST

$sql = "SELECT id, sensorName, location, readValue, reading_time FROM sensordata WHERE sensorName = ? ORDER BY id DESC LIMIT ?";

// Preparar la consulta
$stmt = mysqli_prepare($con, $sql);

// Vincular los parámetros con los valores
mysqli_stmt_bind_param($stmt, "ss", $sensorname, $cantidad);

// Ejecutar la consulta preparada
mysqli_stmt_execute($stmt);

// Obtener el resultado de la consulta
$result = mysqli_stmt_get_result($stmt);

echo '<link rel="stylesheet" href="css/bootstrap.min.css">';

echo '<table class="table table-dark table-striped">
<thead>
<tr>    
    <th class="bg-secondary text-center"> ID </th>
    <th class="bg-secondary text-center"> Sensor </th>
    <th class="bg-secondary text-center"> Ubicacion </th>
    <th class="bg-secondary text-center"> Lectura Sensor </th>
    <th class="bg-secondary text-center"> Timestap </th>
</tr>
</thead>
<tbody>';

while ($row = mysqli_fetch_assoc($result)) {
    $row_id = $row["id"];
    $row_sensorName = $row["sensorName"];
    $row_location = $row["location"];
    $row_readValue = $row["readValue"];
    $row_reading_time = $row["reading_time"];
    
    // Convertir la fecha y hora a la zona horaria adecuada
    $row_reading_time = date("Y-m-d H:i:s", strtotime("$row_reading_time - 5 hours"));
       
    echo '
    <tr> 
        <td class="text-center">' . $row_id . '</td> 
        <td class="text-center">' . $row_sensorName . '</td> 
        <td class="text-center">' . $row_location . '</td> 
        <td class="text-center">' . $row_readValue . '</td> 
        <td class="text-center">' . $row_reading_time . '</td> 
    </tr>';
}

echo '</tbody>
</table>';
?>

