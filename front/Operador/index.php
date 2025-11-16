<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senda Firme - Dashboard Admin</title>
    <link rel="stylesheet" href="../Css/estilosAdmin.css">
    <link rel="stylesheet" href="../Css/backoffice.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="backoffice">
    <!-- Botón hamburguesa para móviles -->
    <button class="boton-hamburguesa" id="botonHamburguesa">
        <span></span>
    </button>
    <div class="overlay" id="overlay"></div>

    <div class="contenedor-dashboard">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="logo-dashboard">
                <img src="../../Fotos/logoBack.webp" alt="Logo Cooperativa">
                <span>Senda Firme</span>
                <p>Construyendo oportunidades juntos</p>
            </div>

            <nav id="NavegacionDashboard">
                <ul class="menu-dashboard">
                    <li class="item-menu activo">
                        <a href="index.php"><i class="material-icons">home</i> Inicio</a>
                    </li>
                    <li class="item-menu">
                        <a href="reuniones.php"><i class="material-icons">event</i> Reuniones</a>
                    </li>
                    <li class="item-menu">
                        <a href="socios.php"><i class="material-icons">people</i> Socios</a>
                    </li>
                    <li class="item-menu">
                        <a href="#" class="submenu-toggle">
                            <i class="material-icons">apartment</i> Unidades Habitacionales
                        </a>
                        <ul class="submenu">
                            <a href="unidades.php"><i class="material-icons">home_work</i> Gestionar Proyectos</a>
                            <a href="crearUnidad.php"><i class="material-icons">add_circle</i> Crear Unidad</a>
                        </ul>
                    </li>
                    <li class="item-menu">
                        <a href="#" class="submenu-toggle">
                            <i class="material-icons">payments</i> Pagos
                        </a>
                        <ul class="submenu">
                            <li><a href="confirmarPagos.php"><i class="material-icons">receipt_long</i> Corroborar
                                    Comprobantes</a></li>
                            <li><a href="pagos.php"><i class="material-icons">point_of_sale</i> Gestor de Pagos</a></li>
                        </ul>
                    </li>
                    <li class="item-menu">
                        <a href="solicitudes.php"><i class="material-icons">email</i> Solicitudes</a>
                    </li>
                    <li class="item-menu">
                        <a href="faltas.php">
                            <i class="material-icons">punch_clock</i> Faltas de Horas
                        </a>
                    </li>
                    <li class="item-menu">
                        <a href="configuracion.php">
                            <i class="material-icons">settings</i> Configuracion
                        </a>
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
                <button id="boton-cambiar-sesion">
                    <i class="material-icons">switch_account</i> Cambiar a Usuario
                </button>
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
                            <p class="tarjeta-valor" id="solicitudesPendientes"><span>Solicitudes</span></p>
                        </div>
                    </div>
                </a>

                <!-- Tarjeta de proyectos activos -->
                <a href="faltas.php">
                    <div class="tarjeta-dashboard">
                        <div class="tarjeta-icono">
                            <i class="material-icons">punch_clock</i>
                        </div>
                        <div class="tarjeta-contenido">
                            <h3>Faltas de horas Pendientes</h3>
                            <p class="tarjeta-valor" id="faltasEnEspera">.</p>
                            <p class="tarjeta-subtexto">Por corroborar</p>
                        </div>
                    </div>
                </a>
                <a href="reuniones.php">
                <div class="tarjeta-dashboard">
                    <div class="tarjeta-icono">
                        <i class="material-icons">event</i>
                    </div>
                    <div class="tarjeta-contenido">
                        <h3>Reuniones</h3>
                        <p class="tarjeta-valor" id="reunionesPendientes">.</p>
                        <p class="tarjeta-subtexto">Próximas</p>
                    </div>
                </div>
                </a>

                <!-- Tarjeta de estado financiero -->
                <a href="confirmarPagos.php">
                    <div class="tarjeta-dashboard">
                        <div class="tarjeta-icono">
                            <i class="material-icons">payments</i>
                        </div>
                        <div class="tarjeta-contenido">
                            <h3>Comprobantes Enviados</h3>
                            <p class="tarjeta-valor" id="comprobantesPendientes">.</p>
                            <p class="tarjeta-subtexto">Por Revisar</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="contenedor-secciones">
                <!-- Sección de actividades recientes -->
                <section class="seccion-actividades">
                    <h2>Reuniones Completadas</h2>
                    <div class="lista-actividades">
                        <div id="contenedorTerminadas">
                            <div class="actividad">
                                <i class="material-icons actividad-icono">event_available</i>
                                <div class="actividad-detalle">
                                    <p>Reunion Actividades Comerciales</p>
                                    <span class="actividad-fecha">Hoy, 10:30 AM</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Sección de próximos eventos -->
                <section class="seccion-eventos">
                    <h2>Proximas Reuniones</h2>
                    <div class="lista-eventos">
                        <div id="contenedorPendientes">
                            <div class="evento">
                                <div class="evento-fecha">
                                    <span class="evento-dia">15</span>
                                    <span class="evento-mes">Jul</span>
                                </div>
                                <div class="evento-detalle">
                                    <h3>Reunión general</h3>
                                    <p>Acciones Comerciales Avanzadas - 4:00 PM</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <!-- Modal para información detallada de reunión -->
    <div id="modalReunion" class="modal-reunion">
        <div class="modal-contenido">
            <div class="modal-header" style="display:flex; align-items:center; gap:12px;">
                <h2 id="modalTitulo" style="margin:0; flex:1;">Detalles de la Reunión</h2>
                <!-- Tipo a la derecha del título -->
                <span id="modalTipo" class="modal-tipo" style="font-size:0.95rem; color:#fff;"></span>
                <span class="cerrar-modal" style="cursor:pointer;">&times;</span>
            </div>

            <div class="modal-body">
                <div class="info-reunion">
                    <!-- Estado debajo del título -->
                    <div>
                        <div class="info-item" style="margin-bottom:0.75rem;">
                            <i class="material-icons">flag</i>
                            <div class="info-detalle">
                                <h3>Estado</h3>
                                <p id="modalEstado">-</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <i class="material-icons">event</i>
                            <div class="info-detalle">
                                <h3>Fecha y Hora</h3>
                                <p id="modalFechaHora">-</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="info-item">
                            <i class="material-icons">location_on</i>
                            <div class="info-detalle">
                                <h3>Ubicación</h3>
                                <p id="modalUbicacion">-</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <i class="material-icons">description</i>
                            <div class="info-detalle">
                                <h3>Descripción</h3>
                                <p id="modalDescripcion">-</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="boton-modal boton-secundario" id="botonCerrarModal">Cerrar</button>
            </div>
        </div>
    </div>

    <script>
       
    </script>
    <script src="../Javascript/BackOffice/index.js" type="module"></script>
    <script src="../Javascript/BackOffice/generalidades.js" type="module"></script>
    <script src="../Javascript/BackOffice/reunionesAdmin.js" type="module"></script>
</body>

</html>