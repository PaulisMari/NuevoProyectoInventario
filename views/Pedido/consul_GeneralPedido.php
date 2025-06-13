<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Consulta General de Pedidos</title>
    <link rel="stylesheet" href="CSS/consultasalida.css" />
</head>

<body>

    <!-- BOTÓN REGRESAR FIJO SUPERIOR IZQUIERDO -->
    <form action="inventario.php" method="post">
        <button type="submit" name="action" value="dashboard">Regresar</button>
    </form>

    <!-- Fondo desenfocado de imagen -->
    <div class="fondo-desenfocado"></div>

    <!-- FORMULARIO DE BÚSQUEDA CENTRADO -->
    <div class="form-busqueda">
        <form action="index3.php" method="get" class="form-busqueda-form">
            <input type="hidden" name="action" value="listaPedidos" />
            <label for="buscarId">Buscar ID de Pedido:</label>
            <input
                type="text"
                name="idPedido"
                id="buscarId"
                placeholder="Ej: 123"
                class="input-codigo"
                value="<?= htmlspecialchars($_GET['idPedido'] ?? '') ?>"
            />
            <div class="botones-busqueda">
                <button type="submit">Buscar</button>
                <button type="button" onclick="window.location.href='index3.php?action=listaPedidos'">Limpiar</button>
            </div>
        </form>
    </div>

    <!-- CONTENIDO CON FONDO BLANCO SEMITRANSPARENTE -->
    <div class="contenido-con-fondo">
        <h2 style="text-align: center; color: #5a3e1b;">Consulta General de Pedidos</h2>

        <!-- BOTONES INSERTAR Y PDF -->
        <div class="botones-centro">
            <form action="index3.php" method="GET">
                <input type="hidden" name="action" value="insertPedido" />
                <button type="submit">+ Insertar Pedido</button>
            </form>

          
            <form action="index3.php" method="GET" target="_blank">
    <input type="hidden" name="action" value="generarPDFPedidos" />
    <button type="submit">PDF General Pedidos</button>
</form>

        </div>

        <!-- TABLA CON SCROLL -->
        <div class="tabla-contenedor">
            <?php if (isset($pedidos) && count($pedidos) > 0): ?>
                <table border="1">
                    <thead>
                        <tr>
                            <th>ID Pedido</th>
                            <th>Fecha Pedido</th>
                            <th>Pedido Por (DocEmpleado)</th>
                            <th>Proveedor (DocProveedor)</th>
                            <th>Actualizar</th>
                            <th>Eliminar</th>
                            <th>PDF</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $pedido): ?>
                            <tr>
                                <td><?= htmlspecialchars($pedido['idPedido']) ?></td>
                                <td><?= htmlspecialchars($pedido['FechaPedido']) ?></td>
                           
                                <td>
    <?= htmlspecialchars($pedido['PedidoPor']) ?>
    <?php if (!empty($pedido['EmpleadoNombre'])): ?>
        - <?= htmlspecialchars($pedido['EmpleadoNombre']) ?>
    <?php endif; ?>
</td>
<td>
    <?= htmlspecialchars($pedido['DocProveedor']) ?>
    <?php if (!empty($pedido['ProveedorNombre'])): ?>
        - <?= htmlspecialchars($pedido['ProveedorNombre']) ?>
    <?php endif; ?>
</td>

                                <td>
                                    <form action="index3.php" method="GET" style="display:inline;">
                                        <input type="hidden" name="action" value="openFormPedido" />
                                        <input type="hidden" name="idPedido" value="<?= htmlspecialchars($pedido['idPedido']) ?>" />
                                        <button type="submit">Actualizar</button>
                                    </form>
                                </td>
                                <td>
                                    <form action="index3.php?action=eliminarPedido" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro que deseas eliminar este pedido?');">
                                        <input type="hidden" name="idPedido" value="<?= htmlspecialchars($pedido['idPedido']) ?>" />
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                    </form>
                                </td>
                                <td>
                                    <form action="index3.php" method="GET" target="_blank">
                                        <input type="hidden" name="action" value="generarPDFPedido" />
                                        <input type="hidden" name="idPedido" value="<?= htmlspecialchars($pedido['idPedido']) ?>" />
                                        <button type="submit">PDF</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No se encontraron pedidos.</p>
            <?php endif; ?>
        </div>
    </div> <!-- cierre de .contenido-con-fondo -->

</body>
</html>
