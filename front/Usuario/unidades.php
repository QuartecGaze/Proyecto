<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senda Firme - Unidad Habitacional</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../Css/estilosUnidadUsuario.css">
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
                    <li class="item-menu activo">
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
                    <li class="item-menu">
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

        <main class="contenido-principal">
            <header class="header-principal">
                <h1 class="unidad-titulo">Unidad Habitacional</h1>
                <p class="unidad-subtitulo">Gestiona los datos de tu vivienda y los integrantes de tu familia</p>
            </header>

            <!-- Información de la Unidad -->
            <section class="seccion-unidad">
                <h2 class="unidad-info-titulo">Información de la Unidad</h2>

                <div class="tarjeta-unidad">
                    <div class="info-unidad">
                        <div class="campo-unidad">
                            <span class="etiqueta unidad-numero-label">Número de Unidad:</span>
                            <span class="valor" id="numeroUnidad">-</span>
                        </div>
                        <div class="campo-unidad">
                            <span class="etiqueta unidad-pasillo-label">Pasillo:</span>
                            <span class="valor" id="pasilloUnidad">-</span>
                        </div>
                        <div class="campo-unidad">
                            <span class="etiqueta unidad-estado-label">Estado:</span>
                            <span class="valor estado" id="estadoUnidad">-</span>
                        </div>
                        <div class="campo-unidad">
                            <span class="etiqueta unidad-habitaciones-label">Habitaciones:</span>
                            <span class="valor" id="habitacionesUnidad">-</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Integrantes Familiares -->
            <section class="seccion-integrantes">
                <div class="cabecera-integrantes">
                    <h2 class="integrantes-titulo">Integrantes Familiares</h2>
                    <button class="boton-primario" id="btnAgregarIntegrante">
                        <i class="material-icons">person_add</i>
                        <span class="integrantes-boton-agregar">Agregar Integrante</span>
                    </button>
                </div>

                <div class="tarjeta-integrantes">
                    <div class="tabla-contenedor">
                        <table class="tabla-integrantes">
                            <thead>
                                <tr>
                                    <th class="integrantes-tabla-nombre">Nombre</th>
                                    <th class="integrantes-tabla-apellido">Apellido</th>
                                    <th class="integrantes-tabla-ci">CI</th>
                                    <th class="integrantes-tabla-mail">Mail</th>
                                    <th class="integrantes-tabla-fecha-nacimiento">Fecha de Nacimiento</th>
                                    <th class="integrantes-tabla-genero">Genero</th>
                                    <th class="integrantes-tabla-acciones">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="lista-integrantes">
                                <!-- Los integrantes se cargarán con JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="sin-integrantes" id="sin-integrantes" style="display: none;">
                        <div class="mensaje-vacio">
                            <i class="material-icons">group</i>
                            <h3 class="integrantes-vacio-titulo">No hay integrantes familiares registrados</h3>
                            <p class="integrantes-vacio-texto">
                                Agrega los integrantes de tu familia para gestionarlos desde aquí
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Modal para agregar/editar integrante -->
    <div id="modalIntegrante" class="modal">
        <div class="modal-contenido">
            <div class="modal-header">
                <h2 id="tituloModalIntegrante" class="modal-integrante-titulo-agregar">
                    Agregar Integrante Familiar
                </h2>
                <button class="modal-cerrar" id="cerrarModalIntegrante">
                    <i class="material-icons">close</i>
                </button>
            </div>

            <div class="modal-body">
                <form id="formIntegrante" class="formulario-modal">
                    <div class="mensaje-exito modal-integrante-mensaje-exito" style="display: none;">
                        Integrante guardado correctamente.
                    </div>
                    <div class="mensaje-error modal-integrante-mensaje-error" style="display: none;">
                        Error al guardar el integrante.
                    </div>

                    <div class="grupo-formulario">
                        <label for="nombreIntegrante" class="modal-integrante-label-nombre">Nombre:</label>
                        <input type="text" id="nombreIntegrante" name="nombre" required>
                    </div>

                    <div class="grupo-formulario">
                        <label for="apellidoIntegrante" class="modal-integrante-label-apellido">Apellido:</label>
                        <input type="text" id="apellidoIntegrante" name="apellido" required>
                    </div>

                    <div class="grupo-formulario">
                        <label for="dniIntegrante" class="modal-integrante-label-ci">Cedula:</label>
                        <input type="text" id="dniIntegrante" name="dni" required pattern="[0-9]{8}">
                    </div>

                    <div class="grupo-formulario">
                        <label for="emailIntegrante" class="modal-integrante-label-email">Email:</label>
                        <input type="email" id="emailIntegrante" name="email" required >
                    </div>

                    <div class="grupo-formulario">
                        <label for="fechaNacimientoIntegrante" class="modal-integrante-label-fecha-nacimiento">
                            Fecha de Nacimiento:
                        </label>
                        <input type="date" id="fechaNacimientoIntegrante" name="fechaNacimiento" required>
                    </div>

                    <select id="generoIntegrante" required>
                        <option value="" class="modal-integrante-select-placeholder">Seleccioná…</option>
                        <option class="modal-integrante-select-masculino">Masculino</option>
                        <option class="modal-integrante-select-femenino">Femenino</option>
                    </select>

                    <div class="modal-acciones">
                        <button type="button" class="boton-secundario modal-boton-cancelar" id="cancelarIntegrante">
                            Cancelar
                        </button>
                        <button type="submit" class="boton-primario modal-boton-guardar" id="guardarIntegrante">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para eliminar -->
    <div id="modalConfirmacion" class="modal">
        <div class="modal-contenido modal-pequeno">
            <div class="modal-header">
                <h2 class="modal-confirmacion-titulo">Confirmar Eliminación</h2>
                <button class="modal-cerrar" id="cerrarModalConfirmacion">
                    <i class="material-icons">close</i>
                </button>
            </div>

            <div class="modal-body">
                <p id="mensajeConfirmacion" class="modal-confirmacion-texto">
                    ¿Estás seguro de que deseas eliminar este integrante familiar?
                </p>
                <div class="modal-acciones">
                    <button type="button" class="boton-secundario modal-boton-cancelar" id="cancelarEliminacion">
                        Cancelar
                    </button>
                    <button type="button" class="boton-primario boton-peligro modal-boton-eliminar" id="confirmarEliminacion">
                        Eliminar
                    </button>
                </div>
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

    <script src="../Javascript/FrontUsuario/unidad.js" type="module"></script>
    <script src="../Javascript/FrontUsuario/generalidades.js" type="module"></script>
    <script src="../Javascript/FrontUsuario/unidad_idioma.js" type="module"></script>
</body>

</html>
