<?php
session_start();

// Destruir todas las variables de sesión
session_destroy();

// Redirigir al formulario de inicio de sesión
header("Location:sign_in.html");
exit();
?>