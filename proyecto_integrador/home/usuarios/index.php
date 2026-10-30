<?php
    require_once '../../includes/verificar_sesion.php';
    require_once '../../includes/conexion.php';

    // Verificar si el usuario es administrador
    if ($_SESSION['usuario_rol'] !== 'admin') {
        header("Location: ../index.php"); // Redirigir a la página principal si no es admin
        exit();
    }

    // Consulta para obtener la lista de usuarios
    $sql = "SELECT id_usuario, nombre, email, rol, fecha_registro FROM usuarios";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="../../assets/css/bulma.min.css">
    <link rel="stylesheet" href="../../assets/css/miestilo.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    <?php include '../../includes/menu.php'; ?>

    <section class="section usuarios-index">
        <div class="container">
            <h1 class="title is-2" style="font-family: 'Playfair Display', serif;">
                Gestión de Usuarios
            </h1>
            <p class="subtitle" style="font-family: 'Roboto', sans-serif;">
                Lista de usuarios registrados en Habitus.
            </p>

            <?php
                if (isset($_SESSION['mensaje_registro_usuario'])) {
                    echo '<div class="notification is-success is-light">' . $_SESSION['mensaje_registro_usuario'] . '</div>';
                    unset($_SESSION['mensaje_registro_usuario']); // Limpiar el mensaje de éxito
                }
                if (isset($_SESSION['mensaje_registro_usuario'])) {
                    echo '<div class="notification is-success is-light">' . $_SESSION['mensaje_registro_usuario'] . '</div>';
                    unset($_SESSION['mensaje_registro_usuario']);
                }
                if (isset($_SESSION['mensaje_editar_usuario'])) {
                    echo '<div class="notification is-success is-light">' . $_SESSION['mensaje_editar_usuario'] . '</div>';
                    unset($_SESSION['mensaje_editar_usuario']);
                }
                if (isset($_SESSION['error_eliminar_usuario'])) {
                    echo '<div class="notification is-danger is-light">' . $_SESSION['error_eliminar_usuario'] . '</div>';
                    unset($_SESSION['error_eliminar_usuario']);
                }
                if (isset($_SESSION['mensaje_eliminar_usuario'])) {
                echo '<div class="notification is-success is-light">' . $_SESSION['mensaje_eliminar_usuario'] . '</div>';
                unset($_SESSION['mensaje_eliminar_usuario']);
                }
            ?>

            <a href="registrar.php" class="button is-primary mb-4">
                <span class="icon">
                    <i class="fas fa-user-plus"></i>
                </span>
                <span>Registrar Nuevo Usuario</span>
            </a>

            <table class="table is-striped is-fullwidth">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Fecha de Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($usuarios) > 0): ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($usuario['id_usuario']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                <td><?php echo htmlspecialchars(ucfirst($usuario['rol'])); ?></td>
                                <td><?php echo htmlspecialchars($usuario['fecha_registro']); ?></td>
                                <td>
                                    <a href="editar.php?id=<?php echo htmlspecialchars($usuario['id_usuario']); ?>" class="button is-small is-info">
                                        <span class="icon">
                                            <i class="fas fa-edit"></i>
                                        </span>
                                        <span>Editar</span>
                                    </a>
                                    <a href="eliminar.php?id=<?php echo htmlspecialchars($usuario['id_usuario']); ?>" class="button is-small is-danger">
                                        <span class="icon">
                                            <i class="fas fa-trash"></i>
                                        </span>
                                        <span>Eliminar</span>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="has-text-centered">No hay usuarios registrados.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <?php include '../../includes/footer.php'; ?>
</body>
</html>