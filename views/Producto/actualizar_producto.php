<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/actualizarProducto.css">
    <title>Actualizar Producto</title>
    <style>
        input[type="text"].precio-input {
            font-weight: bold;
            color: black;
        }
    </style>
</head>
<body>

<!-- Botón Regresar -->
<form action="index3.php" method="GET">
    <button class="btn-regresar" type="submit" name="action" value="consultaproducto">Regresar</button>
</form>

<div class="contenedor-principal">
    <div class="tarjeta">
        <div class="lado-izquierdo">
            <!-- Se ha ocultado esta sección visualmente con CSS -->
        </div>

        <div class="lado-derecho">
            <h1 class="titulo">Actualizar Producto</h1>

            <?php if (isset($_SESSION['message'])): ?>
                <p class="mensaje"><?= $_SESSION['message']; ?></p>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <?php if (isset($userspros) && count($userspros) === 1): 
                $userpro = $userspros[0];
            ?>
            <form id="form-actualizar" action="index3.php?action=actualizarproducto" method="POST">
                <input type="hidden" name="CodigoOriginal" value="<?= htmlspecialchars($userpro['Codigo']); ?>">

                <label for="Codigo">Código:</label>
                <input class="form-input" type="number" name="Codigo" id="Codigo" value="<?= htmlspecialchars($userpro['Codigo']); ?>" required>

                <label for="NombreProducto">Nombre del producto:</label>
                <input class="form-input" type="text" name="NombreProducto" id="NombreProducto" value="<?= htmlspecialchars($userpro['NombreProducto']); ?>" required>

                <label for="Descripcion">Descripción:</label>
                <input class="form-input" type="text" name="Descripcion" id="Descripcion" value="<?= htmlspecialchars($userpro['Descripcion']); ?>" required>

                <label for="Precio">Precio:</label>
                <input class="form-input precio-input" type="text" name="Precio" id="Precio"
                    value="$<?= number_format($userpro['Precio'], 0, '', '.'); ?>" required>

                <label for="CantMax">Cantidad Máxima:</label>
                <input class="form-input" type="number" name="CantMax" id="CantMax" value="<?= htmlspecialchars($userpro['CantMax']); ?>" required>

                <label for="CantMin">Cantidad Mínima:</label>
                <input class="form-input" type="number" name="CantMin" id="CantMin" value="<?= htmlspecialchars($userpro['CantMin']); ?>" required>

                <label for="CantDis">Cantidad Disponible:</label>
                <input class="form-input" type="number" name="CantDis" id="CantDis" value="<?= htmlspecialchars($userpro['CantDis']); ?>" required>

                <label for="CreadoPor">Número de Documento:</label>
                <input class="form-input" type="number" name="CreadoPor" id="CreadoPor" value="<?= htmlspecialchars($userpro['CreadoPor']); ?>" readonly required>

                <input class="btn-guardar" type="submit" value="Guardar Cambios">
            </form>
            <?php else: ?>
                <p class="mensaje-error">No se encontró el producto.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    const precioInput = document.getElementById("Precio");
    const form = document.getElementById("form-actualizar");

    precioInput.addEventListener("input", () => {
        let valor = precioInput.value.replace(/[^\d]/g, "");
        if (valor) {
            precioInput.value = "$" + parseInt(valor).toLocaleString("es-CO", { maximumFractionDigits: 0 });
        } else {
            precioInput.value = "";
        }
    });

    form.addEventListener("submit", () => {
        precioInput.value = precioInput.value.replace(/[^\d]/g, "");
    });
</script>

</body>
</html>
