<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="assets/css/bulma.min.css">
    <link rel="stylesheet" href="assets/css/miestilo.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <section class="hero is-fullheight login-hero">
        <div class="hero-body">
            <div class="container">
                <div class="columns is-centered">
                    <div class="column is-4-desktop is-6-tablet">
                        <?php
                            session_start(); // Asegúrate de iniciar la sesión aquí

                            if (isset($_SESSION['error_login'])) {
                                echo '<div class="notification is-danger is-light">' . $_SESSION['error_login'] . '</div>';
                                unset($_SESSION['error_login']);
                            }
                            if (isset($_SESSION['registro_exitoso'])) {
                                echo '<div class="notification is-success is-light">' . $_SESSION['registro_exitoso'] . '</div>';
                                unset($_SESSION['registro_exitoso']);
                            }
                        ?>
                        <form action="procesar_login.php" method="POST" class="box login-box">
                            <h3 class="title is-3 has-text-centered login-title">Iniciar Sesión</h3>

                            <div class="field">
                                <label class="label login-label">Correo Electrónico</label>
                                <div class="control">
                                    <input class="input" type="email" placeholder="tu_correo@ejemplo.com" name="email" required>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label login-label">Contraseña</label>
                                <div class="control">
                                    <input class="input" type="password" placeholder="Tu contraseña" name="password" required>
                                </div>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <button type="submit" class="button is-primary is-fullwidth login-button">Iniciar Sesión</button>
                                </div>
                            </div>

                            <p class="has-text-centered login-text">
                                ¿No tienes cuenta? <a href="sing_up.php" class="has-text-link">Regístrate</a>
                            </p>
                            <p class="has-text-centered login-text">
                                <a href="recuperar_password.php" class="has-text-link">¿Olvidaste tu contraseña?</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <a href="index.html" class="button is-white is-small back-to-home">
            <span class="icon">
                <i class="fas fa-home"></i>
            </span>
        </a>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/script.js"></script>
</body>
</html>