<?php
    require_once '../../includes/verificar_sesion.php';
    require_once '../../includes/conexion.php';

    // Verificar si el usuario es administrador
    if ($_SESSION['usuario_rol'] !== 'admin') {
        header("Location: ../index.php"); // Redirigir a la página principal si no es admin
        exit();
    }

    // Verificar si se ha proporcionado un ID de usuario para editar
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        header("Location: index.php"); // Redirigir a la lista de usuarios si no hay ID válido
        exit();
    }

    $id_usuario_editar = $_GET['id'];

    // Consulta para obtener la información del usuario a editar
    $sql = "SELECT id_usuario, nombre, email, rol FROM usuarios WHERE id_usuario = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id', $id_usuario_editar);
    $stmt->execute();
    $usuario_editar = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si no se encuentra el usuario, redirigir
    if (!$usuario_editar) {
        header("Location: index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="../../assets/css/bulma.min.css">
    <link rel="stylesheet" href="../../assets/css/miestilo.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    <?php include '../../includes/menu.php'; ?>

    <section class="section">
        <div class="container">
            <h1 class="title is-2" style="font-family: 'Playfair Display', serif;">
                Editar Usuario
            </h1>
            <p class="subtitle" style="font-family: 'Roboto', sans-serif;">
                Modifique la información del usuario.
            </p>

            <?php
                if (isset($_SESSION['error_editar_usuario'])) {
                    echo '<div class="notification is-danger is-light">' . $_SESSION['error_editar_usuario'] . '</div>';
                    unset($_SESSION['error_editar_usuario']);
                }
            ?>

            <form action="proceso_edicion.php" method="POST" class="box">
                <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($usuario_editar['id_usuario']); ?>">

                <div class="field">
                    <label class="label">Nuevo nombre de usuario</label>
                    <div class="control">
                        <input class="input" type="text" name="nombre" value="<?php echo htmlspecialchars($usuario_editar['nombre']); ?>" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Nuevo email</label>
                    <div class="control">
                        <input class="input" type="email" name="email" value="<?php echo htmlspecialchars($usuario_editar['email']); ?>" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Rol</label>
                    <div class="control">
                        <div class="select">
                            <select name="rol">
                                <option value="usuario" <?php if ($usuario_editar['rol'] == 'usuario') echo 'selected'; ?>>Usuario</option>
                                <option value="admin" <?php if ($usuario_editar['rol'] == 'admin') echo 'selected'; ?>>Administrador</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="field is-grouped">
                    <div class="control">
                        <button type="submit" class="button guardar-cambios-button">Guardar Cambios</button>
                    </div>
                    <div class="control">
                        <a href="index.php" class="button is-link is-light">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <?php include '../../includes/footer.php'; ?>
</body>
</html>