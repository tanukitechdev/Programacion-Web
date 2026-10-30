<?php

require_once '../../includes/verificar_sesion.php';
require_once '../../includes/conexion.php';

// Verificar si el usuario es administrador
if ($_SESSION['usuario_rol'] !== 'admin') {
    header("Location: ../index.php"); // Redirigir a la página principal si no es admin
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];

    // Validaciones básicas
    if (empty($nombre) || empty($email) || empty($password) || empty($rol)) {
        $_SESSION['error_registro_usuario'] = "Todos los campos son obligatorios.";
        header("Location: registrar.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_registro_usuario'] = "El formato del correo electrónico no es válido.";
        header("Location: registrar.php");
        exit();
    }

    if (strlen($password) < 6) {
        $_SESSION['error_registro_usuario'] = "La contraseña debe tener al menos 6 caracteres.";
        header("Location: registrar.php");
        exit();
    }

    // Verificar si el email ya existe
    $sql_check_email = "SELECT email FROM usuarios WHERE email = :email";
    $stmt_check_email = $conexion->prepare($sql_check_email);
    $stmt_check_email->bindParam(':email', $email);
    $stmt_check_email->execute();

    if ($stmt_check_email->rowCount() > 0) {
        $_SESSION['error_registro_usuario'] = "Este correo electrónico ya está registrado.";
        header("Location: registrar.php");
        exit();
    }

    // Hashear la contraseña
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Insertar el nuevo usuario
    $sql_insert = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (:nombre, :email, :password, :rol)";
    $stmt_insert = $conexion->prepare($sql_insert);
    $stmt_insert->bindParam(':nombre', $nombre);
    $stmt_insert->bindParam(':email', $email);
    $stmt_insert->bindParam(':password', $password_hash);
    $stmt_insert->bindParam(':rol', $rol);

    if ($stmt_insert->execute()) {
        $_SESSION['mensaje_registro_usuario'] = "Usuario registrado correctamente.";
        header("Location: index.php"); // Redirigir a la lista de usuarios
        exit();
    } else {
        $_SESSION['error_registro_usuario'] = "Error al registrar el usuario. Por favor, intenta de nuevo.";
        header("Location: registrar.php");
        exit();
    }

} else {
    // Si se intenta acceder directamente al archivo
    header("Location: registrar.php");
    exit();
}

?>