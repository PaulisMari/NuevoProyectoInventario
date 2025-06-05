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
                        echo "<option value='".htmlspecialchars($num['Codigo'])."'>"
                        .htmlspecialchars($num['Codigo'])."</option>"; 
                    }
                    ?>
                </select>

                <input type="submit" value="Guardar Salida">
            </form>
        </div>
    </div>
</body>
</html>
