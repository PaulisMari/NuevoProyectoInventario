<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Insertar Detalle de Pedido</title>
    <link rel="stylesheet" href="CSS/detallepedido.css">
</head>
<body>

<button class="btn-regresar" onclick="window.location.href='index3.php?action=listaDetallePedidos'">
    Regresar
</button>



    <div class="container">
        <!-- Imagen -->
        <img class="imagen" src="images/Detalle1.png" alt="Imagen de Detalle">

        <!-- Formulario -->
        <form action="index3.php?action=insertDetallePedido" method="POST">
            <!-- Título dentro del formulario, justo arriba del ID del Pedido -->
            <h2 class="titulo-formulario">Insertar Detalle de Pedido</h2>

            <label for="IdPedido">ID del Pedido:</label>
            <select name="IdPedido" id="IdPedido" required>
                <option value="">-- Seleccionar Pedido --</option>
                <?php foreach ($pedidos as $pedido): ?>
                    <option value="<?= $pedido['idPedido'] ?>">
                        <?= htmlspecialchars($pedido['idPedido']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <table id="productosTable">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select name="Codigo[]" required>
                                <option value="">-- Seleccionar Producto --</option>
                                <?php foreach ($productos as $producto): ?>
                                    <option value="<?= $producto['Codigo'] ?>">
                                        <?= $producto['Codigo'] ?> - <?= htmlspecialchars($producto['NombreProducto']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><input type="number" name="cant[]" min="1" required></td>
                        <td><input type="number" name="PrecioUni[]" min="0" step="0.01" required></td>
                        <td><button type="button" onclick="eliminarFila(this)">X</button></td>
                    </tr>
                </tbody>
            </table>

            <button type="button" onclick="agregarFila()">+ Agregar Producto</button><br><br>
            <button type="submit">Guardar Detalles</button>
        </form>
    </div>

    <script>
        function agregarFila() {
            const fila = document.querySelector('#productosTable tbody tr');
            const nuevaFila = fila.cloneNode(true);
            nuevaFila.querySelectorAll('input').forEach(input => input.value = '');
            document.querySelector('#productosTable tbody').appendChild(nuevaFila);
        }

        function eliminarFila(boton) {
            const filas = document.querySelectorAll('#productosTable tbody tr');
            if (filas.length > 1) {
                boton.closest('tr').remove();
            }
        }
    </script>

</body>
</html>
