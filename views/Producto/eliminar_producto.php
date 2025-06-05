<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Producto</title>
</head>
<body>
    <h1>Eliminar Producto</h1>
    <form action="index3.php?action=eliminarproducto" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este producto?')">
        <label for="Codigo">Codigo:</label>
        <input type="number" id="Codigo" name="Codigo" required>

        <button type="submit" class="btn btn-danger">Eliminar</button>
    </form>
</body>
</html>
