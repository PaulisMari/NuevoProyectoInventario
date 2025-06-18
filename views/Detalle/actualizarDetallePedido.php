<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Detalle de Pedido</title>
    <link rel="stylesheet" href="CSS/actualizarDetalle.css">
</head>
<body>

    <button class="btn-regresar" onclick="window.location.href='index.php?action=listaDetallePedidos'">Regresar</button>

    <div class="container">
        <!-- Mensaje de sesión -->
        <?php if (isset($_SESSION['message'])): ?>
            <p class="message"><?= $_SESSION['message']; unset($_SESSION['message']); ?></p>
        <?php endif; ?>

        <!-- Formulario -->
        <form action="index.php?action=actualizarDetallePedido" method="POST">
            <h2 class="titulo-formulario">Actualizar Detalle de Pedido</h2>

            <input type="hidden" name="idDetalleOriginal" value="<?= htmlspecialchars($detalle['idDetalle'] ?? '') ?>">

            <label for="cant">Cantidad:</label>
            <input type="number" name="cant" id="cant" value="<?= htmlspecialchars($detalle['cant'] ?? '') ?>" min="1" required>

            <label for="PrecioUni">Precio Unitario:</label>
            <input type="text" name="PrecioUni" id="PrecioUni" value="<?= htmlspecialchars($detalle['PrecioUni'] ?? '') ?>" required>

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

    <script>
        // Obtener el input de precio
        const inputPrecio = document.getElementById('PrecioUni');

        // Formatea en tiempo real
        function formatearPrecioInput(e) {
            const input = e.target;

            // Eliminar todo excepto dígitos
            let valor = input.value.replace(/[^0-9]/g, '');

            if (valor === '') {
                input.value = '';
                return;
            }

            const numero = parseInt(valor, 10);
            input.value = '$' + numero.toLocaleString('es-CO');

            // Mueve el cursor al final
            input.setSelectionRange(input.value.length, input.value.length);
        }

        // Aplicar formateo mientras se escribe
        inputPrecio.addEventListener('input', formatearPrecioInput);

        // Aplicar formateo al cargar la página si hay valor
        window.addEventListener('DOMContentLoaded', () => {
            const valor = inputPrecio.value.replace(/[^0-9]/g, '');
            if (valor) {
                inputPrecio.value = '$' + parseInt(valor, 10).toLocaleString('es-CO');
            }
        });

        // Validar y limpiar antes de enviar el formulario
        document.querySelector('form').addEventListener('submit', function(e) {
            const valorLimpio = inputPrecio.value.replace(/[^0-9]/g, '');
            const valorNumerico = parseInt(valorLimpio, 10);

            if (isNaN(valorNumerico) || valorNumerico < 50) {
                alert('El precio unitario debe ser un número mayor o igual a $50.');
                inputPrecio.focus();
                e.preventDefault();
                return false;
            }

            // Enviar valor limpio (sin $ ni puntos)
            inputPrecio.value = valorLimpio;
        });
    </script>

</body>
</html>
