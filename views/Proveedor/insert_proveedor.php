<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar Proveedor</title>
    <link rel="stylesheet" href="CSS/Proveedor.css">
</head>
<body>

    <!-- Botón de regresar -->
    <a href="index3.php?action=listaProveedores" class="btn-regresar">Regresar</a>

    <!-- Contenedor principal -->
    <div class="container">
        <!-- Imagen decorativa -->
        <img class="imagen" src="images/Entrada2.png" alt="Imagen de entrada">

        <!-- Formulario -->
        <form class="formulario" action="index3.php?action=insertProveedor" method="POST">
            <h2 style="text-align: center; color: #ffffff; margin-bottom: 15px;">Insertar Proveedor</h2>

            <label for="DocProveedor">Documento del Proveedor:</label>
            <input type="number" name="DocProveedor" id="DocProveedor"
                   pattern="[1-9][0-9]*"
                   title="El número no puede comenzar con 0 y debe contener solo dígitos"
                   required>

            <label for="TipoDoc">Tipo de Documento:</label>
            <select name="TipoDoc" id="TipoDoc" required>
                <option value="">-- Seleccionar --</option>
                <?php foreach ($tiposDocumento as $tipo): ?>
                    <option value="<?= $tipo['id'] ?>"><?= htmlspecialchars($tipo['tipo']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="Nombre">Nombre del Proveedor:</label>
            <input type="text" name="Nombre" id="Nombre" required>

            <label for="Telefono">Teléfono:</label>
            <input type="number" name="Telefono" id="Telefono"
                   pattern="(3[0-9]{9})|([1-9][0-9]{6,8})"
                   title="Ingrese un número de teléfono fijo (7-9 dígitos) o celular válido (10 dígitos empezando con 3)"
                   required>

            <label for="Direccion">Dirección:</label>
            <input type="text" name="Direccion" id="Direccion" required>

            <label for="Email">Correo Electrónico:</label>
            <input type="email" name="Email" id="Email" required>

            <button type="submit">Registrar Proveedor</button>
        </form>
    </div>

</body>
</html>
