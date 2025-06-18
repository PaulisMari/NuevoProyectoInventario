<!-- reset_password.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- 👈 Necesario para que sea responsivo -->
    <title>Restablecer contraseña</title>
    <link rel="stylesheet" href="CSS/password.css">

    <!-- Ícono de ojo -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
          integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
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
            <div class="alert <?= $clase ?>">
                <?= $mensaje ?>
            </div>
        <?php endif; ?>

        <?php if (empty($mensaje) || strpos($mensaje, 'actualizada correctamente') === false): ?>
        <form action="index.php?action=resetPassword" method="POST">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">

            <label for="password">Nueva contraseña:</label>
            <div class="password-container">
                <input type="password" name="password" id="password" placeholder="Nueva contraseña" required>
                <i class="fa-solid fa-eye toggle-password" id="togglePassword"></i>
            </div>

            <button type="submit">Actualizar contraseña</button>
        </form>
        <?php endif; ?>
    </div>

    <script>
        const toggleIcon = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        toggleIcon.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            toggleIcon.classList.toggle('fa-eye');
            toggleIcon.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>