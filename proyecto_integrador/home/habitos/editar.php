<?php
    require_once '../../includes/verificar_sesion.php';
    require_once '../../includes/conexion.php';

    $id_usuario = $_SESSION['usuario_id'];

    // Verificar si se ha proporcionado un ID de hábito para editar
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        header("Location: index.php");
        exit();
    }

    $id_habito_editar = $_GET['id'];

    // Consulta para obtener la información del hábito a editar
    $sql = "SELECT id_habito, nombre_habito, descripcion, frecuencia, meta FROM habitos WHERE id_habito = :id AND id_usuario = :id_usuario";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id', $id_habito_editar);
    $stmt->bindParam(':id_usuario', $id_usuario);
    $stmt->execute();
    $habito_editar = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si no se encuentra el hábito, redirigir
    if (!$habito_editar) {
        header("Location: index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Hábito</title>
    <link rel="stylesheet" href="../../assets/css/bulma.min.css">
    <link rel="stylesheet" href="../../assets/css/miestilo.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    <?php include '../../includes/menu.php'; ?>

    <section class="section editar-habito">
        <div class="container">
            <h1 class="title">
                Editar Hábito
            </h1>
            <p class="subtitle">
                "Empieza por hacer lo necesario, luego haz lo posible y de pronto estarás haciendo lo imposible." 
            </p>

            <?php
                if (isset($_SESSION['error_editar_habitual'])) {
                    echo '<div class="notification is-danger is-light">' . $_SESSION['error_editar_habitual'] . '</div>';
                    unset($_SESSION['error_editar_habitual']);
                }
            ?>

            <form action="proceso_editar.php" method="POST" class="box">
                <input type="hidden" name="id_habito" value="<?php echo htmlspecialchars($habito_editar['id_habito']); ?>">

                <div class="field">
                    <label class="label">Nueva actividad</label>
                    <div class="control">
                        <input class="input" type="text" name="nombre_habito" value="<?php echo htmlspecialchars($habito_editar['nombre_habito']); ?>" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Descripción (Opcional)</label>
                    <div class="control">
                        <textarea class="textarea" name="descripcion"><?php echo htmlspecialchars($habito_editar['descripcion']); ?></textarea>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Frecuencia</label>
                    <div class="control">
                        <div class="select">
                            <select name="frecuencia">
                                <option value="diaria" <?php if ($habito_editar['frecuencia'] == 'diaria') echo 'selected'; ?>>Diaria</option>
                                <option value="semanal" <?php if ($habito_editar['frecuencia'] == 'semanal') echo 'selected'; ?>>Semanal</option>
                                <option value="personalizada" <?php if ($habito_editar['frecuencia'] == 'personalizada') echo 'selected'; ?>>Personalizada</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Meta (Opcional)</label>
                    <div class="control">
                        <input class="input" type="text" name="meta" placeholder="Ej: 3 veces por semana, 30 minutos" value="<?php echo htmlspecialchars($habito_editar['meta']); ?>">
                    </div>
                </div>

                <div class="field is-grouped">
                    <div class="control">
                        <button type="submit" class="button is-info guardar-cambios-button">Guardar Cambios</button>
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