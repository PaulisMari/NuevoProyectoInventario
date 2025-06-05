<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="CSS/recuperar.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>
<body>
    <div class="regresar-container">
        <a href="index3.php?action=login" class="btn-regresar" title="Volver">←</a>
    </div>

    <div class="contenedor">
        <h2>Recuperar contraseña</h2>
        <form action="index3.php?action=enviarToken" method="POST">
            <div class="input-container">
                <label for="usuario">Número de documento:</label>
                <input type="text" name="usuario" id="usuario" required>
            </div>
            <button type="submit">Enlace de recuperación</button>
        </form>

        <?php if (!empty($mensaje)): ?>
            <?php
                $clase = "alert-warning";
                if (strpos($mensaje, 'correctamente') !== false) {
                    $clase = "alert-success";
                } elseif (strpos($mensaje, 'No se encontró') !== false || strpos($mensaje, 'Debes ingresar') !== false || strpos($mensaje, 'error') !== false) {
                    $clase = "alert-error";
                }
            ?>
            <div class="alert <?= $clase ?>">
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
