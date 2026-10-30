<?php

require_once '../../includes/verificar_sesion.php';
require_once '../../includes/conexion.php';

// Verificar si el usuario es administrador
if ($_SESSION['usuario_rol'] !== 'admin') {
    header("Location: ../index.php"); // Redirigir a la página principal si no es admin
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar y obtener el ID del usuario a editar
    if (!isset($_POST['id_usuario']) || !is_numeric($_POST['id_usuario'])) {
        $_SESSION['error_editar_usuario'] = "ID de usuario inválido.";
        header("Location: editar.php?id=" . $_POST['id_usuario']);
        exit();
    }
    $id_usuario = $_POST['id_usuario'];

    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $rol = $_POST['rol'];

    // Validaciones básicas
    if (empty($nombre) || empty($email) || empty($rol)) {
        $_SESSION['error_editar_usuario'] = "Todos los campos son obligatorios.";
        header("Location: editar.php?id=" . $id_usuario);
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_editar_usuario'] = "El formato del correo electrónico no es válido.";
        header("Location: editar.php?id=" . $id_usuario);
        exit();
    }

    // Verificar si el email ya existe para otro usuario
    $sql_check_email = "SELECT email FROM usuarios WHERE email = :email AND id_usuario != :id";
    $stmt_check_email = $conexion->prepare($sql_check_email);
    $stmt_check_email->bindParam(':email', $email);
    $stmt_check_email->bindParam(':id', $id_usuario);
    $stmt_check_email->execute();

    if ($stmt_check_email->rowCount() > 0) {
        $_SESSION['error_editar_usuario'] = "Este correo electrónico ya está registrado por otro usuario.";
        header("Location: editar.php?id=" . $id_usuario);
        exit();
    }

    // Preparar la consulta para actualizar el usuario
    $sql_update = "UPDATE usuarios SET nombre = :nombre, email = :email, rol = :rol WHERE id_usuario = :id";
    $stmt_update = $conexion->prepare($sql_update);
    $stmt_update->bindParam(':nombre', $nombre);
    $stmt_update->bindParam(':email', $email);
    $stmt_update->bindParam(':rol', $rol);
    $stmt_update->bindParam(':id', $id_usuario);

    if ($stmt_update->execute()) {
        $_SESSION['mensaje_editar_usuario'] = "Usuario actualizado correctamente.";
        header("Location: index.php"); // Redirigir a la lista de usuarios
        exit();
    } else {
        $_SESSION['error_editar_usuario'] = "Error al actualizar el usuario. Por favor, intenta de nuevo.";
        header("Location: editar.php?id=" . $id_usuario);
        exit();
    }

} else {
    // Si se intenta acceder directamente al archivo
    header("Location: index.php");
    exit();
}

?>