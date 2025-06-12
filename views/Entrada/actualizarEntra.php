<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Entrada</title>
    <link rel="stylesheet" href="CSS/actualizarEntrada.css">
</head>
<body>
    <button class="btn-regresar" onclick="window.location.href='index3.php?action=listaEntradas'">
        Regresar
    </button>
    
    <div class="container">
        <?php if (isset($entrada)): ?>
        <form action="index3.php?action=actualizarEntrada" method="POST">
            <input type="hidden" name="idEntrada" value="<?= htmlspecialchars($entrada['idEntrada']); ?>">

            <h1>Actualizar Entrada</h1>

            <label for="descripcionEntrada">Descripción del producto:</label>
            <input type="text" name="descripcionEntrada" id="descripcionEntrada"
                   value="<?= htmlspecialchars($entrada['DescripcionEntrada']); ?>" required>

            <label for="cantidadEntrada">Cantidad del producto:</label>
            <input type="number" name="cantidadEntrada" id="cantidadEntrada"
                   value="<?= htmlspecialchars($entrada['CantidadEntrada']); ?>" required>

            <label for="fechaEntrada">Fecha y hora de entrada del producto:</label>
            <input type="datetime-local" name="fechaEntrada" id="fechaEntrada"
                   value="<?= htmlspecialchars($entrada['FechaEntrada']); ?>" required>

            <label for="precioUni">Precio unitario:</label>
            <input type="text" name="precioUni" id="precioUni"
                   value="<?= '$' . number_format($entrada['PrecioUni'], 0, ',', '.') ?>" required>

            <label for="codigo">Código:</label>
            <select name="codigo" id="codigo" required>
                <option value="">Escoja código del producto</option>
                <?php
                include_once('./controllers/UserController.php');
                $numE = new UserController();
                $arraynumE = $numE->getCodigoP();
                foreach ($arraynumE as $num) {
                    $selected = ($entrada['Codigo'] === $num['Codigo']) ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($num['Codigo']) . "' $selected>"
                        . htmlspecialchars($num['Codigo']) . "</option>";
                }
                ?>
            </select>

            <input type="submit" value="Guardar Cambios">
        </form>
        <?php else: ?>
            <p style="color:red;">No se encontró la entrada para actualizar.</p>
        <?php endif; ?>
    </div>
    <script>
    const precioInput = document.getElementById('precioUni');

    // Formatear al escribir
    precioInput.addEventListener('input', function (e) {
        let valor = e.target.value.replace(/\D/g, '');
        if (valor) {
            valor = new Intl.NumberFormat('es-CO').format(valor);
            e.target.value = '$' + valor;
        } else {
            e.target.value = '';
        }
    });

    // Quitar formato al enviar
    document.querySelector('form').addEventListener('submit', function (e) {
        const input = document.getElementById('precioUni');
        input.value = input.value.replace(/\D/g, '');
    });
</script>

</body>
</html>
