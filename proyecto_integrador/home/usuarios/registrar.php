<?php
    require_once '../../includes/verificar_sesion.php';

    // Verificar si el usuario es administrador
    if ($_SESSION['usuario_rol'] !== 'admin') {
        header("Location: ../index.php"); // Redirigir a la página principal si no es admin
        exit();
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Nuevo Usuario</title>
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
                Registrar Nuevo Usuario
            </h1>
            <p class="subtitle" style="font-family: 'Roboto', sans-serif;">
                Ingrese la información del nuevo usuario.
            </p>

            <?php
                if (isset($_SESSION['error_registro_usuario'])) {
                    echo '<div class="notification is-danger is-light">' . $_SESSION['error_registro_usuario'] . '</div>';
                    unset($_SESSION['error_registro_usuario']); // Limpiar el mensaje de error
                }
            ?>

            <form action="proceso_registro.php" method="POST" class="box">
                <div class="field">
                    <label class="label">Nombre</label>
                    <div class="control">
                        <input class="input" type="text" name="nombre" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Email</label>
                    <div class="control">
                        <input class="input" type="email" name="email" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Contraseña</label>
                    <div class="control">
                        <input class="input" type="password" name="password" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Rol</label>
                    <div class="control">
                        <div class="select">
                            <select name="rol">
                                <option value="usuario">Usuario</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="field is-grouped">
                    <div class="control">
                        <button type="submit" class="button is-primary">Registrar Usuario</button>
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