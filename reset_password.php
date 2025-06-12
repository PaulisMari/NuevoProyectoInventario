<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Restablecer contraseña</title>
    <link rel="stylesheet" href="CSS/password.css" />

    <!-- Font Awesome para el ícono de ojo -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" 
          integrity="sha512-5J6RR4m7k5n0RlHR1IY72DLDRuGjIdD0fKKd6n66k7y9pU3Z+Bg2J3QxLmkE8VXYocvXZq5wJ47wFTxfQOBFRg==" 
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .btn-regresar-form {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1000;
        }

        .btn-regresar {
            background-color: #c89b7b;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 15px;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .contenedor-reset {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            background: #fdfdfd;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            font-family: sans-serif;
        }

        .password-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .password-container input {
            flex: 1;
            padding: 10px;
            font-size: 16px;
        }

        .togglePasswordBtn {
            background: none;
            border: none;
            cursor: pointer;
            margin-left: -35px;
            color: #666;
        }

        button[type="submit"] {
            background-color: #c89b7b;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 15px;
            font-weight: bold;
            cursor: pointer;
        }

        .alert-container {
            margin-bottom: 15px;
        }

        .alert {
            padding: 10px;
            border-radius: 10px;
            font-weight: bold;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <!-- Botón de regresar en la esquina superior izquierda -->
    <form action="index3.php?action=logout" method="GET" class="btn-regresar-form">
        <button type="submit" name="action" value="logout" class="btn-regresar">
            Regresar
        </button>
    </form>

    <div class="contenedor-reset">
        <h2>Ingresa tu nueva contraseña</h2>

        <?php if (!empty($mensaje)): ?>
            <?php
                $clase = "alert-warning";
                if (strpos($mensaje, 'actualizada correctamente') !== false) {
                    $clase = "alert-success";
                } elseif (strpos($mensaje, 'inválido') !== false) {
                    $clase = "alert-error";
                }
            ?>
            <div class="alert-container">
                <div class="alert <?= $clase ?>">
                    <?= htmlspecialchars($mensaje) ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (empty($mensaje) || strpos($mensaje, 'actualizada correctamente') === false): ?>
        <!-- Este formulario redirige a logout (igual que "Regresar") como solicitaste -->
        <form action="index3.php?action=logout" method="POST">
            <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token']) ?>">

            <label for="password">Nueva contraseña:</label>
            <div class="password-container">
                <input type="password" name="password" id="password" placeholder="Nueva contraseña" required>
                <button type="button" class="togglePasswordBtn" id="togglePasswordBtn" aria-label="Mostrar/Ocultar contraseña">
                    <i class="fa-solid fa-eye" id="eyeIcon"></i>
                </button>
            </div>

            <button type="submit">Actualizar contraseña</button>
        </form>
        <?php endif; ?>
    </div>

    <script>
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        toggleBtn.addEventListener('click', () => {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            eyeIcon.className = isHidden ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
            toggleBtn.classList.toggle('active', isHidden);
        });
    </script>
</body>
</html>
