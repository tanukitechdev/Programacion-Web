<?php

require_once '../../includes/verificar_sesion.php';
require_once '../../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = $_SESSION['usuario_id'];
    $nombre_habito = $_POST['nombre_habito'];
    $descripcion = $_POST['descripcion'];
    $frecuencia = $_POST['frecuencia'];
    $meta = $_POST['meta'];

    // Validaciones básicas
    if (empty($nombre_habito) || empty($frecuencia)) {
        $_SESSION['error_crear_habitual'] = "El nombre y la frecuencia del hábito son obligatorios.";
        header("Location: crear.php");
        exit();
    }

    // Preparar la consulta para insertar el nuevo hábito
    $sql_insert = "INSERT INTO habitos (id_usuario, nombre_habito, descripcion, frecuencia, meta) VALUES (:id_usuario, :nombre_habito, :descripcion, :frecuencia, :meta)";
    $stmt_insert = $conexion->prepare($sql_insert);
    $stmt_insert->bindParam(':id_usuario', $id_usuario);
    $stmt_insert->bindParam(':nombre_habito', $nombre_habito);
    $stmt_insert->bindParam(':descripcion', $descripcion);
    $stmt_insert->bindParam(':frecuencia', $frecuencia);
    $stmt_insert->bindParam(':meta', $meta);

    if ($stmt_insert->execute()) {
        $_SESSION['mensaje_habitual'] = "Hábito creado correctamente.";
        header("Location: index.php"); // Redirigir a la lista de hábitos
        exit();
    } else {
        $_SESSION['error_crear_habitual'] = "Error al crear el hábito. Por favor, intenta de nuevo.";
        header("Location: crear.php");
        exit();
    }

} else {
    // Si se intenta acceder directamente al archivo
    header("Location: crear.php");
    exit();
}

?>