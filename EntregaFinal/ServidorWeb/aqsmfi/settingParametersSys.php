<!DOCTYPE html>
<html><body>
<?php
include('connection.php'); // Incluye el archivo de conexión a la base de datos

if(isset($_POST['submit'])) {
$id = $_POST['id'];
$nombre_parametro = $_POST['nombre_parametro'];
$valor_parametro = $_POST['valor_parametro'];

$sql = "UPDATE settingParameters SET parameterValue='$valor_parametro' WHERE id='$id' AND parameterName='$nombre_parametro'";

if ($con->query($sql) === TRUE) {
echo "El parámetro se ha actualizado correctamente.";
// Esperar 2 segundos antes de redireccionar
header("refresh:2;url=settingParametersSys.php");

} else {
echo "Error: " . $sql . "<br>" . $con->error;
}
}

$sql = "SELECT * FROM settingParameters";
$result = $con->query($sql);

echo '<table cellspacing="5" cellpadding="5">
<tr>
<td>ID</td>
<td>Nombre del parámetro</td>
<td>Valor del parámetro</td>
</tr>';

if ($result->num_rows > 0) {
while ($row = $result->fetch_assoc()) {
$row_id = $row["id"];
$row_nombre_parametro = $row["parameterName"];
$row_valor_parametro = $row["parameterValue"];
    echo '<form method="post">
            <tr> 
              <td>' . $row_id . '</td> 
              <td>' . $row_nombre_parametro . '</td> 
              <td><input type="text" name="valor_parametro" value="' . $row_valor_parametro . '"></td> 
              <td><input type="hidden" name="id" value="' . $row_id . '"></td>
              <td><input type="hidden" name="nombre_parametro" value="' . $row_nombre_parametro . '"></td>
              <td><input type="submit" name="submit" value="Actualizar"></td>
            </tr>
          </form>';
}
} else {


echo "No se encontraron resultados.";


}

$con->close();
?>

</table>
</body>
</html>