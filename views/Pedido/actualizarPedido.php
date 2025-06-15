<!-- ./views/Pedido/actualizarPedido.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Agregado para responsividad -->
    <title>Actualizar Pedido</title>
    <link rel="stylesheet" href="CSS/actualizarPedido.css">
</head>
<body>
    <a href="index3.php?action=listaPedidos" class="btn-regresar">Regresar</a>

    <div class="container">
        <h2>Actualizar Pedido</h2>

        <?php if (isset($_SESSION['message'])): ?>
            <p class="message"><?= $_SESSION['message']; unset($_SESSION['message']); ?></p>
        <?php endif; ?>

        <form action="index3.php?action=actualizarPedido" method="POST">
            <input type="hidden" name="idPedidoOriginal" value="<?= htmlspecialchars($pedido['idPedido']) ?>">

            <label for="FechaPedido">Fecha del Pedido:</label>
            <input type="date" name="FechaPedido" id="FechaPedido" 
                   value="<?= htmlspecialchars($pedido['FechaPedido']) ?>" 
                   max="<?= date('Y-m-d') ?>" required>

            <label for="PedidoPor">Empleado que realiza el pedido:</label>
            <select name="PedidoPor" id="PedidoPor" required>
                <option value="">-- Seleccionar Empleado --</option>
                <?php foreach ($empleados as $empleado): ?>
                    <option value="<?= $empleado['DocEmpleado'] ?>" 
                        <?= $empleado['DocEmpleado'] == $pedido['PedidoPor'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($empleado['Nombre']) ?> (<?= $empleado['DocEmpleado'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="DocProveedor">Proveedor:</label>
            <select name="DocProveedor" id="DocProveedor" required>
                <option value="">-- Seleccionar Proveedor --</option>
                <?php foreach ($proveedores as $proveedor): ?>
                    <option value="<?= $proveedor['DocProveedor'] ?>" 
                        <?= $proveedor['DocProveedor'] == $pedido['DocProveedor'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($proveedor['Nombre']) ?> (<?= $proveedor['DocProveedor'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn-guardar">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>
