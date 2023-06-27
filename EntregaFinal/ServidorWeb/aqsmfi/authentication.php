<?php
    session_start();
    include('connection.php'); // Incluye el archivo de conexión a la base de datos
    

    $username = $_POST['user']; // Obtiene el valor del campo de usuario del formulario enviado por POST (atributo name)
    $password = $_POST['pass']; // Obtiene el valor del campo de contraseña del formulario enviado por POST

    $username = mysqli_real_escape_string($con, $username); // Escapa los caracteres especiales para prevenir inyección de SQL

    $sql = "SELECT COUNT(*) AS count FROM login WHERE userName = '$username' AND password = UNHEX(SHA2('$password', 256))"; // Consulta SQL para verificar las credenciales

    $result = mysqli_query($con, $sql); // Ejecuta la consulta en la conexión a la base de datos
    $row = mysqli_fetch_assoc($result); // Obtiene el resultado de la consulta en un array asociativo
    $count = $row['count']; // Obtiene el valor de la columna 'count' del resultado

    if ($count == 1) { // Si se encontró una coincidencia en las credenciales
        // Guarda la información en la variable de sesión
        $_SESSION['username'] = $username;

        // Redirige al usuario a la página de inicio
        echo '<meta http-equiv="refresh" content="2; url=interfaz.php">';
        exit();
    } else {
        // Muestra un mensaje de error
        echo 'Usuario no encontrado, intente nuevamente.';
        echo '<meta http-equiv="refresh" content="2; url=sign_in.html">';
        exit();
    }

    mysqli_close($con); // Cierra la conexión a la base de datos
?>
