<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta General de Proveedores</title>
    <link rel="stylesheet" href="CSS/consultasalida.css" />
</head>

<body>

    <!-- BOTÓN REGRESAR FIJO EN ESQUINA SUPERIOR IZQUIERDA -->
    <div style="position: fixed; top: 10px; left: 10px; z-index: 1000;">
        <form action="inventario.php" method="post">
            <button type="submit" name="action" value="dashboard">Regresar</button>
        </form>
    </div>

    <!-- Fondo desenfocado con imagen -->
    <div class="fondo-desenfocado"></div>

    <!-- FORMULARIO DE BÚSQUEDA CENTRADO -->
    <div class="form-busqueda">
        <form action="index.php" method="get" class="form-busqueda-form">
            <input type="hidden" name="action" value="listaProveedores" />
            <label for="buscarDoc">Buscar documento:</label>
            <input
                type="text"
                name="docProveedor"
                id="buscarDoc"
                required
                placeholder="Ej: 12"
                class="input-codigo"
                value="<?= htmlspecialchars($_GET['docProveedor'] ?? '') ?>"
            />
            <div class="botones-busqueda">
                <button type="submit">Buscar</button>
                <button type="button" onclick="window.location.href='index.php?action=listaProveedores'">Limpiar</button>
            </div>
        </form>
    </div>

    <!-- CONTENIDO CON FONDO -->
    <div class="contenido-con-fondo">
        <h2 style="text-align: center; color: #5a3e1b;">Consulta General de Proveedores</h2>

        <!-- BOTONES INSERTAR Y PDF -->
        <div class="botones-centro">
            <form action="index.php" method="GET">
                <input type="hidden" name="action" value="insertProveedor" />
                <button type="submit">+ Insertar Proveedor</button>
            </form>

            <form action="index.php" method="GET" target="_blank">
                <input type="hidden" name="action" value="generarPDFProveedores" />
                <button type="submit">PDF General Proveedores</button>
            </form>
        </div>

        <!-- TABLA -->
        <div class="tabla-contenedor">
            <?php if (isset($users) && count($users) > 0): ?>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Número de Documento</th>
                            <th>Tipo de Documento</th>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Email</th>
                            <th>Actualizar</th>
                            <th>Eliminar</th>
                            <th>PDF</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['DocProveedor']) ?></td>
                                <td><?= htmlspecialchars($user['TipoDocumentoNombre']) ?></td>
                                <td><?= htmlspecialchars($user['Nombre']) ?></td>
                                <td><?= htmlspecialchars($user['Telefono']) ?></td>
                                <td><?= htmlspecialchars($user['Direccion']) ?></td>
                                <td><?= htmlspecialchars($user['Email']) ?></td>
                                <td>
                                    <form action="index.php" method="GET">
                                        <input type="hidden" name="action" value="openFormProveedor" />
                                        <input type="hidden" name="docProveedor" value="<?= htmlspecialchars($user['DocProveedor']) ?>" />
                                        <button type="submit">Actualizar</button>
                                    </form>
                                </td>
                                <td>
                                    <form action="index.php?action=eliminarProveedor" method="POST" onsubmit="return confirm('¿Estás seguro que deseas eliminar este proveedor?');">
                                        <input type="hidden" name="DocProveedor" value="<?= htmlspecialchars($user['DocProveedor']) ?>" />
                                        <button type="submit">Eliminar</button>
                                    </form>
                                </td>
                                <td>
                                    <form action="index.php" method="GET" target="_blank">
                                        <input type="hidden" name="action" value="generarPDFProveedor" />
                                        <input type="hidden" name="docProveedor" value="<?= htmlspecialchars($user['DocProveedor']) ?>" />
                                        <button type="submit">PDF</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No se encontraron proveedores.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
