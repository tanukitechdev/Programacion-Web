<?php
    require_once '../../includes/verificar_sesion.php';
    require_once '../../includes/conexion.php';

    $id_usuario = $_SESSION['usuario_id'];

    // Consulta para obtener los hábitos del usuario actual
    $sql = "SELECT id_habito, nombre_habito, descripcion, frecuencia, meta, fecha_creacion FROM habitos WHERE id_usuario = :id_usuario ORDER BY fecha_creacion DESC";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id_usuario', $id_usuario);
    $stmt->execute();
    $habitos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener los hábitos completados hoy para marcarlos visualmente
    $fecha_hoy = date('Y-m-d');
    $sql_completados_hoy = "SELECT sh.id_habito FROM seguimiento_habitos sh INNER JOIN habitos h ON sh.id_habito = h.id_habito WHERE h.id_usuario = :id_usuario AND sh.fecha_seguimiento = :fecha_hoy AND sh.completado = 1";
    $stmt_completados_hoy = $conexion->prepare($sql_completados_hoy);
    $stmt_completados_hoy->bindParam(':id_usuario', $id_usuario);
    $stmt_completados_hoy->bindParam(':fecha_hoy', $fecha_hoy);
    $stmt_completados_hoy->execute();
    $habitos_completados_hoy = $stmt_completados_hoy->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Hábitos</title>
    <link rel="stylesheet" href="../../assets/css/bulma.min.css">
    <link rel="stylesheet" href="../../assets/css/miestilo.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    <?php include '../../includes/menu.php'; ?>

    <section class="section habitos-index">
        <div class="container">
            <h1 class="title">
                Mis Hábitos
            </h1>
            <p class="subtitle">
                Gestiona tus hábitos diarios y semanales.
            </p>

            <?php
                if (isset($_SESSION['mensaje_habitual'])) {
                    echo '<div class="notification is-success is-light">' . $_SESSION['mensaje_habitual'] . '</div>';
                    unset($_SESSION['mensaje_habitual']);
                }
                if (isset($_SESSION['error_habitual'])) {
                    echo '<div class="notification is-danger is-light">' . $_SESSION['error_habitual'] . '</div>';
                    unset($_SESSION['error_habitual']);
                }
            ?>

            <a href="crear.php" class="button is-primary mb-4">
                <span class="icon">
                    <i class="fas fa-plus"></i>
                </span>
                <span>Crear Nuevo Hábito</span>
            </a>

            <table class="table is-striped is-fullwidth">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Frecuencia</th>
                        <th>Meta</th>
                        <th>Fecha de Creación</th>
                        <th>Completado Hoy</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($habitos) > 0): ?>
                        <?php foreach ($habitos as $habito): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($habito['nombre_habito']); ?></td>
                                <td><?php echo htmlspecialchars($habito['descripcion']); ?></td>
                                <td><?php echo htmlspecialchars(ucfirst($habito['frecuencia'])); ?></td>
                                <td><?php echo htmlspecialchars($habito['meta']); ?></td>
                                <td><?php echo htmlspecialchars($habito['fecha_creacion']); ?></td>
                                <td>
                                    <form method="POST" action="marcar_completado.php">
                                        <input type="hidden" name="id_habito" value="<?php echo htmlspecialchars($habito['id_habito']); ?>">
                                        <input type="hidden" name="fecha_seguimiento" value="<?php echo htmlspecialchars($fecha_hoy); ?>">
                                        <input type="hidden" name="completado" value="<?php echo in_array($habito['id_habito'], $habitos_completados_hoy) ? '0' : '1'; ?>">
                                        <button type="submit" class="button is-small <?php echo in_array($habito['id_habito'], $habitos_completados_hoy) ? 'is-success' : 'is-light'; ?>">
                                            <span class="icon">
                                                <i class="fas <?php echo in_array($habito['id_habito'], $habitos_completados_hoy) ? 'fa-check-circle' : 'fa-circle'; ?>"></i>
                                            </span>
                                            <span><?php echo in_array($habito['id_habito'], $habitos_completados_hoy) ? 'Completado' : 'Pendiente'; ?></span>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <a href="editar.php?id=<?php echo htmlspecialchars($habito['id_habito']); ?>" class="button is-small is-info">
                                        <span class="icon">
                                            <i class="fas fa-edit"></i>
                                        </span>
                                        <span>Editar</span>
                                    </a>
                                    <a href="eliminar.php?id=<?php echo htmlspecialchars($habito['id_habito']); ?>" class="button is-small is-danger">
                                        <span class="icon">
                                            <i class="fas fa-trash"></i>
                                        </span>
                                        <span>Eliminar</span>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="has-text-centered">No hay actividades registradas >:( </td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <?php include '../../includes/footer.php'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const formsMarcarCompletado = document.querySelectorAll('.habitos-index form[action="marcar_completado.php"]');

            formsMarcarCompletado.forEach(form => {
                form.addEventListener('submit', async (event) => {
                    event.preventDefault(); // Evitar la recarga completa de la página

                    const formData = new FormData(form);
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Actualizar visualmente el botón en la página sin recargar
                        const button = form.querySelector('button');
                        const span = button.querySelector('span:last-child');
                        const icon = button.querySelector('.icon i');
                        const completadoHiddenInput = form.querySelector('input[name="completado"]');

                        if (completadoHiddenInput.value === '1') {
                            button.classList.remove('is-light');
                            button.classList.add('is-success');
                            icon.classList.remove('fa-circle');
                            icon.classList.add('fa-check-circle');
                            span.textContent = 'Completado';
                            completadoHiddenInput.value = '0'; // Cambiar el valor para la próxima vez
                        } else {
                            button.classList.remove('is-success');
                            button.classList.add('is-light');
                            icon.classList.remove('fa-check-circle');
                            icon.classList.add('fa-circle');
                            span.textContent = 'Pendiente';
                            completadoHiddenInput.value = '1'; // Cambiar el valor para la próxima vez
                        }
                    } else {
                        console.error('Error al marcar el hábito:', data.error);
                        // Opcional: Mostrar un mensaje de error al usuario
                    }
                });
            });
        });
    </script>
</body>
</html>