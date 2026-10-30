<?php
    require_once '../../includes/verificar_sesion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Hábito</title>
    <link rel="stylesheet" href="../../assets/css/bulma.min.css">
    <link rel="stylesheet" href="../../assets/css/miestilo.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    <?php include '../../includes/menu.php'; ?>

    <section class="section crear-habito">
        <div class="container">
            <h1 class="title">
                Crear Nuevo Hábito
            </h1>
            <p class="subtitle">
                "El placer en la tarea pone perfección en el trabajo."
            </p>

            <?php
                if (isset($_SESSION['error_crear_habitual'])) {
                    echo '<div class="notification is-danger is-light">' . $_SESSION['error_crear_habitual'] . '</div>';
                    unset($_SESSION['error_crear_habitual']);
                }
            ?>

            <form action="proceso_crear.php" method="POST" class="box">
                <div class="field">
                    <label class="label">Nombre del Hábito</label>
                    <div class="control">
                        <input class="input" type="text" name="nombre_habito" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Descripción de tu actividad</label>
                    <div class="control">
                        <textarea class="textarea" name="descripcion"></textarea>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Frecuencia</label>
                    <div class="control">
                        <div class="select">
                            <select name="frecuencia">
                                <option value="diaria" selected>Diaria</option>
                                <option value="semanal">Semanal</option>
                                <option value="personalizada">Personalizada</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Meta (Opcional)</label>
                    <div class="control">
                        <input class="input" type="text" name="meta" placeholder="Ej: 3 veces por semana, 30 minutos">
                    </div>
                </div>

                <div class="field is-grouped">
                    <div class="control">
                        <button type="submit" class="button is-primary">Crear Hábito</button>
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