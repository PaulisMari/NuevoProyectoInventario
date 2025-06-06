<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="images/icono.png" type="image/x-icon">
    <link rel="stylesheet" href="CSS/entrada.css">
    <title>Formulario de Entrada</title>
</head>
<body>
    <!-- Botón de regresar -->
    <button class="btn-regresar" onclick="window.location.href='index3.php?action=listaEntradas'">
        Regresar
    </button>

    <div class="container"> 
        <img class="imagen" src="images/Entrada2.png" alt="Imagen de entrada">
        
        <div class="entrada">
            <form action="index3.php?action=insertEntrada" method="post">
                
                <h1>ENTRADA DEL PRODUCTO</h1>

                <label for="descripcionEntrada">Descripción del producto:</label>
                <input type="text" name="descripcionEntrada" required>

                <label for="cantidadEntrada">Cantidad del producto:</label>
                <input type="number" name="cantidadEntrada" required>

                <label for="fechaEntrada">Fecha y hora de entrada del producto:</label>
                <input type="datetime-local" name="fechaEntrada" required>

                <label for="precioUni">Precio unitario:</label>
                <input type="number" name="precioUni" id="precioUni" required>

                <label for="codigo">Código:</label>
                <select name="codigo" id="codigo" required>
                    <option value="">Escoja código del producto</option>
                    <?php
                    include_once('./controllers/UserController.php');
                    $numE = new UserController();
                    $arraynumE = $numE->getCodigoP();
                    foreach($arraynumE as $num){
                        echo "<option value='".htmlspecialchars($num['Codigo'])."'>"
                        .htmlspecialchars($num['Codigo'])."</option>";
                    }
                    ?>
                </select>

                <input type="submit" value="Guardar Entrada">
            </form>
        </div>
    </div>

    <script>
        const precioInput = document.getElementById('precioUni');

        precioInput.addEventListener('input', function (e) {
            let valor = e.target.value.replace(/\D/g, '');
            if (valor) {
                valor = new Intl.NumberFormat('es-CO').format(valor);
                e.target.value = '$' + valor;
            } else {
                e.target.value = '';
            }
        });

        document.querySelector('form').addEventListener('submit', function (e) {
            const input = document.getElementById('precioUni');
            input.value = input.value.replace(/\D/g, '');
        });
    </script>
</body>
</html>
