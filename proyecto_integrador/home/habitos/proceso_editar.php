<?php

require_once '../../includes/verificar_sesion.php';
require_once '../../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = $_SESSION['usuario_id'];

    // Verificar y obtener el ID del hábito a editar
    if (!isset($_POST['id_habito']) || !is_numeric($_POST['id_habito'])) {
        $_SESSION['error_editar_habitual'] = "ID de hábito inválido.";
        header("Location: editar.php?id=" . $_POST['id_habito']);
        exit();
    }
    $id_habito = $_POST['id_habito'];

    $nombre_habito = $_POST['nombre_habito'];
    $descripcion = $_POST['descripcion'];
    $frecuencia = $_POST['frecuencia'];
    $meta = $_POST['meta'];

    // Validaciones básicas
    if (empty($nombre_habito) || empty($frecuencia)) {
        $_SESSION['error_editar_habitual'] = "El nombre y la frecuencia del hábito son obligatorios.";
        header("Location: editar.php?id=" . $id_habito);
        exit();
    }

    // Preparar la consulta para actualizar el hábito
    $sql_update = "UPDATE habitos SET nombre_habito = :nombre_habito, descripcion = :descripcion, frecuencia = :frecuencia, meta = :meta WHERE id_habito = :id_habito AND id_usuario = :id_usuario";
    $stmt_update = $conexion->prepare($sql_update);
    $stmt_update->bindParam(':nombre_habito', $nombre_habito);
    $stmt_update->bindParam(':descripcion', $descripcion);
    $stmt_update->bindParam(':frecuencia', $frecuencia);
    $stmt_update->bindParam(':meta', $meta);
    $stmt_update->bindParam(':id_habito', $id_habito);
    $stmt_update->bindParam(':id_usuario', $id_usuario);

    if ($stmt_update->execute()) {
        $_SESSION['mensaje_habitual'] = "Hábito actualizado correctamente.";
        header("Location: index.php"); // Redirigir a la lista de hábitos
        exit();
    } else {
        $_SESSION['error_editar_habitual'] = "Error al actualizar el hábito. Por favor, intenta de nuevo.";
        header("Location: editar.php?id=" . $id_habito);
        exit();
    }

} else {
    // Si se intenta acceder directamente al archivo
    header("Location: index.php");
    exit();
}

?>