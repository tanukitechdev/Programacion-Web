<?php

require_once '../../includes/verificar_sesion.php';
require_once '../../includes/conexion.php';

header('Content-Type: application/json'); // Establecer el tipo de contenido a JSON

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = $_SESSION['usuario_id'];
    $id_habito = $_POST['id_habito'];
    $fecha_seguimiento = $_POST['fecha_seguimiento'];
    $completado = $_POST['completado'];

    // Validar datos recibidos
    if (!is_numeric($id_habito) || empty($fecha_seguimiento) || !in_array($completado, ['0', '1'])) {
        echo json_encode(['success' => false, 'error' => 'Datos de seguimiento inválidos.']);
        exit();
    }

    // Verificar si el hábito pertenece al usuario
    $sql_check_habito = "SELECT id_habito FROM habitos WHERE id_habito = :id_habito AND id_usuario = :id_usuario";
    $stmt_check_habito = $conexion->prepare($sql_check_habito);
    $stmt_check_habito->bindParam(':id_habito', $id_habito);
    $stmt_check_habito->bindParam(':id_usuario', $id_usuario);
    $stmt_check_habito->execute();

    if ($stmt_check_habito->rowCount() == 0) {
        echo json_encode(['success' => false, 'error' => 'No tienes permiso para marcar este hábito.']);
        exit();
    }

    // Verificar si ya existe un registro para este hábito en esta fecha
    $sql_check_seguimiento = "SELECT id_seguimiento FROM seguimiento_habitos WHERE id_habito = :id_habito AND fecha_seguimiento = :fecha_seguimiento";
    $stmt_check_seguimiento = $conexion->prepare($sql_check_seguimiento);
    $stmt_check_seguimiento->bindParam(':id_habito', $id_habito);
    $stmt_check_seguimiento->bindParam(':fecha_seguimiento', $fecha_seguimiento);
    $stmt_check_seguimiento->execute();

    if ($stmt_check_seguimiento->rowCount() > 0) {
        // Si existe, actualizar el estado
        $sql_update_seguimiento = "UPDATE seguimiento_habitos SET completado = :completado WHERE id_habito = :id_habito AND fecha_seguimiento = :fecha_seguimiento";
        $stmt_update_seguimiento = $conexion->prepare($sql_update_seguimiento);
        $stmt_update_seguimiento->bindParam(':completado', $completado);
        $stmt_update_seguimiento->bindParam(':id_habito', $id_habito);
        $stmt_update_seguimiento->bindParam(':fecha_seguimiento', $fecha_seguimiento);

        if ($stmt_update_seguimiento->execute()) {
            echo json_encode(['success' => true, 'id_habito' => $id_habito]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al actualizar el estado del hábito.']);
        }
    } else {
        // Si no existe, crear un nuevo registro
        $sql_insert_seguimiento = "INSERT INTO seguimiento_habitos (id_habito, fecha_seguimiento, completado) VALUES (:id_habito, :fecha_seguimiento, :completado)";
        $stmt_insert_seguimiento = $conexion->prepare($sql_insert_seguimiento);
        $stmt_insert_seguimiento->bindParam(':id_habito', $id_habito);
        $stmt_insert_seguimiento->bindParam(':fecha_seguimiento', $fecha_seguimiento);
        $stmt_insert_seguimiento->bindParam(':completado', $completado);

        if ($stmt_insert_seguimiento->execute()) {
            echo json_encode(['success' => true, 'id_habito' => $id_habito]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al guardar el estado del hábito.']);
        }
    }

    exit();

} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit();
}

?>