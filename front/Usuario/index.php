<?php
require_once '../verificarSesion.php';
verificarAcceso(['Usuario', 'Admin']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senda Firme - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../Css/estilosUsuario.css">
</head>

<body>
    <!-- Botón hamburguesa para móviles -->
    <button class="boton-hamburguesa" id="botonHamburguesa">
        <span></span>
    </button>
    <div class="overlay" id="overlay"></div>

    <div class="contenedor-dashboard">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
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
                        <a href="horas.php"><i class="material-icons">punch_clock</i> Horas Trabajadas</a>
                    </li>
                    <li class="item-menu">
                        <a href="#Proyectos"><i class="material-icons">apartment</i> Proyectos</a>
                    </li>
                    <li class="item-menu">
                        <a href="pagos.php"><i class="material-icons">payments</i> Pagos</a>
                    </li>
                    <li class="item-menu">
                        <a href="configuracion.php"><i class="material-icons">settings</i> Configuración</a>
                    </li>
                </ul>
            </nav>

            <div class="perfil-usuario">
                <a href="configuracion.php">
                    <div class="info-usuario">
                        <img src="" alt="Foto perfil" class="fotoPerfil">
                        <div>
                            <p class="nombre-usuario nombreUsuario">Nombre User</p>
                            <p class="rol-usuario">Usuario</p>
                        </div>
                    </div>
                </a>
                <form action="../cerrarSesion.php">
                    <button class="boton-cerrar-sesion">
                        <i class="material-icons">logout</i> Cerrar sesión
                    </button>
                </form>
                <button id="boton-cambiar-sesion">
                    <i class="material-icons">switch_account</i> Cambiar a Admin
                </button>
            </div>
        </aside>

        <main class="contenido-principal">
            <header class="header-principal">
                <h1>Bienvenido, <span class="nombre-usuario-destacado nombreUsuario">Nombre User</span></h1>
                <p>Aquí puedes gestionar todas tus actividades en la cooperativa</p>
            </header>

            <div class="contenedor-tarjetas">
                <!-- Tarjeta de resumen de horas trabajadas -->
                <!-- Tarjeta de pagos atrasados -->
                <a href="pagos.php">
                    <div class="tarjeta-dashboard" id="cardPagosAtrasados">
                        <div class="tarjeta-icono" id="iconPagosAtrasados">
                            <i class="material-icons">warning</i>
                        </div>
                        <div class="tarjeta-contenido">
                            <h3>Pagos Atrasados</h3>
                            <p class="tarjeta-valor" id="pagosAtrasadosCantidad">.</p>
                            <p class="tarjeta-subtexto" id="pagosAtrasadosTotal">Total: $0</p>
                        </div>
                    </div>
                </a>

                <a href="horas.php">
                    <div class="tarjeta-dashboard">
                        <div class="tarjeta-icono">
                            <i class="material-icons">punch_clock</i>
                        </div>
                        <div class="tarjeta-contenido">
                            <h3>Horas Trabajadas</h3>
                            <p class="tarjeta-valor" id="horasTrabajadas">.</p>
                            <p class="tarjeta-subtexto">Esta Semana</p>
                        </div>
                    </div>
                </a>

                <!-- Tarjeta de reuniones pendientes -->
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

                <!-- Tarjeta de estado financiero -->
                <div class="tarjeta-dashboard">
                    <div class="tarjeta-icono">
                        <i class="material-icons">analytics</i>
                    </div>
                    <div class="tarjeta-contenido">
                        <h3>Asistencias</h3>
                        <p class="tarjeta-valor" id="porcentajeFaltas">.</p>
                        <p class="tarjeta-subtexto">De las reuniones</p>
                    </div>
                </div>
            </div>

            <div class="contenedor-secciones">
                <!-- Sección de actividades recientes -->
                <section class="seccion-actividades">
                    <h2>Reuniones Completadas</h2>
                    <div class="lista-actividades">
                        <div id="contenedorTerminadas">
                            <!-- Contenido generado dinámicamente -->
                        </div>
                    </div>
                </section>

                <!-- Sección de próximos eventos -->
                <section class="seccion-eventos">
                    <h2>Proximas Reuniones</h2>
                    <div class="lista-eventos">
                        <div id="contenedorPendientes">
                            <!-- Contenido generado dinámicamente -->
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

            <div class="modal-footer">
                <button class="boton-modal boton-secundario" id="botonCerrarModal">Cerrar</button>
            </div>
        </div>
    </div>

    <script>
        // Funcionalidad del menú hamburguesa
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

        // Cerrar menú al hacer clic en un enlace (en móviles)
        document.querySelectorAll('.item-menu a').forEach(enlace => {
            enlace.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    toggleMenu();
                }
            });
        });

        // Submenús
        document.querySelectorAll(".item-menu > a").forEach(boton => {
            boton.addEventListener("click", function (e) {
                // Evita que redireccione si tiene submenu
                if (this.nextElementSibling && this.nextElementSibling.classList.contains("submenu")) {
                    e.preventDefault();
                    this.parentElement.classList.toggle("open");
                }
            });
        });

        // Ajustar el menú al cambiar el tamaño de la ventana
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

</body>

</html>