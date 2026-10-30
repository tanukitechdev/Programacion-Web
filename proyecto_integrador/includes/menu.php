<nav class="navbar is-light" role="navigation" aria-label="main navigation">
    <div id="navbarBasicExample" class="navbar-menu">
        <div class="navbar-start is-flex is-justify-content-center">
            <a href="../index.php" class="navbar-item <?php if ($_SERVER['PHP_SELF'] == '/home/index.php') echo 'is-active'; ?>">
                <span class="icon mr-2">
                    <i class="fas fa-home"></i>
                </span>
                Inicio
            </a>

            <a href="habitos/index.php" class="navbar-item <?php if ($_SERVER['PHP_SELF'] == '/home/habitos/index.php') echo 'is-active'; ?>">
                <span class="icon mr-2">
                    <i class="fas fa-list-ul"></i>
                </span>
                Hábitos
            </a>

            <a href="metas/index.php" class="navbar-item <?php if ($_SERVER['PHP_SELF'] == '/home/metas/index.php') echo 'is-active'; ?>">
                <span class="icon mr-2">
                    <i class="fas fa-bullseye"></i>
                </span>
                Metas
            </a>

            <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] == 'admin'): ?>
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link">
                        <span class="icon mr-2">
                            <i class="fas fa-cog"></i>
                        </span>
                        Administrador
                    </a>
                    <div class="navbar-dropdown">
                        <a href="usuarios/index.php" class="navbar-item">
                            <span class="icon mr-2">
                                <i class="fas fa-users"></i>
                            </span>
                            Usuarios
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <div class="navbar-item">
                <div class="buttons">
                    <a href="../../logout.php" class="button is-warning logout-button">
                        <span class="icon mr-2">
                            <i class="fas fa-sign-out-alt"></i>
                        </span>
                        Cerrar Sesión
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
        $navbarBurgers.forEach(el => {
            el.addEventListener('click', () => {
                const target = el.dataset.target;
                const $target = document.getElementById(target);
                el.classList.toggle('is-active');
                $target.classList.toggle('is-active');
            });
        });
    });
</script>