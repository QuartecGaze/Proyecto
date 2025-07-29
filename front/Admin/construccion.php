<!DOCTYPE html>
<html lang="es">
   
<head>
    <?php 
    require_once '../verificarSesion.php';
    verificarAcceso(['Admin']);
?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cooperativa Nombre - En construcción</title>
    <link rel="stylesheet" href="estilosAdmin.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="contenedor-dashboard">
        <!-- Sidebar (igual que en index.php) -->
        <aside class="sidebar">
            <div class="logo-dashboard">
                <img src="../../Fotos/Logo-1.png" alt="Logo Cooperativa">
                <span>Cooperativa Nombre</span>
                <p>Construyendo oportunidades juntos</p>
            </div>

            <nav id="NavegacionDashboard">
                <ul class="menu-dashboard">
                    <li class="item-menu">
                        <a href="index.php"><i class="material-icons">home</i> Inicio</a>
                    </li>
                    <li class="item-menu">
                        <a href="#"><i class="material-icons">event</i> Reuniones</a>
                    </li>
                    <li class="item-menu">
                        <a href="#"><i class="material-icons">people</i> Socios</a>
                    </li>
                    <li class="item-menu">
                        <a href="#"><i class="material-icons">apartment</i> Proyectos</a>
                    </li>
                    <li class="item-menu">
                        <a href="#"><i class="material-icons">payments</i> Finanzas</a>
                    </li>
                    <li class="item-menu">
                        <a href="solicitudes.php"><i class="material-icons">email</i> Solicitudes</a>
                    </li>
                    <li class="item-menu">
                        <a href="configuracion.php"><i class="material-icons">settings</i> Configuración</a>
                    </li>
                </ul>
            </nav>

            <div class="perfil-usuario">
                <div class="info-usuario">
                    <img src="" alt="Foto perfil" class="fotoPerfil">
                    <div>
                        <p class="nombre-usuario nombreAdmin">Admin User</p>
                        <p class="rol-usuario" id="rolAdmin">Administrador</p>
                    </div>
                </div>
                <form action="../cerrarSesion.php">
                    <button class="boton-cerrar-sesion">
                        <i class="material-icons">logout</i> Cerrar sesión
                    </button>
                </form>
            </div>
        </aside>

        <!-- Contenido principal modificado -->
        <main class="contenido-principal">
            <div class="contenido-construccion">
                <i class="fas fa-hard-hat icono-construccion"></i>
                <h1 class="titulo-construccion">¡Página en construcción!</h1>
                <p class="texto-construccion">
                    Estamos trabajando duro para brindarte esta funcionalidad muy pronto. 
                    Nuestro equipo está construyendo las mejores herramientas para la gestión 
                    de tu cooperativa. Disculpa las molestias y gracias por tu paciencia.
                </p>
                <a href="index.php" class="boton-volver">
                    <i class="fas fa-arrow-left"></i> Volver al inicio
                </a>
            </div>
        </main>
    </div>
    <script src="../Javascript/BackOffice/generalidades.js" type="module"></script>
</body>
</html>