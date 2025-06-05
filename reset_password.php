<!-- reset_password.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Restablecer contraseña</title>
    <link rel="stylesheet" href="CSS/usuario.css" />
    <style>
        .alert {
            padding: 10px 20px;
            margin-top: 15px;
            border-radius: 5px;
            font-weight: bold;
        }
        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Estilos para el botón Mostrar/Ocultar */
        .password-container {
            display: flex;
            align-items: center;
            margin-top: 5px;
            margin-bottom: 15px;
        }
        .password-container input {
            flex: 1;
        }
        .togglePasswordBtn {
            background: none;
            border: none;
            color: #333;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.9rem;
            padding: 0 5px;
            margin-left: 8px;
        }
    </style>
</head>
<body>
    <h2>Ingresa tu nueva contraseña</h2>

    <?php if (!empty($mensaje)): ?>
        <?php
            // Decidir clase CSS según mensaje
            $clase = "alert-warning";
            if (strpos($mensaje, 'actualizada correctamente') !== false) {
                $clase = "alert-success";
            } elseif (strpos($mensaje, 'inválido') !== false) {
                $clase = "alert-error";
            }
        ?>
        <div class="alert <?= $clase ?>">
            <?= $mensaje /* Aquí NO usar htmlspecialchars() para que el enlace funcione */ ?>
        </div>
    <?php endif; ?>

    <!-- Mostrar el formulario solo si no hay mensaje de éxito -->
    <?php if (empty($mensaje) || strpos($mensaje, 'actualizada correctamente') === false): ?>
    <form action="index3.php?action=resetPassword" method="POST">
        <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token']) ?>">

        <label for="password">Nueva contraseña:</label><br>
        <div class="password-container">
            <input type="password" name="password" id="password" required>
            <button type="button" class="togglePasswordBtn" id="togglePasswordBtn">Mostrar</button>
        </div>

        <button type="submit">Actualizar contraseña</button>
    </form>
    <?php endif; ?>

    <script>
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');

        toggleBtn.addEventListener('click', () => {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleBtn.textContent = 'Ocultar';
            } else {
                passwordInput.type = 'password';
                toggleBtn.textContent = 'Mostrar';
            }
        });
    </script>
</body>
</html>
