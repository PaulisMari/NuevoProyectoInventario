<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Actualizar Empleado</title>
    <link rel="stylesheet" href="CSS/actualizarEmpleado.css" />
</head>

<body>
    <!-- Botón regresar -->
    <a href="index.php?action=listaEmpleados" class="btn-regresar">Regresar</a>

    <div class="container">
        <form action="index.php?action=actualizarEmpleado" method="POST">
            <!-- Documento original oculto -->
            <input type="hidden" name="docEmpleadoOriginal" value="<?= htmlspecialchars($empleado['DocEmpleado'] ?? '') ?>" />

            <h2>Actualizar Empleado</h2>

            <label for="docEmpleado">Documento de Empleado:</label>
            <input type="number" id="docEmpleado" name="docEmpleado"
                value="<?= htmlspecialchars($empleado['DocEmpleado'] ?? '') ?>"
                pattern="[1-9][0-9]*"
                title="El número de documento no puede comenzar con 0 y debe contener solo dígitos"
                required />

            <label for="tipoDoc">Tipo de Documento:</label>
            <select id="tipoDoc" name="tipoDoc" required>
                <option value="">-- Seleccionar --</option>
                <?php foreach ($tiposDocumento as $tipo): ?>
                    <option value="<?= $tipo['id'] ?>" <?= $tipo['id'] == $empleado['TipoDoc'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tipo['tipo']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="nombre">Nombre del Empleado:</label>
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($empleado['Nombre']) ?>" required />

            <label for="fechaNaci">Fecha de Nacimiento:</label>
            <input type="date" id="fechaNaci" name="fechaNaci" value="<?= htmlspecialchars($empleado['FechaNaci']) ?>" required />

            <label for="telefono">Teléfono:</label>
            <input type="number" id="telefono" name="telefono" value="<?= htmlspecialchars($empleado['Telefono']) ?>" required />

            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" value="<?= htmlspecialchars($empleado['Direccion']) ?>" required />

            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($empleado['Email']) ?>" required />

            <button type="submit">Guardar Cambios</button>
        </form>
    </div>
</body>

</html>
