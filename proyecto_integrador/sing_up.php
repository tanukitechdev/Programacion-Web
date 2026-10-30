<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
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

                            if (isset($_SESSION['error_registro'])) {
                                echo '<div class="notification is-danger is-light">' . $_SESSION['error_registro'] . '</div>';
                                unset($_SESSION['error_registro']);
                            }
                        ?>
                        <form action="procesar_singup.php" method="POST" class="box login-box">
                            <h3 class="title is-3 has-text-centered login-title">Registrarse</h3>

                            <div class="field">
                                <label class="label login-label">Nombre</label>
                                <div class="control">
                                    <input class="input" type="text" placeholder="Tu nombre completo" name="nombre" required>
                                </div>
                            </div>

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
                                <label class="label login-label">Confirmar Contraseña</label>
                                <div class="control">
                                    <input class="input" type="password" placeholder="Confirma tu contraseña" name="confirm_password" required>
                                </div>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <button type="submit" class="button is-link is-fullwidth signup-button">Registrarse</button>
                                </div>
                            </div>

                            <p class="has-text-centered login-text">
                                ¿Ya tienes cuenta? <a href="login.php" class="has-text-link">Inicia Sesión</a>
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
    <footer class="footer">
    <div class="content has-text-centered">
        <p>
            <strong>Habitis | Habitus</strong><sup>&reg;</sup> Sitio creado con sangre, sudor y lágrimas por: Luis Miguel Espinoza.
        </p>
    </div>
    </footer>
    <script src="assets/js/script.js"></script>
</body>
</html>