<?php

// Incluir el archivo de conexión a la base de datos
require_once 'includes/conexion.php';

// Iniciar la sesión (si aún no está iniciada)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si se han recibido datos por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validar que los campos no estén vacíos
    if (empty($nombre) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['error_registro'] = "Todos los campos son obligatorios.";
        header("Location: sing_up.php");
        exit();
    }

    // Validar formato del correo electrónico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_registro'] = "El formato del correo electrónico no es válido.";
        header("Location: sing_up.php");
        exit();
    }

    // Validar que las contraseñas coincidan
    if ($password !== $confirm_password) {
        $_SESSION['error_registro'] = "Las contraseñas no coinciden.";
        header("Location: sing_up.php");
        exit();
    }

    // Validar la longitud de la contraseña (puedes ajustar los requisitos)
    if (strlen($password) < 6) {
        $_SESSION['error_registro'] = "La contraseña debe tener al menos 6 caracteres.";
        header("Location: sing_up.php");
        exit();
    }

    // Verificar si el correo electrónico ya existe en la base de datos
    $sql_check_email = "SELECT email FROM usuarios WHERE email = :email";
    $stmt_check_email = $conexion->prepare($sql_check_email);
    $stmt_check_email->bindParam(':email', $email);
    $stmt_check_email->execute();

    if ($stmt_check_email->rowCount() > 0) {
        $_SESSION['error_registro'] = "Este correo electrónico ya está registrado.";
        header("Location: sing_up.php");
        exit();
    }

    // Hashear la contraseña de forma segura
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Preparar la consulta para insertar el nuevo usuario
    $sql_insert = "INSERT INTO usuarios (nombre, email, password) VALUES (:nombre, :email, :password)";
    $stmt_insert = $conexion->prepare($sql_insert);
    $stmt_insert->bindParam(':nombre', $nombre);
    $stmt_insert->bindParam(':email', $email);
    $stmt_insert->bindParam(':password', $password_hash);

    try {
        if ($stmt_insert->execute()) {
            // Registro exitoso, puedes redirigir al usuario a la página de inicio de sesión con un mensaje de éxito
            $_SESSION['registro_exitoso'] = "¡Registro completado con éxito! Ahora puedes iniciar sesión.";
            header("Location: login.php");
            exit();
        } else {
            // Error al registrar el usuario
            $_SESSION['error_registro'] = "Error al registrar el usuario. Por favor, intenta de nuevo.";
            header("Location: sing_up.php");
            exit();
        }
    } catch (PDOException $e) {
        // Error en la consulta de inserción
        $_SESSION['error_registro'] = "Error al registrar el usuario: " . $e->getMessage();
        header("Location: sing_up.php");
        exit();
    }

} else {
    // Si se intenta acceder a este archivo por GET, redirigir al formulario de registro
    header("Location: sing_up.php");
    exit();
}

?>