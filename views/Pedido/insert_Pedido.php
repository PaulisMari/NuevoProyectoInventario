<!-- ./views/Pedido/insert_Pedido.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar Pedido</title>
    <link rel="stylesheet" href="CSS/Pedido.css">
</head>
<body>
    <a href="index.php?action=listaPedidos" class="btn-regresar">Regresar</a>

    <div class="container">
        <!-- Imagen a la izquierda -->
        <div class="imagen-contenedor">
            <img class="imagen" src="images/pedido1.png" alt="Imagen de Pedido">
        </div>

        <!-- Formulario a la derecha -->
        <div class="formulario-contenedor">
            <h2>Insertar Pedido</h2>
            <form action="index.php?action=insertPedido" method="POST">
                <label for="FechaPedido">Fecha del Pedido:</label>
                <input type="date" name="FechaPedido" id="FechaPedido" required max="<?= date('Y-m-d') ?>">

                <label for="PedidoPor">Empleado que realiza el pedido:</label>
                <select name="PedidoPor" id="PedidoPor" required>
                    <option value="">-- Seleccionar Empleado --</option>
                    <?php foreach ($empleados as $empleado): ?>
                        <option value="<?= $empleado['DocEmpleado'] ?>">
                            <?= htmlspecialchars($empleado['Nombre']) ?> (<?= $empleado['DocEmpleado'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="DocProveedor">Proveedor:</label>
                <select name="DocProveedor" id="DocProveedor" required>
                    <option value="">-- Seleccionar Proveedor --</option>
                    <?php foreach ($proveedores as $proveedor): ?>
                        <option value="<?= $proveedor['DocProveedor'] ?>">
                            <?= htmlspecialchars($proveedor['Nombre']) ?> (<?= $proveedor['DocProveedor'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>

                <input type="submit" value="Guardar Pedido">
            </form>
        </div>
    </div>
</body>
</html>
