<?php

require_once '../../includes/verificar_sesion.php';
require_once '../../includes/conexion.php';

$id_usuario = $_SESSION['usuario_id'];

// Verificar si se ha proporcionado un ID de hábito para eliminar
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_habitual'] = "ID de hábito inválido.";
    header("Location: index.php");
    exit();
}

$id_habito_eliminar = $_GET['id'];

// Verificar si el hábito pertenece al usuario actual
$sql_check_habito = "SELECT id_habito FROM habitos WHERE id_habito = :id AND id_usuario = :id_usuario";
$stmt_check_habito = $conexion->prepare($sql_check_habito);
$stmt_check_habito->bindParam(':id', $id_habito_eliminar);
$stmt_check_habito->bindParam(':id_usuario', $id_usuario);
$stmt_check_habito->execute();

if ($stmt_check_habito->rowCount() == 0) {
    $_SESSION['error_habitual'] = "No tienes permiso para eliminar este hábito.";
    header("Location: index.php");
    exit();
}

// Preparar la consulta para eliminar el hábito
$sql_delete = "DELETE FROM habitos WHERE id_habito = :id";
$stmt_delete = $conexion->prepare($sql_delete);
$stmt_delete->bindParam(':id', $id_habito_eliminar);

if ($stmt_delete->execute()) {
    $_SESSION['mensaje_habitual'] = "Hábito eliminado correctamente.";
} else {
    $_SESSION['error_habitual'] = "Error al eliminar el hábito. Por favor, intenta de nuevo.";
}

header("Location: index.php"); // Redirigir de vuelta a la lista de hábitos
exit();

?>