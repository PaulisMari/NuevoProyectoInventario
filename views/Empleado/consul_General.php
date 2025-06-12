<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Consulta General de Empleados</title>
  <link rel="stylesheet" href="CSS/consulEmpleado.css" />
</head>
<body>

  <!-- Imagen de fondo desenfocada -->
  <div class="fondo-desenfocado"></div>

  <!-- Botón Regresar en esquina superior izquierda -->
  <form action="inventario.php" method="post" class="form-regresar">
    <button type="submit" name="action" value="dashboard">Regresar</button>
  </form>

  <!-- Formulario de búsqueda -->
  <div class="form-busqueda">
    <form action="index3.php" method="get" class="form-busqueda-form">
      <input type="hidden" name="action" value="listaEmpleados" />
      <label for="buscarDoc">Buscar documento:</label>
      <input
        type="text"
        id="buscarDoc"
        name="docEmpleado"
        required
        placeholder="Ej: 12"
        class="input-codigo"
        value="<?= htmlspecialchars($_GET['docEmpleado'] ?? '') ?>" />
      <div class="botones-busqueda">
        <button type="submit">Buscar</button>
        <button type="button" onclick="window.location.href='index3.php?action=listaEmpleados'">Limpiar</button>
      </div>
    </form>
  </div>

  <!-- Contenedor principal -->
  <div class="contenido-con-fondo">
    <h2 style="text-align: center; color: #5a3e1b;">Consulta General de Empleados</h2>

    <!-- Botones Insertar y PDF -->
    <div class="botones-centro">
      <form action="index3.php" method="GET">
        <input type="hidden" name="action" value="insertEmpleado" />
        <button type="submit">+ Insertar Empleado</button>
      </form>
      <form action="index3.php" method="GET" target="_blank">
        <input type="hidden" name="action" value="generarPDFEmpleados" />
        <button type="submit">PDF General Empleados</button>
      </form>
    </div>

    <!-- Tabla de empleados -->
    <div class="tabla-contenedor">
      <?php if (isset($users) && count($users) > 0): ?>
        <table border="1">
          <thead>
            <tr>
              <th>Número de Documento</th>
              <th>Tipo de Documento</th>
              <th>Nombre</th>
              <th>Fecha de Nacimiento</th>
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
                <td><?= htmlspecialchars($user['DocEmpleado']) ?></td>
                <td><?= htmlspecialchars($user['TipoDocumentoNombre']) ?></td>
                <td><?= htmlspecialchars($user['Nombre']) ?></td>
                <td><?= htmlspecialchars($user['FechaNaci']) ?></td>
                <td><?= htmlspecialchars($user['Telefono']) ?></td>
                <td><?= htmlspecialchars($user['Direccion']) ?></td>
                <td><?= htmlspecialchars($user['Email']) ?></td>
                <td>
                  <form action="index3.php" method="GET">
                    <input type="hidden" name="action" value="openFormEmpleado" />
                    <input type="hidden" name="docEmpleado" value="<?= htmlspecialchars($user['DocEmpleado']) ?>" />
                    <button type="submit">Actualizar</button>
                  </form>
                </td>
                <td>
                  <form action="index3.php?action=eliminarEmpleado" method="POST" onsubmit="return confirm('¿Estás seguro que deseas eliminar este empleado?');">
                    <input type="hidden" name="DocEmpleado" value="<?= htmlspecialchars($user['DocEmpleado']) ?>" />
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                  </form>
                </td>
                <td>
                  <form action="index3.php" method="GET" target="_blank">
                    <input type="hidden" name="action" value="generarPDFEmpleado" />
                    <input type="hidden" name="docEmpleado" value="<?= htmlspecialchars($user['DocEmpleado']) ?>" />
                    <button type="submit">PDF</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p>No se encontraron empleados.</p>
      <?php endif; ?>
    </div>
  </div>

</body>
</html>
