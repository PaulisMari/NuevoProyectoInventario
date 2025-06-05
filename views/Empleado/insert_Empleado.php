<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Insertar Empleado</title>
    <link rel="stylesheet" href="CSS/Empleado.css">
</head>
<body>

  <a href="index3.php?action=listaEmpleados" class="btn-regresar"> Regresar</a>


    <div class="container">
        <img class="imagen" src="images/Encargada2.png" alt="Imagen de entrada">

        <form action="index3.php?action=insertEmpleado" method="POST">
            <h2>Insertar Empleado</h2>

            <label for="DocEmpleado">Documento del Empleado:</label>
            <input type="number" name="DocEmpleado" id="DocEmpleado" 
                   pattern="[1-9][0-9]*" 
                   title="El número no puede comenzar con 0 y debe contener solo dígitos" 
                   required>

            <label for="TipoDoc">Tipo de Documento:</label>
            <select name="TipoDoc" id="TipoDoc" required>
                <option value="">-- Seleccionar --</option>
                <?php foreach ($tiposDocumento as $tipo): ?>
                <option value="<?= $tipo['id'] ?>"><?= htmlspecialchars($tipo['tipo']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="Nombre">Nombre del Empleado:</label>
            <input type="text" name="Nombre" id="Nombre" required>

            <label for="FechaNaci">Fecha de Nacimiento:</label>
            <input type="date" name="FechaNaci" id="FechaNaci" required>

            <label for="Telefono">Teléfono:</label>
            <input type="number" name="Telefono" id="Telefono" pattern="[0-9]+" title="Solo se permiten números" required>

            <label for="Direccion">Dirección:</label>
            <input type="text" name="Direccion" id="Direccion" required>

            <label for="Email">Correo Electrónico:</label>
            <input type="email" name="Email" id="Email" required>

            <button type="submit">Registrar Empleado</button>
        </form>
    </div>

</body>
</html>
