<?php
    session_start();

    // Verificar si el usuario ha iniciado sesión
    if (!isset($_SESSION['username'])) {
        // Si no ha iniciado sesión, redirigir al formulario de inicio de sesión
        echo '<meta http-equiv="refresh" content="2; url=sign_in.html">';
        exit();
    }

    // Obtener el nombre de usuario de la sesión
    $username = $_SESSION['username'];
?>


