<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senda Firme - Perfil</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../Css/estilosConfiguracion.css">
</head>

<body>
    <!-- Botón hamburguesa para móviles -->
    <button class="boton-hamburguesa" id="botonHamburguesa">
        <span></span>
    </button>

    <!-- Overlay para móviles -->
    <div class="overlay" id="overlay"></div>

    <div class="contenedor-dashboard">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="logo-dashboard">
                <img src="../../Fotos/LogoNegro.webp" alt="Logo Cooperativa">
                <span>Senda Firme</span>
                <p class="sidebar-slogan">Construyendo oportunidades juntos</p>
            </div>

            <nav id="NavegacionDashboard">
                <ul class="menu-dashboard">
                    <li class="item-menu">
                        <a href="index.php">
                            <i class="material-icons">home</i>
                            <span class="menu-inicio">Inicio</span>
                        </a>
                    </li>
                    <li class="item-menu">
                        <a href="horas.php">
                            <i class="material-icons">punch_clock</i>
                            <span class="menu-horas-trabajadas">Horas Trabajadas</span>
                        </a>
                    </li>
                    <li class="item-menu">
                        <a href="unidades.php">
                            <i class="material-icons">apartment</i>
                            <span class="menu-unidad-habitacional">Unidad Habitacional</span>
                        </a>
                    </li>
                    <li class="item-menu">
                        <a href="pagos.php">
                            <i class="material-icons">payments</i>
                            <span class="menu-pagos">Pagos</span>
                        </a>
                    </li>
                    <li class="item-menu activo">
                        <a href="configuracion.php">
                            <i class="material-icons">settings</i>
                            <span class="menu-configuracion">Configuración</span>
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
                            <p class="rol-usuario sidebar-rol-usuario">Usuario</p>
                        </div>
                    </div>
                </a>
                <form action="../cerrarSesion.php">
                    <button class="boton-cerrar-sesion">
                        <i class="material-icons">logout</i>
                        <span class="boton-cerrar-sesion">Cerrar sesión</span>
                    </button>
                </form>
                <button id="boton-cambiar-sesion">
                    <i class="material-icons">switch_account</i>
                    <span class="boton-cambiar-admin">Cambiar a Admin</span>
                </button>
            </div>
        </aside>

        <!-- Contenido principal del perfil -->
        <main class="contenido-principal">
            <header class="header-principal">
                <h1 class="perfil-titulo">Mi Perfil</h1>
                <p class="perfil-subtitulo">Gestiona tu información personal y preferencias</p>
            </header>

            <div class="contenedor-perfil">
                <section class="seccion-info-personal">
                    <div class="foto-perfil-container">
                        <img src="" alt="Foto de perfil" class="foto-perfil fotoPerfil">

                        <!-- Botón para abrir el selector de archivos -->
                        <button type="button" class="boton-cambiar-foto" id="btnCambiarFoto">
                            <i class="material-icons">image_search</i>
                            <span class="boton-cambiar-foto">Cambiar foto</span>
                        </button>

                        <!-- Input de archivo oculto, fuera del botón -->
                        <input type="file" id="subir-foto" accept="image/*" style="display:none;">

                        <button class="boton-cambiar-datos">
                            <i class="material-icons">edit</i>
                            <span class="boton-cambiar-datos-personales">Cambiar datos personales</span>
                        </button>
                    </div>


                    <div class="info-personal">
                        <h2 class="info-personal-titulo">Información personal</h2>
                        <form id="formulario-editar-datos" style="display: none;">
                            <div class="campo-perfil">
                                <label for="nombreInput" class="perfil-label-nombre">Nombre</label>
                                <input type="text" id="nombreInput" class="input-editar">
                            </div>
                            <div class="campo-perfil">
                                <label for="apellidoInput" class="perfil-label-apellido">Apellido</label>
                                <input type="text" id="apellidoInput" class="input-editar">
                            </div>
                            <div class="campo-perfil">
                                <label for="emailInput" class="perfil-label-email">Correo electrónico</label>
                                <input type="email" id="emailInput" class="input-editar">
                            </div>
                            <div class="campo-perfil">
                                <label for="telefonoInput" class="perfil-label-telefono">Teléfono</label>
                                <input type="tel" id="telefonoInput" class="input-editar">
                            </div>
                            <div class="campo-perfil">
                                <label for="fechaNacimientoInput" class="perfil-label-fecha-nacimiento">Fecha de Nacimiento</label>
                                <input type="date" id="fechaNacimientoInput" class="input-editar">
                            </div>
                            <div class="campo-perfil">
                                <label class="perfil-label-direccion">Dirección</label>
                                <p class="valor-perfil" id="direccionUsuarioDisplay">Pasaje 2 unidad 31</p>
                            </div>
                            <div class="campo-perfil">
                                <label class="perfil-label-fecha-ingreso">Fecha de Ingreso a la cooperativa</label>
                                <p class="valor-perfil" id="fechaIngreso"></p>
                            </div>
                            <div class="botones-edicion">
                                <button type="submit" class="boton-guardar">
                                    <i class="material-icons">save</i>
                                    <span class="perfil-boton-guardar-cambios">Guardar cambios</span>
                                </button>
                                <button type="button" class="boton-cancelar">
                                    <i class="material-icons">cancel</i>
                                    <span class="perfil-boton-cancelar">Cancelar</span>
                                </button>
                            </div>
                        </form>

                        <div id="info-solo-lectura">
                            <div class="campo-perfil">
                                <label class="perfil-label-nombre-completo">Nombre completo</label>
                                <p class="valor-perfil nombreUsuario"></p>
                            </div>
                            <div class="campo-perfil">
                                <label class="perfil-label-email">Correo electrónico</label>
                                <p class="valor-perfil" id="emailUsuario"></p>
                            </div>
                            <div class="campo-perfil">
                                <label class="perfil-label-telefono">Teléfono</label>
                                <p class="valor-perfil" id="telefonoUsuario"></p>
                            </div>
                            <div class="campo-perfil">
                                <label class="perfil-label-direccion">Dirección</label>
                                <p class="valor-perfil" id="direccionUsuario"></p>
                            </div>
                            <div class="campo-perfil">
                                <label class="perfil-label-fecha-nacimiento">Fecha de Nacimiento</label>
                                <p class="valor-perfil" id="cumpleUsuario"></p>
                            </div>
                            <div class="campo-perfil">
                                <label class="perfil-label-fecha-ingreso">Fecha de Ingreso a la cooperativa</label>
                                <p class="valor-perfil" id="fechaIngresoUsuario"></p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="seccion-estadisticas">
                    <h2 class="estadisticas-titulo">Mis estadísticas</h2>
                    <div class="estadisticas-grid">
                        <div class="estadistica-item">
                            <i class="material-icons">punch_clock</i>
                            <div>
                                <h3 class="estadistica-total-horas-titulo">Total Horas Trabajadas</h3>
                                <p id="horasTotales">—</p>
                            </div>
                        </div>
                        <div class="estadistica-item">
                            <i class="material-icons">payments</i>
                            <div>
                                <h3 class="estadistica-monto-aportado-titulo">Monto Aportado</h3>
                                <p id="pagosTotales">—</p>
                            </div>
                        </div>
                        <div class="estadistica-item">
                            <i class="material-icons">event</i>
                            <div>
                                <h3 class="estadistica-antiguedad-titulo">Antigüedad</h3>
                                <p id="antiguedadUsuario">—</p>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </main>
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

        // Ajustar el menú al cambiar el tamaño de la ventana
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                botonHamburguesa.classList.remove('activo');
                sidebar.classList.remove('activo');
                overlay.classList.remove('activo');
                document.body.style.overflow = 'auto';
            }
        });

        // Funcionalidad para submenús
        document.querySelectorAll(".item-menu > a").forEach(boton => {
            boton.addEventListener("click", function (e) {
                // Evita que redireccione si tiene submenu
                if (this.nextElementSibling && this.nextElementSibling.classList.contains("submenu")) {
                    e.preventDefault();
                    this.parentElement.classList.toggle("open");
                }
            });
        });

        // Funcionalidad para cambiar entre vista y edición de datos
        document.querySelector('.boton-cambiar-datos').addEventListener('click', function () {
            const formulario = document.getElementById('formulario-editar-datos');
            const infoSoloLectura = document.getElementById('info-solo-lectura');

            if (formulario.style.display === 'none') {
                formulario.style.display = 'block';
                infoSoloLectura.style.display = 'none';
                this.innerHTML = '<i class="material-icons">visibility</i> Ver datos personales';
            } else {
                formulario.style.display = 'none';
                infoSoloLectura.style.display = 'block';
                this.innerHTML = '<i class="material-icons">edit</i> Cambiar datos personales';
            }
        });

        // Funcionalidad para cancelar edición
        document.querySelector('.boton-cancelar').addEventListener('click', function () {
            document.getElementById('formulario-editar-datos').style.display = 'none';
            document.getElementById('info-solo-lectura').style.display = 'block';
            document.querySelector('.boton-cambiar-datos').innerHTML = '<i class="material-icons">edit</i> Cambiar datos personales';
        });
    </script>
    <script src="../Javascript/FrontUsuario/usuario.js" type="module"></script>
    <script src="../Javascript/FrontUsuario/generalidades.js" type="module"></script>
    <script src="../Javascript/FrontUsuario/configuracion_idioma.js" type="module"></script>
</body>

</html>
