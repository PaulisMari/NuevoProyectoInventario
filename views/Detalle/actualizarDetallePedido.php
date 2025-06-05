<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Detalle de Pedido</title>
    <link rel="stylesheet" href="CSS/actualizarDetalle.css">
</head>
<body>

    <button class="btn-regresar" onclick="window.location.href='index3.php?action=listaDetallePedidos'">Regresar</button>

    <div class="container">
        <!-- Mensaje de sesión -->
        <?php if (isset($_SESSION['message'])): ?>
            <p class="message"><?= $_SESSION['message']; unset($_SESSION['message']); ?></p>
        <?php endif; ?>

        <!-- Formulario -->
        <form action="index3.php?action=actualizarDetallePedido" method="POST">
            <h2 class="titulo-formulario">Actualizar Detalle de Pedido</h2>

            <input type="hidden" name="idDetalleOriginal" value="<?= htmlspecialchars($detalle['idDetalle'] ?? '') ?>">

            <label for="cant">Cantidad:</label>
            <input type="number" name="cant" id="cant" value="<?= htmlspecialchars($detalle['cant'] ?? '') ?>" min="1" required>

            <label for="PrecioUni">Precio Unitario:</label>
            <input type="number" name="PrecioUni" id="PrecioUni" value="<?= htmlspecialchars($detalle['PrecioUni'] ?? '') ?>" min="0" step="0.01" required>

            <label for="IdPedido">ID del Pedido:</label>
            <select name="IdPedido" id="IdPedido" required>
                <option value="">-- Seleccionar Pedido --</option>
                <?php foreach ($pedidos as $pedido): ?>
                    <option value="<?= $pedido['idPedido'] ?>" <?= $pedido['idPedido'] == $detalle['IdPedido'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($pedido['idPedido']) ?> - <?= htmlspecialchars($pedido['FechaPedido']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="Codigo">Producto:</label>
            <select name="Codigo" id="Codigo" required>
                <option value="">-- Seleccionar Producto --</option>
                <?php foreach ($productos as $producto): ?>
                    <option value="<?= $producto['Codigo'] ?>" <?= $producto['Codigo'] == $detalle['Codigo'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($producto['Codigo']) ?> - <?= htmlspecialchars($producto['NombreProducto']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Guardar Cambios</button>
        </form>
    </div>

</body>
</html>
