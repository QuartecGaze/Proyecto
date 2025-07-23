<!DOCTYPE html>
<html lang="es">

<head>
    <?php 
    require_once '../verificarSesion.php';
    verificarAcceso(['Admin']);
?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senda Firme - Perfil</title>
    <link rel="stylesheet" href="../Css/estilosConfiguracion.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="contenedor-dashboard">
        <!-- Sidebar (igual que en index.html) -->
        <aside class="sidebar">
            <div class="logo-dashboard">
                <img src="../../Fotos/LogoNegro.webp" alt="Logo Cooperativa">
                <span>Senda Firme</span>
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
                    <li class="item-menu activo">
                        <a href="configuracion.php"><i class="material-icons">settings</i> Configuración</a>
                    </li>
                </ul>
            </nav>

            <div class="perfil-usuario">
                <a href="configuracion.php">
                    <div class="info-usuario">
                        <img src="../../Fotos/account-admin.webp" alt="Foto perfil">
                        <div>
                            <p class="nombre-usuario">Nombre Admin</p>
                            <p class="rol-usuario">Administrador</p>
                        </div>
                    </div>
                </a>
                <form action="../cerrarSesion.php">
                <button class="boton-cerrar-sesion">
                    <i class="material-icons">logout</i> Cerrar sesión
                </button>
                </form>
            </div>
        </aside>

        <!-- Contenido principal del perfil -->
        <main class="contenido-principal">
            <header class="header-principal">
                <h1>Mi Perfil</h1>
                <p>Gestiona tu información personal y preferencias</p>
            </header>

            <div class="contenedor-perfil">
                <section class="seccion-info-personal">
                    <div class="foto-perfil-container">
                        <img src="https://via.placeholder.com/150" alt="Foto de perfil" class="foto-perfil">
                        <button class="boton-cambiar-foto">
                            <i class="material-icons">image_search</i> Cambiar foto
                        </button>
                        <button class="boton-cambiar-datos">
                            <i class="material-icons">edit</i> Cambiar datos personales
                        </button>
                    </div>

                    <div class="info-personal">
                        <h2>Información personal</h2>
                        <div class="campo-perfil">
                            <label>Nombre completo</label>
                            <p class="valor-perfil">Diego Luis Charlo Arce</p>
                        </div>
                        <div class="campo-perfil">
                            <label>Correo electrónico</label>
                            <p class="valor-perfil">alainarce39@gmail.com</p>
                        </div>
                        <div class="campo-perfil">
                            <label>Teléfono</label>
                            <p class="valor-perfil">+598 92 343 168</p>
                        </div>
                        <div class="campo-perfil">
                            <label>Dirección</label>
                            <p class="valor-perfil">Av.Gral Rivera 3729, Buceo, Montevideo</p>
                        </div>
                        <div class="campo-perfil">
                            <label>Fecha de ingreso</label>
                            <p class="valor-perfil">15 de Enero, 2020</p>
                        </div>
                    </div>
                </section>

                <section class="seccion-estadisticas">
                    <h2>Mis estadísticas</h2>
                    <div class="estadisticas-grid">
                        <div class="estadistica-item">
                            <i class="material-icons">punch_clock</i>
                            <div>
                                <h3>Horas trabajadas</h3>
                                <p>1,250 horas totales</p>
                            </div>
                        </div>
                        <div class="estadistica-item">
                            <i class="material-icons">apartment</i>
                            <div>
                                <h3>Proyectos</h3>
                                <p>8 proyectos completados</p>
                            </div>
                        </div>
                        <div class="estadistica-item">
                            <i class="material-icons">event</i>
                            <div>
                                <h3>Asistencia</h3>
                                <p>95% de reuniones asistidas</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>
    <script src="../Javascript/admin.js" type="module"></script>
</body>
</html>