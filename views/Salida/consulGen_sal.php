<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Consulta General de Salidas</title>
    <link rel="stylesheet" href="CSS/consultasalida.css" />
</head>
<body>

    <!-- BOTÓN REGRESAR FIJO EN ESQUINA SUPERIOR IZQUIERDA -->
    <div style="position: fixed; top: 10px; left: 10px; z-index: 1000;">
        <form action="inventario.php" method="post">
            <button type="submit" name="action" value="dashboard">Regresar</button>
        </form>
    </div>

    <!-- FORMULARIO DE BÚSQUEDA CENTRADO -->
    <div class="form-busqueda">
        <form action="index3.php" method="get" class="form-busqueda-form">
            <input type="hidden" name="action" value="listaSalidas" />
            <label for="idSalida">Buscar ID salida del Producto</label>
            <input
                type="number"
                id="idSalida"
                name="idSalida"
                required
                placeholder="Ingrese ID salida del producto"
                class="input-codigo"
                value="<?= htmlspecialchars($_GET['idSalida'] ?? '') ?>"
            />
            <div class="botones-busqueda">
                <button type="submit">Buscar</button>
                <button type="button" onclick="window.location.href='index3.php?action=listaSalidas'">Limpiar</button>
            </div>
        </form>
    </div>

    <!-- CONTENIDO CON FONDO BLANCO SEMITRANSPARENTE -->
    <div class="contenido-con-fondo">
        <div class="fondo-desenfocado"></div>
        <h2 style="text-align: center; color: #5a3e1b;">Consulta General de Salidas</h2>

        <!-- BOTONES INSERTAR Y PDF-->
        <div class="botones-centro">
            <form action="index3.php" method="GET">
                <input type="hidden" name="action" value="insertSalida" />
                <button type="submit">+ Insertar Salida</button>
            </form>

            <form action="index3.php" method="GET" target="_blank">
                <input type="hidden" name="action" value="generarPDFSalidas" />
                <button type="submit">PDF General Salidas</button>
            </form>
        </div>

        <!-- TABLA CON SCROLL -->
        <div class="tabla-contenedor">
            <?php if (isset($users) && count($users) > 0): ?>
                <table border="1">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Motivo de la Salida</th>
                            <th>Cantidad</th>
                            <th>Fecha de Salida</th>
                            <th>Código del Producto</th>
                            <th>Actualizar</th>
                            <th>Eliminar</th>
                            <th>PDF</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['idSalida']); ?></td>
                                <td><?= htmlspecialchars($user['MotivoSalida']); ?></td>
                                <td><?= htmlspecialchars($user['CantidadSalida']); ?></td>
                                <td><?= htmlspecialchars($user['FechaSalida']); ?></td>
                                <td><?= htmlspecialchars($user['Codigo']); ?></td>
                                <td>
                                    <form action="index3.php" method="GET" style="display:inline;">
                                        <input type="hidden" name="action" value="openFormSalida" />
                                        <input type="hidden" name="idSalida" value="<?= htmlspecialchars($user['idSalida']); ?>" />
                                        <button type="submit">Actualizar</button>
                                    </form>
                                </td>
                                <td>
                                    <form action="index3.php?action=eliminarSalida" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar esta salida?');">
                                        <input type="hidden" name="idSalida" value="<?= htmlspecialchars($user['idSalida']); ?>" />
                                        <button type="submit">Eliminar</button>
                                    </form>
                                </td>
                                <td>
                                    <form action="index3.php" method="GET" target="_blank">
                                        <input type="hidden" name="action" value="generarPDFSalida">
                                        <input type="hidden" name="idSalida" value="<?= htmlspecialchars($user['idSalida']); ?>">
                                        <button type="submit">PDF</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No se encontraron salidas.</p>
            <?php endif; ?>
        </div>
    </div> <!-- cierre de .contenido-con-fondo -->

</body>
</html>
