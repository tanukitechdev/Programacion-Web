<?php
    require_once '../../includes/verificar_sesion.php';
    require_once '../../includes/conexion.php';

    // Consulta para obtener el ranking de usuarios por hábitos completados
    $sql_ranking = "SELECT
                        u.id_usuario,
                        u.nombre,
                        COUNT(sh.id_habito) AS total_completados
                    FROM usuarios u
                    LEFT JOIN habitos h ON u.id_usuario = h.id_usuario
                    LEFT JOIN seguimiento_habitos sh ON h.id_habito = sh.id_habito AND sh.completado = 1
                    GROUP BY u.id_usuario
                    ORDER BY total_completados DESC";
    $stmt_ranking = $conexion->prepare($sql_ranking);
    $stmt_ranking->execute();
    $ranking_usuarios = $stmt_ranking->fetchAll(PDO::FETCH_ASSOC);

    $primer_usuario_id = null;
    if ($ranking_usuarios) {
        $primer_usuario_id = $ranking_usuarios[0]['id_usuario'];
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking de Productividad</title>
    <link rel="stylesheet" href="../../assets/css/bulma.min.css">
    <link rel="stylesheet" href="../../assets/css/miestilo.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .insignia {
            color: gold;
            font-size: 1.2em;
            margin-left: 0.5em;
        }
    </style>
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    <?php include '../../includes/menu.php'; ?>

    <section class="section ranking-productividad">
        <div class="container">
            <h1 class="title" style="font-family: 'Playfair Display', serif;">
                Ranking de Productividad
            </h1>
            <p class="subtitle">
                Usuarios ordenados por la cantidad de hábitos completados.
            </p>

            <table class="table is-striped is-fullwidth">
                <thead>
                    <tr>
                        <th>Posición</th>
                        <th>Usuario</th>
                        <th>Hábitos Completados</th>
                        <th>Insignia</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($ranking_usuarios) > 0): ?>
                        <?php $posicion = 1; ?>
                        <?php foreach ($ranking_usuarios as $usuario): ?>
                            <tr>
                                <td><?php echo $posicion++; ?></td>
                                <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['total_completados']); ?></td>
                                <td>
                                    <?php if ($usuario['id_usuario'] == $primer_usuario_id): ?>
                                        <span class="insignia" title="Usuario más productivo"><i class="fas fa-trophy"></i></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="has-text-centered">No hay datos de seguimiento disponibles aún.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <?php include '../../includes/footer.php'; ?>
</body>
</html>