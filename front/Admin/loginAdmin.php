<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso de Administrador - Senda Firme</title>
    <link rel="stylesheet" href="../Css/estilosLogin.css">
    <link rel="stylesheet" href="../Css/estilosLoginBack.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
    <header class="header-registro">
        <div class="contenedor-header">
            <div class="logo">
                <a href="../Landing Page/index.html">
                    <img src="../../Fotos/logo.webp" alt="Logo Cooperativa">
                </a>
                <span>Senda Firme</span>
            </div>
        </div>
    </header>

    <main>
        <div class="admin-login-container">
            <div class="admin-header">
                <img src="../../Fotos/logoBackBlack.webp" alt="" id="foto-header-form">
                <h1>Ingreso de Administrador</h1>
                <p>Acceso exclusivo para personal autorizado</p>
            </div>

            <div id="mensajeError" class="mensaje-error" style="display: none;"></div>

            <form class="admin-form" id="admin-login-form">
                <div class="fieldInfo">
                    <label for="cedula">Cédula</label>
                    <input type="text" id="cedula" name="cedula" required>
                </div>

                <div class="fieldInfo">
                    <label for="password">Constraseña</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" required>
                        <span class="toggle-password material-icons" id="mostrarContraseña">visibility</span>
                    </div>
                </div>

                <button type="submit" class="btn-admin-login">Ingresar</button>
            </form>

            <div class="admin-login-footer">
                <p>¿Problemas para acceder? Contacte al administrador del sistema.</p>
            </div>
        </div>
    </main>

    <footer class="footer-registro">
        <p class="footer">&copy; 2025 Senda Firme. Todos los derechos reservados.</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('admin-login-form');
            const togglePassword = document.getElementById('mostrarContraseña');
            const passwordInput = document.getElementById('password');

            // Alternar visibilidad de contraseña
            togglePassword.addEventListener('click', function () {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    togglePassword.textContent = 'visibility_off';
                } else {
                    passwordInput.type = 'password';
                    togglePassword.textContent = 'visibility';
                }
            });
        });
    </script>
</body>

</html>