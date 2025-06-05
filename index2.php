<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../images/icono.png" type="image/x-ico"> <!-- Ruta ajustada a ../ -->
    <link rel="stylesheet" href="CSS/menu.css"> <!-- Ruta ajustada a ../ -->
    <title>Inventario</title>
</head>

<body>
    <div class="centrado">
        <h1 style="font-size: 80px;">INVENTARIO</h1>

        <!-- Formularios con método POST para mayor seguridad -->
        <form action="index3.php?action=listaEmpleados" method="GET">
            <button type="submit" name="action" value="listaEmpleados" class="btn-consulta">Empleado</button>
        </form><br>

         <form action="index3.php?action=listaProveedores" method="GET">
            <button type="submit" name="action" value="listaProveedores">Proveedor</button>
        </form><br>

      <form action="index3.php?action=listaEntradas" method="GET">
            <button type="submit" name="action" value="listaEntradas">Entrada</button>
        </form><br>

        <form action="index3.php?action=listaSalidas" method="GET">
            <button type="submit" name="action" value="listaSalidas">Salida</button>
        </form><br>

        <form action="index3.php?action=consultaproducto" method="GET">
            <button type="submit" name="action" value="consultaproducto">Producto</button>
        </form><br>

          <form action="index3.php?action=listaPedidos" method="GET">
            <button type="submit" name="action" value="listaPedidos">Pedido</button>
        </form><br>
          <form action="index3.php?action=listaDetallePedidos" method="GET">
            <button type="submit" name="action" value="listaDetallePedidos">Detalle Pedido</button>
        </form><br>




        <form action="index3.php?action=logout" method="GET">
            <button type="submit" name="action" value="logout" class="btn-cerrar-sesion">
                Cerrar sesión
            </button>
        </form>
    </div>

    <form action="ingresar.php" method="post" class="boton">
        <button type="submit" name="action" value="dashboard" style="position: absolute; top: 20px; right: 10px; background-color: #d2a679; 
        padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; font-size: 18px;">
            Registrar usuario
        </button>
    </form>

</body>

</html>