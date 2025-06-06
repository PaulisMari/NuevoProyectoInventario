<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Usuarios</title>
    <!-- <link rel="stylesheet" href="CSS/consultasalida.css"> -->
</head>
<body>

    <div class="contenido-con-fondo">
        <h2 style="text-align: center; color: #5a3e1b;">Usuarios Registrados</h2>

        <div class="tabla-contenedor">
            <?php if (!empty($usuarios)): ?>
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
                                    <form action="index3.php?action=eliminarUsuario" method="POST" onsubmit="return confirm('¿Eliminar este usuario?');">
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <button type="submit">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay usuarios registrados.</p>
            <?php endif; ?>
        </div>

        <div style="text-align: center; margin-top: 20px;">
            <form action="index2.php" method="post">
                <button type="submit" name="action" value="dashboard">Regresar</button>
            </form>
        </div>
    </div>

</body>
</html>
