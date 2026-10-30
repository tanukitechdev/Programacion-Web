<?php

// Iniciar la sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si la variable de sesión del usuario no está establecida
if (!isset($_SESSION['usuario_id'])) {
    // Si no hay sesión activa, redirigir al usuario a la página de inicio de sesión
    header("Location: ../login.php");
    exit();
}

?>