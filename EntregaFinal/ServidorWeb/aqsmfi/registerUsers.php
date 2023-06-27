<?php
    include('connection.php'); // Incluye el archivo de conexión a la base de datos

    $user = $_POST['user']; // Obtiene el valor del campo de usuario del formulario enviado por POST
    $username = $_POST['userName'];
    $password = $_POST['pass']; // Obtiene el valor del campo de contraseña del formulario enviado por POST

    // Prepara la consulta SQL con sentencia preparada
    $sql = "INSERT INTO login (name, userName, password) VALUES (?, ?, UNHEX(SHA2(?, 256)))";

    // Prepara la sentencia
    $stmt = mysqli_prepare($con, $sql);

    // Vincula los parámetros con los valores
    mysqli_stmt_bind_param($stmt, "sss", $user, $username, $password);

    // Ejecuta la sentencia preparada
    $result = mysqli_stmt_execute($stmt);

    if ($result) { // Si la consulta se ejecutó correctamente
        echo "Registro insertado exitosamente."; // Muestra un mensaje de éxito
        echo '<meta http-equiv="refresh" content="2; url=sign_in.html">';
    } else {
        echo "Error al insertar el registro: " . mysqli_error($con); // Muestra un mensaje de error 
        echo '<meta http-equiv="refresh" content="2; url=sign_up.html">';
    }

    mysqli_stmt_close($stmt); // Cierra la sentencia preparada
    mysqli_close($con); // Cierra la conexión a la base de datos
?>