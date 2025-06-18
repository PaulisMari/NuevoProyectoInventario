<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Usuarios</title>
    <link rel="stylesheet" href="CSS/consultasalida.css">
</head>
<body>
    <!-- Botón REGRESAR fuera del contenedor -->
    <div class="boton-regresar">
        <form action="ingresar.php" method="post">
            <button type="submit" name="action" value="dashboard">Regresar</button>
        </form>
    </div>

    <!-- Fondo desenfocado -->
    <div class="fondo-desenfocado"></div>

    <!-- FORMULARIO DE BÚSQUEDA CENTRADO -->
    <div class="form-busqueda">
        <form action="index.php" method="get" class="form-busqueda-form">
            <input type="hidden" name="action" value="listaUsuarios" />
            <label for="usuario">Buscar usuario</label>
            <input
                type="text"
                id="usuario"
                name="usuario"
                required
                placeholder="Ingrese el documento del usuario"
                class="input-codigo"
                value="<?= htmlspecialchars($_GET['usuario'] ?? '') ?>"
            />
            <div class="botones-busqueda">
                <button type="submit">Buscar</button>
                <button type="button" onclick="window.location.href='index.php?action=listaUsuarios'">Limpiar</button>
            </div>
        </form>
    </div>

    <!-- CONTENEDOR DE TABLA DE USUARIOS -->
    <div class="contenido-con-fondo">
        <h2 style="text-align: center; color: #5a3e1b;">Usuarios registrados</h2>

        <div class="tabla-contenedor">  
            <?php if (isset($usuarios) && count($usuarios) > 0): ?>
                <table border="1">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['id']) ?></td>
                                <td><?= htmlspecialchars($user['usuario']) ?></td>
                                <td><?= ($user['id'] == 1) ? 'Encargada' : 'Empleado' ?></td>
                                <td>
                                    <?php if ($user['id'] != 1): ?>
                                    <form action="index.php?action=eliminarUsuario" method="POST" onsubmit="return confirm('¿Eliminar este usuario?');">
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <button type="submit">Eliminar</button>
                                    </form>
                                    <?php else: ?>
                                    <span style="color: gray;">No disponible</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay usuarios registrados.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
