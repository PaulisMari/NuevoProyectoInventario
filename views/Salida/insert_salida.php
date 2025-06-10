<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="images/icono.png" type="image/x-icon">
    <link rel="stylesheet" href="CSS/salida.css">
    <title>Formulario de Salida</title>
</head>
<body>

    <!-- Botón de regresar -->
    <form action="index3.php" method="GET">
        <button class="btn-regresar" type="submit" name="action" value="listaSalidas">Regresar</button>
    </form>

    <div class="container"> 
        <img class="imagen" src="images/Salida1.png" alt="Imagen de entrada">
        <div class="salida">
            <form action="index3.php?action=insertSalida" method="post">
                <h1>SALIDA DEL PRODUCTO</h1>

                <label for="motivoSalida">Motivo de la salida:</label>
                <input type="text" name="motivoSalida" required>

                <label for="cantidadSalida">Cantidad que va a salir:</label>
                <input type="number" name="cantidadSalida" required>

                <label for="fechaSalida">Fecha y hora de salida del producto:</label>
                <input type="datetime-local" name="fechaSalida" required>

                <label for="codigo">Código:</label>
                <select name="codigo" id="codigo" required>
                    <option value="">Escoja código del producto</option>
                    <?php
                    include_once('./controllers/UserController.php');
                    $numE = new UserController();
                    $arraynumE = $numE->getCodigoProS();
                    foreach($arraynumE as $num){
                        echo "<option value='".htmlspecialchars($num['Codigo'])."'data-cantdis='".htmlspecialchars($num['CantDis'])."'>"
                        .htmlspecialchars($num['Codigo'])."</option>"; 
                    }
                    ?>
                </select>

                <input type="submit" value="Guardar Salida">
            </form>
        </div>
    </div>
    <script>
    document.querySelector('form[action="index3.php?action=insertSalida"]').addEventListener('submit', function(event) {
        const cantidadInput = document.querySelector('input[name="cantidadSalida"]');
        const codigoSelect = document.getElementById('codigo');

        const cantidad = parseInt(cantidadInput.value) || 0;
        const selectedOption = codigoSelect.options[codigoSelect.selectedIndex];
        const stockDisponible = parseInt(selectedOption.getAttribute('data-cantdis')) || 0;

        // Validar antes de enviar
        if (cantidad > stockDisponible) {
            event.preventDefault(); // Evita que se envíe el formulario
            mostrarVentana("Cantidad no permitida", "La cantidad ingresada supera el stock disponible: " + stockDisponible);
        }
    });

    function mostrarVentana(titulo, mensaje) {
        const modal = document.createElement("div");
        modal.innerHTML = `
            <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
                        background-color: rgba(0, 0, 0, 0.5); display: flex; align-items: center; justify-content: center; z-index: 9999;">
                <div style="background: white; padding: 20px; border-radius: 8px; width: 300px; text-align: center;">
                    <h2 style="margin-bottom: 10px;">${titulo}</h2>
                    <p style="margin-bottom: 20px;">${mensaje}</p>
                    <button onclick="this.closest('div').parentElement.remove()">Cerrar</button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }
</script>

</body>
</html>
