<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senda Firme - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../Css/estilosUsuario.css">
</head>

<body>
    <button class="boton-hamburguesa" id="botonHamburguesa">
        <span></span>
    </button>
    <div class="overlay" id="overlay"></div>

    <div class="contenedor-dashboard">
        <aside class="sidebar" id="sidebar">
            <div class="logo-dashboard">
                <img src="../../Fotos/LogoNegro.webp" alt="Logo Cooperativa">
                <span>Senda Firme</span>
                <p class="sidebar-slogan">Construyendo oportunidades juntos</p>
            </div>

            <nav id="NavegacionDashboard">
                <ul class="menu-dashboard">
                    <li class="item-menu activo">
                        <a href="index.php">
                            <i class="material-icons">home</i>
                            <span class="sidebar-menu-inicio">Inicio</span>
                        </a>
                    </li>
                    <li class="item-menu">
                        <a href="horas.php">
                            <i class="material-icons">punch_clock</i>
                            <span class="sidebar-menu-horas">Horas Trabajadas</span>
                        </a>
                    </li>
                    <li class="item-menu">
                        <a href="unidades.php">
                            <i class="material-icons">apartment</i>
                            <span class="sidebar-menu-unidad">Unidad Habitacional</span>
                        </a>
                    </li>
                    <li class="item-menu">
                        <a href="pagos.php">
                            <i class="material-icons">payments</i>
                            <span class="sidebar-menu-pagos">Pagos</span>
                        </a>
                    </li>
                    <li class="item-menu">
                        <a href="configuracion.php">
                            <i class="material-icons">settings</i>
                            <span class="sidebar-menu-configuracion">Configuración</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="perfil-usuario">
                <a href="configuracion.php">
                    <div class="info-usuario">
                        <img src="" alt="Foto perfil" class="fotoPerfil">
                        <div>
                            <p class="nombre-usuario nombreUsuario">Nombre User</p>
                            <p class="rol-usuario perfil-rol">Usuario</p>
                        </div>
                    </div>
                </a>
                <form action="../cerrarSesion.php">
                    <button class="boton-cerrar-sesion">
                        <i class="material-icons">logout</i>
                        <span class="btn-cerrar-sesion">Cerrar sesión</span>
                    </button>
                </form>
                <button id="boton-cambiar-sesion" class="boton-cambiar-sesion">
                    <i class="material-icons">switch_account</i>
                    <span class="btn-cambiar-sesion">Cambiar a Admin</span>
                </button>
            </div>
        </aside>

        <main class="contenido-principal">
            <header class="header-principal">
                <h1>
                    <span class="dashboard-header-titulo">Bienvenido,</span>
                    <span class="nombre-usuario-destacado nombreUsuario">Nombre User</span>
                </h1>
                <p class="dashboard-header-subtitulo">Aquí puedes gestionar todas tus actividades en la cooperativa</p>
            </header>

            <div class="contenedor-tarjetas">
                <a href="pagos.php">
                    <div class="tarjeta-dashboard" id="cardPagosAtrasados">
                        <div class="tarjeta-icono" id="iconPagosAtrasados">
                            <i class="material-icons">warning</i>
                        </div>
                        <div class="tarjeta-contenido">
                            <h3 class="card-pagos-titulo">Pagos Atrasados</h3>
                            <p class="tarjeta-valor" id="pagosAtrasadosCantidad">.</p>
                            <p class="tarjeta-subtexto card-pagos-subtexto" id="pagosAtrasadosTotal">Total: $0</p>
                        </div>
                    </div>
                </a>

                <a href="horas.php">
                    <div class="tarjeta-dashboard">
                        <div class="tarjeta-icono">
                            <i class="material-icons">punch_clock</i>
                        </div>
                        <div class="tarjeta-contenido">
                            <h3 class="card-horas-titulo">Horas Trabajadas</h3>
                            <p class="tarjeta-valor" id="horasTrabajadas">.</p>
                            <p class="tarjeta-subtexto card-horas-subtexto">Esta Semana</p>
                        </div>
                    </div>
                </a>

                <div class="tarjeta-dashboard">
                    <div class="tarjeta-icono">
                        <i class="material-icons">event</i>
                    </div>
                    <div class="tarjeta-contenido">
                        <h3 class="card-reuniones-titulo">Reuniones</h3>
                        <p class="tarjeta-valor" id="reunionesPendientes">.</p>
                        <p class="tarjeta-subtexto card-reuniones-subtexto">Próximas</p>
                    </div>
                </div>

                <div class="tarjeta-dashboard">
                    <div class="tarjeta-icono">
                        <i class="material-icons">analytics</i>
                    </div>
                    <div class="tarjeta-contenido">
                        <h3 class="card-asistencias-titulo">Asistencias</h3>
                            <p class="tarjeta-valor" id="porcentajeFaltas">.</p>
                            <p class="tarjeta-subtexto card-asistencias-subtexto">De las reuniones</p>
                    </div>
                </div>
            </div>

            <div class="contenedor-secciones">
                <section class="seccion-actividades">
                    <h2 class="seccion-reuniones-completadas-titulo">Reuniones Completadas</h2>
                    <div class="lista-actividades">
                        <div id="contenedorTerminadas"></div>
                    </div>
                </section>

                <section class="seccion-eventos">
                    <h2 class="seccion-proximas-reuniones-titulo">Proximas Reuniones</h2>
                    <div class="lista-eventos">
                        <div id="contenedorPendientes"></div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <div id="modalReunion" class="modal-reunion">
        <div class="modal-contenido">
            <div class="modal-header" style="display:flex; align-items:center; gap:12px;">
                <h2 id="modalTitulo" class="modal-titulo" style="margin:0; flex:1;">Detalles de la Reunión</h2>
                <span id="modalTipo" class="modal-tipo" style="font-size:0.95rem; color:#fff;"></span>
                <span class="cerrar-modal" style="cursor:pointer;">&times;</span>
            </div>

            <div class="modal-body">
                <div class="info-reunion">
                    <div class="info-item" style="margin-bottom:0.75rem;">
                        <i class="material-icons">flag</i>
                        <div class="info-detalle">
                            <h3 class="modal-estado-label">Estado</h3>
                            <p id="modalEstado">-</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <i class="material-icons">event</i>
                        <div class="info-detalle">
                            <h3 class="modal-fecha-label">Fecha y Hora</h3>
                            <p id="modalFechaHora">-</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <i class="material-icons">location_on</i>
                        <div class="info-detalle">
                            <h3 class="modal-ubicacion-label">Ubicación</h3>
                            <p id="modalUbicacion">-</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <i class="material-icons">description</i>
                        <div class="info-detalle">
                            <h3 class="modal-descripcion-label">Descripción</h3>
                            <p id="modalDescripcion">-</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="boton-modal boton-secundario modal-boton-cerrar" id="botonCerrarModal">Cerrar</button>
            </div>
        </div>
    </div>

    <script>
        const botonHamburguesa = document.getElementById('botonHamburguesa');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        function toggleMenu() {
            botonHamburguesa.classList.toggle('activo');
            sidebar.classList.toggle('activo');
            overlay.classList.toggle('activo');
            document.body.style.overflow = sidebar.classList.contains('activo') ? 'hidden' : 'auto';
        }

        botonHamburguesa.addEventListener('click', toggleMenu);
        overlay.addEventListener('click', toggleMenu);

        document.querySelectorAll('.item-menu a').forEach(enlace => {
            enlace.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    toggleMenu();
                }
            });
        });

        document.querySelectorAll(".item-menu > a").forEach(boton => {
            boton.addEventListener("click", function (e) {
                if (this.nextElementSibling && this.nextElementSibling.classList.contains("submenu")) {
                    e.preventDefault();
                    this.parentElement.classList.toggle("open");
                }
            });
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                botonHamburguesa.classList.remove('activo');
                sidebar.classList.remove('activo');
                overlay.classList.remove('activo');
                document.body.style.overflow = 'auto';
            }
        });
    </script>
    <script type="module" src="../Javascript/FrontUsuario/cooperativa.js"></script>
    <script type="module" src="../Javascript/FrontUsuario/generalidades.js"></script>
    <script type="module" src="../Javascript/FrontUsuario/reuniones.js"></script>
    <script type="module" src="../Javascript/FrontUsuario/traduccionesUsuario.js"></script>
</body>
</html>
