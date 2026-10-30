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
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validar que los campos no estén vacíos
    if (empty($email) || empty($password)) {
        $_SESSION['error_login'] = "Por favor, introduce tu correo electrónico y contraseña.";
        header("Location: login.php");
        exit();
    }

    // Preparar la consulta para buscar al usuario por su email
    $sql = "SELECT id_usuario, nombre, password, rol FROM usuarios WHERE email = :email";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':email', $email);

    try {
        $stmt->execute();

        // Verificar si se encontró un usuario con ese email
        if ($stmt->rowCount() == 1) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar la contraseña
            if (password_verify($password, $usuario['password'])) {
                // Iniciar sesión y guardar los datos del usuario en la sesión
                $_SESSION['usuario_id'] = $usuario['id_usuario'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_rol'] = $usuario['rol'];

                // Redirigir al usuario a la página de inicio de la aplicación (home/index.php)
                header("Location: home/index.php");
                exit();
            } else {
                // Contraseña incorrecta
                $_SESSION['error_login'] = "La contraseña es incorrecta.";
                header("Location: login.php");
                exit();
            }
        } else {
            // No se encontró ningún usuario con ese email
            $_SESSION['error_login'] = "No se encontró ningún usuario con ese correo electrónico.";
            header("Location: login.php");
            exit();
        }

    } catch (PDOException $e) {
        // Error en la consulta
        $_SESSION['error_login'] = "Error al verificar las credenciales: " . $e->getMessage();
        header("Location: login.php");
        exit();
    }

} else {
    // Si se intenta acceder a este archivo por GET, redirigir al formulario de login
    header("Location: login.php");
    exit();
}

?>