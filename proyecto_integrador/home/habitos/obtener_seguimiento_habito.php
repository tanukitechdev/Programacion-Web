<?php
    require_once '../../includes/verificar_sesion.php';
    require_once '../../includes/conexion.php';

    if (isset($_GET['id_habito'])) {
        $id_usuario = $_SESSION['usuario_id'];
        $id_habito = $_GET['id_habito'];
        $fecha_fin = date('Y-m-d');
        $fecha_inicio = date('Y-m-d', strtotime('-7 days'));

        $sql_seguimiento = "SELECT fecha_seguimiento, completado FROM seguimiento_habitos WHERE id_habito = :id_habito AND fecha_seguimiento BETWEEN :fecha_inicio AND :fecha_fin ORDER BY fecha_seguimiento ASC";
        $stmt_seguimiento = $conexion->prepare($sql_seguimiento);
        $stmt_seguimiento->bindParam(':id_habito', $id_habito);
        $stmt_seguimiento->bindParam(':fecha_inicio', $fecha_inicio);
        $stmt_seguimiento->bindParam(':fecha_fin', $fecha_fin);
        $stmt_seguimiento->execute();
        $seguimiento_habitos = $stmt_seguimiento->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($seguimiento_habitos);
    } else {
        echo json_encode([]);
    }
?>