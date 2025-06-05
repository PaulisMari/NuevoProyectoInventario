<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="images/icono.png" type="image/x-icon" />
    <link rel="stylesheet" href="CSS/usuario.css" />
    <title>Ingreso - Telas y Costuras</title>
    <style>
        .alert {
            padding: 12px 20px;
            margin-top: 20px;
            border-radius: 5px;
            font-weight: 600;
            text-align: center;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>
    <div class="contenedor">
        <h1>Iniciar Sesión</h1>

        <img src="images/usuario.png" alt="Usuario" class="imaus" style="width: 100px; height: auto;" />

        <!-- Formulario de login -->
        <form id="login" action="index3.php?action=login" method="POST">
            <div class="input-container" for="username">
                <input type="text" id="username" name="username" placeholder="Usuario" />
                <i class="fas fa-user"></i>
            </div>

            <div class="input-container" for="password" style="position: relative;">
                <input type="password" id="password" name="password" placeholder="Contraseña" />
                <i class="fas fa-lock"></i>

                <button type="button" id="togglePasswordBtn"
                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
                    background: none; border: none; cursor: pointer; padding: 0; width: 24px; height: 24px;">
                    <!-- SVG ojo cerrado (por defecto) -->
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="#333" viewBox="0 0 24 24" width="24" height="24">
                        <path d="M12 5c-7 0-10 7-10 7s3 7 10 7 10-7 10-7-3-7-10-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10z"/>
                        <circle cx="12" cy="12" r="2.5" fill="#333"/>
                    </svg>
                </button>
            </div>

            <button class="btn-ingresar" type="submit">Ingresar</button>
        </form>

        <!-- Mostrar mensaje de error si existe -->
        <?php
        if (isset($_SESSION['error'])) : ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($_SESSION['error']); ?>
            </div>
        <?php
            unset($_SESSION['error']);
        endif;
        ?>

        <!-- Link para recuperar contraseña -->
        <p style="margin-top: 20px;">
            <a href="index3.php?action=formRecuperarPassword">¿Olvidaste tu contraseña?</a>
        </p>
    </div>

    <!-- Botón de regresar -->
    <form action="inicio.html" method="post">
        <button type="submit" name="action" value="dashboard" class="btn-regresar">Regresar</button>
    </form>

    <!-- Scripts -->
    <script>
        // Validar campos vacíos antes de enviar
        document.getElementById('login').addEventListener('submit', function (event) {
            event.preventDefault();

            var username = document.getElementById('username').value.trim();
            var password = document.getElementById('password').value.trim();

            if (username && password) {
                this.submit();
            } else {
                alert("Por favor, ingrese un nombre de usuario y contraseña");
            }
        });

        // Toggle para mostrar/ocultar contraseña con ícono ojo
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        toggleBtn.addEventListener('click', () => {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                // ojo abierto
                eyeIcon.innerHTML = `
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3" fill="#666"/>
                `;
            } else {
                passwordInput.type = 'password';
                // ojo cerrado
                eyeIcon.innerHTML = `
                    <path d="M12 5c-7 0-10 7-10 7s3 7 10 7 10-7 10-7-3-7-10-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10z"/>
                    <circle cx="12" cy="12" r="2.5" fill="#333"/>
                `;
            }
        });
    </script>
</body>

</html>
