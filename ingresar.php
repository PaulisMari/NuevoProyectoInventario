<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Ingreso - Telas y Costuras</title>
    <link rel="stylesheet" href="CSS/ingresar.css" />
</head>
<body>
    <div class="contenedor">
        <h1>Registrarse</h1>

        <!-- Alerta de error si existe -->
        <?php if (isset($_SESSION['error'])): ?>
            <div id="alerta-error" class="alerta-error">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <img src="images/usuario.png" alt="Registro" class="imaus" />

        <form action="index3.php?action=registrar" method="POST">
            <label for="username">Usuario:</label>
            <input type="text" id="username" name="username" required />

            <label for="password">Contraseña:</label>
            <div class="password-container" style="position: relative;">
                <input type="password" id="password" name="password" required />
                <button type="button" class="togglePasswordBtn" id="togglePasswordBtn" aria-label="Mostrar contraseña"
                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
                           background: none; border: none; cursor: pointer; padding: 0; width: 24px; height: 24px;">
                    <!-- Ícono ojo cerrado (contraseña oculta) -->
                    <svg id="iconEye" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="#333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" >
                        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </div>

            <button type="submit" class="btn-registrar">Crear usuario</button>
        </form>
    </div>

    <button class="btn-regresar" onclick="history.back()">Regresar</button>

    <script>
        // Ocultar mensaje error después de 9 segundos
        const alerta = document.getElementById('alerta-error');
        if (alerta) {
            setTimeout(() => {
                alerta.style.display = 'none';
            }, 9000);
        }

        // Mostrar/Ocultar contraseña con cambio de ícono ojo
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');
        const iconEye = document.getElementById('iconEye');

        toggleBtn.addEventListener('click', () => {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                // Ojo abierto (contraseña visible)
                iconEye.innerHTML = `
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3" fill="#666"></circle>
                `;
                toggleBtn.setAttribute('aria-label', 'Ocultar contraseña');
            } else {
                passwordInput.type = 'password';
                // Ojo cerrado (contraseña oculta)
                iconEye.innerHTML = `
                    <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                `;
                toggleBtn.setAttribute('aria-label', 'Mostrar contraseña');
            }
        });
    </script>
</body>
</html>
