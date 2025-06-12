<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../images/icono.png" type="image/x-ico">
    <link rel="stylesheet" href="CSS/menu.css">
    <title>Inventario</title>
</head>

<body>
    <div class="centrado">
        <h1 >INVENTARIO</h1>

        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'encargada'): ?>
        <form action="index3.php?action=listaEmpleados" method="GET">
            <button type="submit" name="action" value="listaEmpleados" class="btn-consulta">Empleado</button>
        </form><br>
        <?php endif; ?>

        <form action="index3.php?action=listaProveedores" method="GET">
            <button type="submit" name="action" value="listaProveedores" class="btn-consulta">Proveedor</button>
        </form><br>

        <form action="index3.php?action=consultaproducto" method="GET">
            <button type="submit" name="action" value="consultaproducto" class="btn-consulta">Producto</button>
        </form><br>


        <form action="index3.php?action=listaEntradas" method="GET">
            <button type="submit" name="action" value="listaEntradas" class="btn-consulta">Entrada</button>
        </form><br>

        <form action="index3.php?action=listaSalidas" method="GET">
            <button type="submit" name="action" value="listaSalidas" class="btn-consulta">Salida</button>
        </form><br>


        <form action="index3.php?action=listaPedidos" method="GET">
            <button type="submit" name="action" value="listaPedidos" class="btn-consulta">Pedido</button>
        </form><br>

        <form action="index3.php?action=listaDetallePedidos" method="GET">
            <button type="submit" name="action" value="listaDetallePedidos" class="btn-consulta">Detalle Pedido</button>
        </form><br>

        <!-- Botón Cerrar sesión -->
        <form action="index3.php?action=logout" method="GET">
            <button type="submit" name="action" value="logout" class="btn-cerrar-sesion">
                Cerrar sesión
            </button>
        </form>
    </div>

    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'encargada'): ?>
        <form action="ingresar.php" method="post" class="boton">
            <button type="submit" name="action" value="dashboard" style="position: absolute; top: 20px; right: 10px; background-color: #d2a679; 
            padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; font-size: 18px;">
                Registrar usuario
            </button>
        </form>
    <?php endif; ?>

</body>

</html> 