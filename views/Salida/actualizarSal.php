<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- 👈 Necesario para que sea responsivo -->
    <title>Actualizar Salida</title>
    <link rel="stylesheet" href="CSS/actualizarSalida.css">
</head>
<body>
    <button class="btn-regresar" onclick="history.back()">Regresar</button>

    <div class="container">
        <div class="salida">
            <h1>Actualizar Salida</h1>
            <?php if (isset($salida)): ?>
            <form action="index3.php?action=actualizarSalida" method="POST">
                <input type="hidden" name="idSalida" value="<?= htmlspecialchars($salida['idSalida']); ?>">

                <label for="motivoSalida">Motivo de salida del producto:</label>
                <input type="text" name="motivoSalida" id="motivoSalida" value="<?= htmlspecialchars($salida['MotivoSalida']); ?>" required>

                <label for="cantidadSalida">Cantidad del producto:</label>
                <input type="number" name="cantidadSalida" id="cantidadSalida" value="<?= htmlspecialchars($salida['CantidadSalida']); ?>" required>

                <label for="fechaSalida">Fecha y hora de salida del producto:</label>
                <input type="datetime-local" name="fechaSalida" id="fechaSalida" value="<?= htmlspecialchars($salida['FechaSalida']); ?>" required>

                <label for="codigo">Código:</label>
                <select name="codigo" id="codigo" required>
                    <option value="">Escoja código del producto</option>
                    <?php
                    include_once('./controllers/UserController.php');
                    $numE = new UserController();
                    $arraynumE = $numE->getCodigoProS();
                    foreach ($arraynumE as $num) {
                        $selected = ($salida['Codigo'] === $num['Codigo']) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($num['Codigo']) . "' $selected>"
                            . htmlspecialchars($num['Codigo']) . "</option>";
                    }
                    ?>
                </select>

                <input type="submit" value="Guardar Cambios ">
            </form>
            <?php else: ?>
                <p style="color:red;">No se encontró la salida para actualizar.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
