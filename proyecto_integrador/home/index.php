<?php
    require_once '../includes/verificar_sesion.php';
    require_once '../includes/conexion.php';

    $id_usuario = $_SESSION['usuario_id'];
    $fecha_hoy = date('Y-m-d');

    // Obtener todos los hábitos del usuario
    $sql_habitos = "SELECT id_habito FROM habitos WHERE id_usuario = :id_usuario";
    $stmt_habitos = $conexion->prepare($sql_habitos);
    $stmt_habitos->bindParam(':id_usuario', $id_usuario);
    $stmt_habitos->execute();
    $habitos = $stmt_habitos->fetchAll(PDO::FETCH_COLUMN);

    $habitos_completados_hoy = 0;
    $habitos_pendientes_hoy = 0;

    if ($habitos) {
        // Contar hábitos completados hoy
        $sql_completados = "SELECT COUNT(sh.id_habito) FROM seguimiento_habitos sh INNER JOIN habitos h ON sh.id_habito = h.id_habito WHERE h.id_usuario = :id_usuario AND sh.fecha_seguimiento = :fecha_hoy AND sh.completado = 1";
        $stmt_completados = $conexion->prepare($sql_completados);
        $stmt_completados->bindParam(':id_usuario', $id_usuario);
        $stmt_completados->bindParam(':fecha_hoy', $fecha_hoy);
        $stmt_completados->execute();
        $habitos_completados_hoy = $stmt_completados->fetchColumn();

        // Contar hábitos pendientes hoy (asumimos que si no está en seguimiento como completado, está pendiente)
        $habitos_pendientes_hoy = count($habitos) - $habitos_completados_hoy;
    }

    // Obtener el número de hábitos registrados por día (últimos 7 días)
    $habitos_por_dia = [];
    for ($i = 6; $i >= 0; $i--) {
        $fecha = date('Y-m-d', strtotime("-$i days"));
        $sql_conteo_habitos = "SELECT COUNT(id_habito) FROM habitos WHERE id_usuario = :id_usuario AND DATE(fecha_creacion) = :fecha";
        $stmt_conteo_habitos = $conexion->prepare($sql_conteo_habitos);
        $stmt_conteo_habitos->bindParam(':id_usuario', $id_usuario);
        $stmt_conteo_habitos->bindParam(':fecha', $fecha);
        $stmt_conteo_habitos->execute();
        $conteo = $stmt_conteo_habitos->fetchColumn();
        $habitos_por_dia[$fecha] = $conteo;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Principal</title>
    <link rel="stylesheet" href="../assets/css/bulma.min.css">
    <link rel="stylesheet" href="../assets/css/miestilo.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <?php include '../includes/menu.php'; ?>

    <section class="section">
        <div class="container">
            <h1 class="title is-2">
                ¡Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Usuario'); ?>!
            </h1>
            <p class="subtitle">
                Un vistazo rápido.
            </p>

            <div class="box grafica-container">
                <h2 class="title is-4">Resumen de tus actividades</h2>
                <canvas id="progreso-hoy"></canvas>
            </div>

            <div class="box grafica-container">
                <canvas id="actividades-registradas"></canvas>
                <h2 class="title is-4">Actividades Registradas (Últimos 7 días)</h2>
            </div>
        </div>
    </section>

    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Gráfica de pastel
            const ctxProgresoHoy = document.getElementById('progreso-hoy').getContext('2d');
            const dataProgresoHoy = {
                labels: ['Completados', 'Pendientes'],
                datasets: [{
                    label: 'Estado de Hábitos Hoy',
                    data: [<?php echo $habitos_completados_hoy; ?>, <?php echo $habitos_pendientes_hoy; ?>],
                    backgroundColor: [
                        'rgba(1, 46, 64, 1)',
                        'rgba(2, 103, 115, 1)'
                    ],
                    borderColor: [
                        'rgba(1, 46, 64, 1)',
                        'rgba(2, 103, 115, 1)'
                    ],
                    borderWidth: 1
                }]
            };
            const progresoHoyChart = new Chart(ctxProgresoHoy, {
                type: 'pie',
                data: dataProgresoHoy,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        tooltip: {
                            callbacks: {
                                label: (context) => {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = dataProgresoHoy.datasets[0].data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? Math.round((value / total) * 100) + '%' : '0%';
                                    return `${label}: ${value} (${percentage})`;
                                }
                            }
                        }
                    }
                }
            });

            // Gráfica de líneas
            const ctxActividades = document.getElementById('actividades-registradas').getContext('2d');
            const dataActividades = {
                labels: [
                    <?php
                        foreach ($habitos_por_dia as $fecha => $conteo) {
                            echo "'" . htmlspecialchars($fecha) . "',";
                        }
                    ?>
                ],
                datasets: [{
                    label: 'Número de Actividades Registradas por día',
                    data: [
                        <?php
                            foreach ($habitos_por_dia as $fecha => $conteo) {
                                echo htmlspecialchars($conteo) . ",";
                            }
                        ?>
                    ],
                    borderColor: 'rgba(2, 103, 115, 1)',
                    backgroundColor: 'rgba(1, 46, 64, 1)',
                    borderWidth: 2
                }]
            };
            const actividadesChart = new Chart(ctxActividades, {
                type: 'line',
                data: dataActividades,
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>