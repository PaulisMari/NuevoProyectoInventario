<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Consulta General de Entradas</title>
    <link rel="stylesheet" href="CSS/consultasalida.css" />
</head>
<body>

    <!-- BOTÓN REGRESAR SUPERIOR IZQUIERDO -->
    <div class="boton-regresar">
        <form action="index2.php" method="POST">
            <input type="hidden" name="action" value="dashboard" />
            <button type="submit">Regresar</button>
        </form>
    </div>

    <!-- FORMULARIO DE BÚSQUEDA CENTRADO -->
    <div class="form-busqueda">
        <form action="index3.php" method="get" class="form-busqueda-form">
            <input type="hidden" name="action" value="listaEntradas" />
            <label for="idEntrada">Buscar ID de Entrada del Producto</label>
            <input
                type="number"
                id="idEntrada"
                name="idEntrada"
                required
                placeholder="Ingrese ID de entrada del producto"
                class="input-codigo"
                value="<?= htmlspecialchars($_GET['idEntrada'] ?? '') ?>"
            />
            <div class="botones-busqueda">
                <button type="submit">Buscar</button>
                <button type="button" onclick="window.location.href='index3.php?action=listaEntradas'">Limpiar</button>
            </div>
        </form>
    </div>

    <!-- CONTENIDO CON FONDO BLANCO SEMITRANSPARENTE -->
    <div class="contenido-con-fondo">
        <div class="fondo-desenfocado"></div>
        <h2 style="text-align: center; color: #5a3e1b;">Consulta General de Entradas</h2>

        <!-- BOTONES INSERTAR -->
        <div class="botones-centro">
            <form action="index3.php" method="GET">
                <input type="hidden" name="action" value="insertEntrada" />
                <button type="submit">+ Insertar Entrada</button>
            </form>

            <form action="index3.php" method="GET" target="_blank">
                <input type="hidden" name="action" value="generarPDFEntradas" />
                <button type="submit">PDF General Entradas</button>
            </form>
        </div>

        <!-- TABLA CON SCROLL -->
        <div class="tabla-contenedor">
            <?php if (isset($users) && count($users) > 0): ?>
                <table border="1">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Descripción del Producto</th>
                            <th>Cantidad</th>
                            <th>Fecha de Entrada</th>
                            <th>Precio Unitario</th>
                            <th>Código del Producto</th>
                            <th>Actualizar</th>
                            <th>Eliminar</th>
                            <th>PDF</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['idEntrada']); ?></td>
                                <td><?= htmlspecialchars($user['DescripcionEntrada']); ?></td>
                                <td><?= htmlspecialchars($user['CantidadEntrada']); ?></td>
                                <td><?= htmlspecialchars($user['FechaEntrada']); ?></td>
                                <td><?= '$' . number_format($user['PrecioUni'], 0, ',', '.'); ?></td>
                                <td><?= htmlspecialchars($user['Codigo']); ?></td>
                                <td>
                                    <form action="index3.php" method="GET" style="display:inline;">
                                        <input type="hidden" name="action" value="openFormEntrada" />
                                        <input type="hidden" name="idEntrada" value="<?= htmlspecialchars($user['idEntrada']); ?>" />
                                        <button type="submit">Actualizar</button>
                                    </form>
                                </td>
                                <td>
                                    <form action="index3.php?action=eliminarEntrada" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar esta entrada?');">
                                        <input type="hidden" name="idEntrada" value="<?= htmlspecialchars($user['idEntrada']); ?>" />
                                        <button type="submit">Eliminar</button>
                                    </form>
                                </td>
                                <td>
                                    <form action="index3.php" method="GET" target="_blank">
                                        <input type="hidden" name="action" value="generarPDFEntrada">
                                        <input type="hidden" name="idEntrada" value="<?= htmlspecialchars($user['idEntrada']); ?>">
                                        <button type="submit">PDF</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No se encontraron entradas.</p>
            <?php endif; ?>
        </div>
    </div> <!-- cierre de .contenido-con-fondo -->

</body>
</html>
