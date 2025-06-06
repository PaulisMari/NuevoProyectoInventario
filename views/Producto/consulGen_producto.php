<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Consulta General de Productos</title>
    <link rel="stylesheet" href="CSS/consultasalida.css" />
</head>
<body>

<!-- FORMULARIO DE BÚSQUEDA CENTRADO -->
<div class="form-busqueda">
    <form action="index3.php" method="get" class="form-busqueda-form">
        <input type="hidden" name="action" value="searchproductoByNumberDocumento" />
        <label for="Codigo">Buscar por Código del Producto</label>
        <input
            type="text"
            id="Codigo"
            name="Codigo"
            required
            placeholder="Ingrese código del producto"
            class="input-codigo"
            value="<?= htmlspecialchars($_GET['Codigo'] ?? '') ?>"
        />
        <div class="botones-busqueda">
            <button type="submit">Buscar</button>
            <button type="button" onclick="window.location.href='index3.php?action=consultaproducto'">Limpiar</button>
        </div>
    </form>
</div>

<!-- CONTENIDO CON FONDO BLANCO SEMITRANSPARENTE -->
<div class="fondo-desenfocado"></div>
<div class="contenido-con-fondo">
    <h2 style="text-align: center; color: #5a3e1b;">Consulta General de Productos</h2>

    <!-- BOTONES INSERTAR Y PDF -->
    <div class="botones-centro">
        <form action="index3.php" method="GET">
            <input type="hidden" name="action" value="insertproducto" />
            <button type="submit">+ Insertar Producto</button>
        </form>

        <form action="index3.php" method="GET" target="_blank">
            <input type="hidden" name="action" value="generarPDFProductos" />
            <button type="submit">PDF General Productos</button>
        </form>
    </div>

    <!-- TABLA CON SCROLL -->
    <div class="tabla-contenedor">
        <?php
        // ORDENAMIENTO: productos con CantDis < CantMin primero
        if (isset($userspros) && is_array($userspros)) {
            usort($userspros, function($a, $b) {
                $a_estado = ($a['CantDis'] < $a['CantMin']) ? 0 : 1;
                $b_estado = ($b['CantDis'] < $b['CantMin']) ? 0 : 1;
                return $a_estado - $b_estado;
            });
        }
        ?>

        <?php if (isset($userspros) && count($userspros) > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Cant. Máxima</th>
                    <th>Cant. Mínima</th>
                    <th>Disponible</th>
                    <th>Creado Por</th>
                    <th>Actualizar</th>
                    <th>Eliminar</th>
                    <th>PDF</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($userspros as $userpro): ?>
                    <?php
                        $cantDis = $userpro['CantDis'];
                        $cantMin = $userpro['CantMin'];
                        $cantMax = $userpro['CantMax'];

                        if ($cantDis < $cantMin) {
                            $color = 'red';
                        } elseif ($cantDis > $cantMax) {
                            $color = '#FF8C00'; // Naranja oscuro
                        } else {
                            $color = 'green';
                        }
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($userpro['Codigo']); ?></td>
                        <td><?= htmlspecialchars($userpro['NombreProducto']); ?></td>
                        <td><?= htmlspecialchars($userpro['Descripcion']); ?></td>
                        <td>$<?= number_format($userpro['Precio'], 0); ?></td>
                        <td><?= htmlspecialchars($cantMax); ?></td>
                        <td><?= htmlspecialchars($cantMin); ?></td>
                        <td style="color: <?= $color ?>;">
                            <strong><?= htmlspecialchars($cantDis); ?></strong>
                            <?php if ($cantDis < $cantMin): ?>⚠<?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($userpro['CreadoPor']); ?></td>
                        <td>
                            <form action="index3.php" method="GET" style="display:inline;">
                                <input type="hidden" name="action" value="openFormproducto" />
                                <input type="hidden" name="Codigo" value="<?= htmlspecialchars($userpro['Codigo']); ?>" />
                                <button type="submit">Actualizar</button>
                            </form>
                        </td>
                        <td>
                            <form action="index3.php?action=eliminarproducto" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar este producto?');">
                                <input type="hidden" name="Codigo" value="<?= htmlspecialchars($userpro['Codigo']); ?>" />
                                <button type="submit">Eliminar</button>
                            </form>
                        </td>
                        <td>
                            <form action="index3.php" method="GET" target="_blank">
                                <input type="hidden" name="action" value="generarPDFProducto">
                                <input type="hidden" name="Codigo" value="<?= htmlspecialchars($userpro['Codigo']); ?>">
                                <button type="submit">PDF</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p>No se encontraron productos.</p>
        <?php endif; ?>
    </div>

    <!-- BOTÓN REGRESAR -->
    <div style="text-align: center; margin-top: 30px;">
        <form action="index2.php" method="post">
            <button type="submit" name="action" value="dashboard">Regresar</button>
        </form>
    </div>
</div> <!-- cierre de .contenido-con-fondo -->
</body>
</html>
