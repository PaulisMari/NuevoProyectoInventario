<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Consulta General de Detalle de Pedidos</title>
    <link rel="stylesheet" href="CSS/consultasalida.css" />
</head>
<body>

    <!-- CAPA DE FONDO DESENFOCADO -->
    <div class="fondo-desenfocado"></div>

<div class="form-busqueda">
    <form action="index3.php" method="get" class="form-busqueda-form">
        <input type="hidden" name="action" value="listaDetallePedidos" />
        <label for="buscarDetalle">Buscar ID Detalle:</label>
        <input
            type="text"
            id="buscarDetalle"
            name="idDetalle"
            required
            placeholder="Ej: 1"
            class="input-codigo"
            value="<?= htmlspecialchars($_GET['idDetalle'] ?? '') ?>"
        />
        <div class="botones-busqueda">
            <button type="submit">Buscar</button>
            <button type="button" onclick="window.location.href='index3.php?action=listaDetallePedidos'">Limpiar</button>
        </div>
    </form>
</div>


     


    <!-- CONTENIDO -->
    <div class="contenido-con-fondo">
        <h2 style="text-align: center; color: #5a3e1b;">Consulta General de Detalle de Pedidos</h2>

        <!-- BOTONES SUPERIORES -->
        <div class="botones-centro">
            <form action="index3.php" method="GET">
                <input type="hidden" name="action" value="insertDetallePedido" />
                <button type="submit">+ Insertar Detalle</button>
            </form>

         
        </div>

        <!-- TABLA DETALLEPEDIDO -->
      <div class="tabla-contenedor">
    <?php if (!empty($users)): ?>
        <table border="1" cellspacing="0" cellpadding="5" style="width:100%; text-align:center;">
            <thead>
                <tr>
                    <th>ID Detalle</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>ID Pedido</th>
                    <th>Código Producto</th>
                    <th>Actualizar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['idDetalle']) ?></td>
                        <td><?= htmlspecialchars($user['cant']) ?></td>
                        <td><?= '$' . number_format($user['PrecioUni'], 0, ',', '.') ?></td>
                        <td><?= htmlspecialchars($user['IdPedido']) ?></td>
                        <td><?= htmlspecialchars($user['Codigo']) ?> - <?= htmlspecialchars($user['NombreProducto']) ?></td>
                        <td>
                            <form action="index3.php" method="GET" style="display:inline;">
                                <input type="hidden" name="action" value="openFormDetallePedido" />
                                <input type="hidden" name="idDetalle" value="<?= htmlspecialchars($user['idDetalle']) ?>" />
                                <button type="submit">Actualizar</button>
                            </form>
                        </td>
                        <td>
                            <form action="index3.php?action=eliminarDetallePedido" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro que deseas eliminar este detalle?');">
                                <input type="hidden" name="idDetalle" value="<?= htmlspecialchars($user['idDetalle']) ?>" />
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No se encontraron detalles de pedido.</p>
    <?php endif; ?>
</div>


        <!-- BOTÓN REGRESAR -->
        <div style="text-align: center; margin-top: 30px;">
            <form action="index2.php" method="post">
                <button type="submit" name="action" value="dashboard">Regresar</button>
            </form>
        </div>
    </div>

</body>
</html>
