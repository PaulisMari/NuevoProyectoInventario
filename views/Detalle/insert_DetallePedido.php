<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Insertar Detalle de Pedido</title>
    <link rel="stylesheet" href="CSS/detallepedido.css">
</head>
<body>

<!-- Botón Regresar -->
<button class="btn-regresar" onclick="window.location.href='index3.php?action=listaDetallePedidos'">
    Regresar
</button>

<div class="container">
    <img class="imagen" src="images/Detalle1.png" alt="Imagen de Detalle">

    <form action="index3.php?action=insertDetallePedido" method="POST" id="formDetalle">
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
                    <td><input type="text" name="PrecioUni[]" required></td>
                    <td><button type="button" onclick="eliminarFila(this)">X</button></td>
                </tr>
            </tbody>
        </table>

        <button type="button" onclick="agregarFila()">+ Agregar Producto</button><br><br>
        <button type="submit">Guardar Detalles</button>
    </form>
</div>

<script>
    // Agrega nueva fila con listeners aplicados
    function agregarFila() {
        const fila = document.querySelector('#productosTable tbody tr');
        const nuevaFila = fila.cloneNode(true);

        nuevaFila.querySelectorAll('input').forEach(input => input.value = '');
        nuevaFila.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

        document.querySelector('#productosTable tbody').appendChild(nuevaFila);
        aplicarFormatoEnTiempoReal(); // Aplica formateo a inputs nuevos
    }

    // Elimina fila solo si hay más de una
    function eliminarFila(boton) {
        const filas = document.querySelectorAll('#productosTable tbody tr');
        if (filas.length > 1) {
            boton.closest('tr').remove();
        }
    }

    // Aplica formateo de precio en tiempo real a todos inputs precio
    function aplicarFormatoEnTiempoReal() {
        const inputs = document.querySelectorAll('input[name="PrecioUni[]"]');

        inputs.forEach(input => {
            input.removeEventListener('input', formatearEnTiempoReal);
            input.addEventListener('input', formatearEnTiempoReal);
        });
    }

    function formatearEnTiempoReal(e) {
        const input = e.target;
        let cursorPos = input.selectionStart;

        // Solo números (elimina todo menos dígitos)
        let valor = input.value.replace(/[^0-9]/g, '');

        if (valor === '') {
            input.value = '';
            return;
        }

        // Convierte a número entero y formatea con separador de miles y prefijo $
        const numero = parseInt(valor, 10);
        input.value = '$' + numero.toLocaleString('es-CO');

        // Pon el cursor al final para evitar que se mueva inesperadamente
        input.setSelectionRange(input.value.length, input.value.length);
    }

    // Al enviar el formulario, valida que precio unitario sea >= 50 y limpia los valores
    document.getElementById('formDetalle').addEventListener('submit', function(e) {
        const precioInputs = document.querySelectorAll('input[name="PrecioUni[]"]');
        for (const input of precioInputs) {
            // Limpiar para validar
            const valorLimpio = input.value.replace(/[^0-9]/g, '');
            const valorNumero = parseInt(valorLimpio, 10);

            if (isNaN(valorNumero) || valorNumero < 50) {
                alert('El precio unitario debe ser un número mayor o igual a $50.');
                input.focus();
                e.preventDefault();
                return false;
            }
        }

        // Limpiar todos para enviar sin $ ni separadores
        precioInputs.forEach(input => {
            input.value = input.value.replace(/[^0-9]/g, '');
        });
    });

    // Aplicar formato en inputs existentes
    aplicarFormatoEnTiempoReal();
</script>

</body>
</html>
