<?php

require_once '../../includes/verificar_sesion.php';
require_once '../../includes/conexion.php';

// Verificar si el usuario es administrador
if ($_SESSION['usuario_rol'] !== 'admin') {
    header("Location: ../index.php"); // Redirigir a la página principal si no es admin
    exit();
}

// Verificar si se ha proporcionado un ID de usuario para eliminar
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_eliminar_usuario'] = "ID de usuario inválido.";
    header("Location: index.php");
    exit();
}

$id_usuario_eliminar = $_GET['id'];

// Evitar que el administrador se elimine a sí mismo (opcional, pero recomendado)
if ($_SESSION['usuario_id'] == $id_usuario_eliminar) {
    $_SESSION['error_eliminar_usuario'] = "No puedes eliminar tu propia cuenta.";
    header("Location: index.php");
    exit();
}

// Preparar la consulta para eliminar el usuario
$sql_delete = "DELETE FROM usuarios WHERE id_usuario = :id";
$stmt_delete = $conexion->prepare($sql_delete);
$stmt_delete->bindParam(':id', $id_usuario_eliminar);

if ($stmt_delete->execute()) {
    $_SESSION['mensaje_eliminar_usuario'] = "Usuario eliminado correctamente.";
} else {
    $_SESSION['error_eliminar_usuario'] = "Error al eliminar el usuario. Por favor, intenta de nuevo.";
}

header("Location: index.php"); // Redirigir de vuelta a la lista de usuarios
exit();

?>