<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="images/icono.png" type="image/x-icon" />
    <link rel="stylesheet" href="CSS/producto.css" />
    <title>Formulario Producto</title>
    <style>
        input[type="text"].precio-input {
            font-weight: bold;
            color: black; /* COLOR NEGRO */
        }
    </style>
</head>
<body>
    <form action="index3.php" method="GET">
        <button class="btn-regresar" type="submit" name="action" value="consultaproducto">Regresar</button>
    </form>

    <div class="contenedor-principal">
        <div class="tarjeta">
            <div class="lado-izquierdo">
                <img src="images/Producto1.png" alt="Imagen de Producto" />
            </div>
            <div class="lado-derecho">
                <form id="form-producto" action="index3.php?action=insertproducto" method="POST">
                    <h1>PRODUCTO</h1>

                    <?php if (isset($_SESSION['message'])): ?>
                        <p class="mensaje"><?= $_SESSION['message']; ?></p>
                        <?php unset($_SESSION['message']); ?>
                    <?php endif; ?>

                    <label for="Codigo">Código:</label>
                    <input type="number" name="Codigo" id="Codigo" required />

                    <label for="NombreProducto">Nombre del producto:</label>
                    <input type="text" name="NombreProducto" id="NombreProducto" required />

                    <label for="Descripcion">Descripción:</label>
                    <input type="text" name="Descripcion" id="Descripcion" />

                    <label for="Precio">Precio:</label>
                    <input type="text" name="Precio" id="Precio" class="precio-input" required />

                    <label for="CantMax">Cantidad Máxima:</label>
                    <input type="number" name="CantMax" id="CantMax" required />

                    <label for="CantMin">Cantidad Mínima:</label>
                    <input type="number" name="CantMin" id="CantMin" required />

                    <label for="CantDis">Cantidad Disponible:</label>
                    <input type="number" name="CantDis" id="CantDis" required />

                    <label for="CreadoPor">Creado Por (Cédula):</label>
                    <input type="number" name="CreadoPor" id="CreadoPor" required /><br />

                    <input type="submit" value="Guardar Producto" />
                </form>
            </div>
        </div>
    </div>

    <script>
        const precioInput = document.getElementById("Precio");
        const form = document.getElementById("form-producto");

        // Mostrar $ automáticamente al escribir
        precioInput.addEventListener("input", () => {
            let valor = precioInput.value.replace(/[^\d]/g, "");
            if (valor) {
                precioInput.value = "$" + parseInt(valor).toLocaleString("es-CO", { maximumFractionDigits: 0 });
            } else {
                precioInput.value = "";
            }
        });

        // Limpiar $ y puntos antes de enviar
        form.addEventListener("submit", () => {
            precioInput.value = precioInput.value.replace(/[^\d]/g, "");
        });
    </script>
</body>
</html>
