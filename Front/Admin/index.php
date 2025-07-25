<!DOCTYPE html>
<html lang="es">
   
<head>
     <?php 
    require_once '../verificarSesion.php';
    verificarAcceso(['Admin']);
?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senda Firme - Dashboard</title>
    <link rel="stylesheet" href="../Css/estilosAdmin.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="contenedor-dashboard">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo-dashboard">
                <img src="../../Fotos/LogoNegro.webp" alt="Logo Cooperativa">
                <span>Senda Firme</span>
                <p>Construyendo oportunidades juntos</p>
            </div>

            <nav id="NavegacionDashboard">
                <ul class="menu-dashboard">
                    <li class="item-menu activo">
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
                    <img src="https://via.placeholder.com/40" alt="Foto perfil" class="fotoPerfil">
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

        <main class="contenido-principal">
            <header class="header-principal">
                <h1>Bienvenido, <span class="nombre-usuario-destacado nombreAdmin">Nombre Admin</span></h1>
                <p>Aquí puedes gestionar todas tus actividades en la cooperativa</p>
            </header>

            <div class="contenedor-tarjetas">
                <!-- Tarjeta de resumen de horas trabajadas -->
                <a href="solicitudes.php">
                    <div class="tarjeta-dashboard">
                        <div class="tarjeta-icono">
                            <i class="material-icons">pending_actions</i>
                        </div>
                        <div class="tarjeta-contenido">
                            <h3>Solicitudes Pendientes</h3>
                            <p class="tarjeta-valor" id="solicitudesPendientes">10 <span>Solicitudes</span></p>
                        </div>
                    </div>
                </a>

                <!-- Tarjeta de proyectos activos -->
                <a href="">
                    <div class="tarjeta-dashboard">
                        <div class="tarjeta-icono">
                            <i class="material-icons">apartment</i>
                        </div>
                        <div class="tarjeta-contenido">
                            <h3>Proyectos activos</h3>
                            <p class="tarjeta-valor">3</p>
                            <p class="tarjeta-subtexto">Participando</p>
                        </div>
                    </div>
                </a>

                <!-- Tarjeta de reuniones pendientes -->
                <a href="">
                    <div class="tarjeta-dashboard">
                        <div class="tarjeta-icono">
                            <i class="material-icons">event</i>
                        </div>
                        <div class="tarjeta-contenido">
                            <h3>Reuniones</h3>
                            <p class="tarjeta-valor">2</p>
                            <p class="tarjeta-subtexto">Próximas</p>
                        </div>
                    </div>
                </a>

                <!-- Tarjeta de estado financiero -->
                <a href="">
                    <div class="tarjeta-dashboard">
                        <div class="tarjeta-icono">
                            <i class="material-icons">payments</i>
                        </div>
                        <div class="tarjeta-contenido">
                            <h3>Cuota</h3>
                            <p class="tarjeta-valor">$1,250</p>
                            <p class="tarjeta-subtexto">A pagar</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="contenedor-secciones">
                <!-- Sección de actividades recientes -->
                <section class="seccion-actividades">
                    <h2>Actividades recientes</h2>
                    <div class="lista-actividades">
                        <div class="actividad">
                            <i class="material-icons actividad-icono">check_circle</i>
                            <div class="actividad-detalle">
                                <p>Registro de horas trabajadas</p>
                                <span class="actividad-fecha">Hoy, 10:30 AM</span>
                            </div>
                        </div>
                        <div class="actividad">
                            <i class="material-icons actividad-icono">event_available</i>
                            <div class="actividad-detalle">
                                <p>Asistencia a reunión confirmada</p>
                                <span class="actividad-fecha">Ayer, 3:45 PM</span>
                            </div>
                        </div>
                        <div class="actividad">
                            <i class="material-icons actividad-icono">assignment_turned_in</i>
                            <div class="actividad-detalle">
                                <p>Tarea completada en proyecto "Huerto comunitario"</p>
                                <span class="actividad-fecha">Ayer, 1:20 PM</span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Sección de próximos eventos -->
                <section class="seccion-eventos">
                    <h2>Próximos eventos</h2>
                    <div class="lista-eventos">
                        <div class="evento">
                            <div class="evento-fecha">
                                <span class="evento-dia">15</span>
                                <span class="evento-mes">Jul</span>
                            </div>
                            <div class="evento-detalle">
                                <h3>Reunión general</h3>
                                <p>Auditorio principal - 4:00 PM</p>
                            </div>
                        </div>
                        <div class="evento">
                            <div class="evento-fecha">
                                <span class="evento-dia">18</span>
                                <span class="evento-mes">Jul</span>
                            </div>
                            <div class="evento-detalle">
                                <h3>Trabajo comunitario</h3>
                                <p>Parque central - 8:00 AM</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>
    <script src="../Javascript/BackOffice/index.js" type="module"></script>
    <script src="../Javascript/BackOffice/generalidades.js" type="module"></script>
</body>

</html>