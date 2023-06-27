<?php

include('connection.php'); // Incluye el archivo de conexión a la base de datos

echo '<link rel="stylesheet" href="css/bootstrap.min.css">'; //estilos para las tablas

//sentencia sql para obtner todos los datos de la tabla
$sql = "SELECT id, sensorName, location, readValue, reading_time FROM sensordata ORDER BY id DESC LIMIT 10";


echo '<table class="table  table-dark table-striped">

<thead>
<tr>
    
    <th class="bg-secondary text-center"> ID </th>
    <th class="bg-secondary text-center"> Sensor </th>
    <th class="bg-secondary text-center"> Ubicacion </th>
    <th class="bg-secondary text-center">lectura sensor </th>
    <th class="bg-secondary text-center"> timestap </th>
</tr>
</thead>';



if ($result = $con->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $row_id = $row["id"];
        $row_sensorName = $row["sensorName"];
        $row_location = $row["location"];
        $row_readValue = $row["readValue"];
        $row_reading_time = $row["reading_time"];

        // para servidor web Uncomment to set timezone to + 4 hours (you can change 4 to any number)
        $row_reading_time = date("Y-m-d H:i:s", strtotime("$row_reading_time - 5 hours"));
       
        echo '
        <tbody>
            <tr> 
            <td class="text-center"> ' . $row_id . '</td> 
            <td class="text-center"> ' . $row_sensorName . '</td> 
            <td class="text-center"> '. $row_location . '</td> 
            <td class="text-center"> ' . $row_readValue . '</td> 
            <td class="text-center"> '. $row_reading_time . '</td> 
            </tr>
        </tbody>';
    }
}
'</table>'; 