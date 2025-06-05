<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Actualizar Proveedor</title>
    <link rel="stylesheet" href="CSS/actualizarProveedor.css" />
</head>
<body>
    <a href="index3.php?action=listaProveedores" class="btn-regresar">Regresar</a>

    <div class="container">
        <?php if (isset($_SESSION['message'])): ?>
            <p class="message"><?= $_SESSION['message']; unset($_SESSION['message']); ?></p>
        <?php endif; ?>

        <form class="formulario" action="index3.php?action=actualizarProveedor" method="POST">
            <h2>Actualizar Proveedor</h2>

            <input type="hidden" name="docProveedorOriginal" value="<?= htmlspecialchars($proveedor['DocProveedor']) ?>" />

            <label for="docProveedor">Documento del Proveedor:</label>
            <input type="number" name="docProveedor" id="docProveedor" 
                   value="<?= htmlspecialchars($proveedor['DocProveedor']) ?>"
                   pattern="[1-9][0-9]*" 
                   title="El número de documento no puede comenzar con 0 y debe contener solo dígitos" required />

            <label for="tipoDoc">Tipo de Documento:</label>
            <select name="tipoDoc" id="tipoDoc" required>
                <option value="">-- Seleccionar --</option>
                <?php foreach ($tiposDocumento as $tipo): ?>
                    <option value="<?= $tipo['id'] ?>" <?= $tipo['id'] == $proveedor['TipoDoc'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tipo['tipo']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="nombre">Nombre del Proveedor:</label>
            <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($proveedor['Nombre']) ?>" required />

            <label for="telefono">Teléfono:</label>
            <input type="number" name="telefono" id="telefono" 
                   value="<?= htmlspecialchars($proveedor['Telefono']) ?>" 
                   pattern="(3[0-9]{9})|([1-9][0-9]{6,8})" 
                   title="Ingrese un número de teléfono fijo (7 a 9 dígitos) o celular válido (10 dígitos que empiece con 3)" 
                   required />

            <label for="direccion">Dirección:</label>
            <input type="text" name="direccion" id="direccion" value="<?= htmlspecialchars($proveedor['Direccion']) ?>" required />

            <label for="email">Correo Electrónico:</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($proveedor['Email']) ?>" required />

            <button type="submit">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>
