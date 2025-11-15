<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senda Firme - Pagos Pendientes</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../Css/estilosPagosUsuario.css">
</head>

<body>
    <!-- Botón hamburguesa para móviles -->
    <button class="boton-hamburguesa" id="botonHamburguesa">
        <span></span>
    </button>

    <!-- Overlay para móviles -->
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
                <h1 class="pagos-titulo">Pagos Pendientes</h1>
                <p class="pagos-subtitulo">Consulta y gestiona tus pagos pendientes con la cooperativa</p>
            </header>

            <div class="contenedor-tarjetas">
                <div class="tarjeta-dashboard" id="cardPagosAtrasados">
                    <div class="tarjeta-icono" id="iconPagosAtrasados">
                        <i class="material-icons">warning</i>
                    </div>
                    <div class="tarjeta-contenido">
                        <h3 class="card-pagos-pendientes-titulo">Pagos Pendientes</h3>
                        <p class="tarjeta-valor" id="pagosAtrasadosCantidad"></p>
                        <p class="tarjeta-subtexto card-pagos-pendientes-subtexto">Cantidad total</p>
                    </div>
                </div>

                <div class="tarjeta-dashboard">
                    <div class="tarjeta-icono">
                        <i class="material-icons">attach_money</i>
                    </div>
                    <div class="tarjeta-contenido">
                        <h3 class="card-monto-total-titulo">Monto Total</h3>
                        <p class="tarjeta-valor" id="pagosAtrasadosTotal"></p>
                        <p class="tarjeta-subtexto card-monto-total-subtexto">Por pagar</p>
                    </div>
                </div>

                <div class="tarjeta-dashboard">
                    <div class="tarjeta-icono">
                        <i class="material-icons">event</i>
                    </div>
                    <div class="tarjeta-contenido">
                        <h3 class="card-ultimo-pago-titulo">Ultimo Pago</h3>
                        <p class="tarjeta-valor" id="pagoMensual"></p>
                        <p class="tarjeta-subtexto card-ultimo-pago-subtexto">Monto del ultimo pago a realizar</p>
                    </div>
                </div>
            </div>

            <section class="seccion-pagos">
                <div class="mensaje-exito pagos-mensaje-exito" style="display: none;">
                    Pago general registrado correctamente, a la espera de
                    la aprobacion de un administrador
                </div>
                <h2 class="seccion-detalle-pagos-titulo">Detalle de Pagos Atrasados</h2>

                <div class="filtros-pagos">
                    <div class="grupo-filtro">
                        <label for="filtro-estado" class="filtros-estado-label">Estado:</label>
                        <select name="filtro-estado" id="filtro-estado">
                            <option value="todos" class="filtros-estado-opcion-todos">Todos</option>
                            <option value="pendiente" class="filtros-estado-opcion-pendiente">Pendiente</option>
                            <option value="enespera" class="filtros-estado-opcion-en-espera">En Espera</option>
                        </select>
                    </div>

                    <div class="grupo-filtro">
                        <label for="filtro-mes" class="filtros-tipo-label">Tipo de Pago</label>
                        <select name="filtro-mes" id="filtro-mes">
                            <option value="Todos" class="filtros-tipo-opcion-todos">Todos</option>
                            <option value="mensual" class="filtros-tipo-opcion-mensual" selected>Mensual</option>
                            <option value="otros" class="filtros-tipo-opcion-otros">Otros</option>
                        </select>
                    </div>

                    <button class="boton-primario">
                        <i class="material-icons">filter_list</i>
                        <span class="filtros-boton-aplicar">Aplicar Filtros</span>
                    </button>

                    <button class="boton-primario btn-pago">
                        <i class="material-icons">payment</i>
                        <span class="filtros-boton-realizar-pago">Realizar Pago</span>
                    </button>
                </div>

                <div class="tabla-contenedor">
                    <table class="tabla-pagos">
                        <thead>
                            <tr>
                                <th class="tabla-pagos-columna-concepto">Concepto</th>
                                <th class="tabla-pagos-columna-monto">Monto</th>
                                <th class="tabla-pagos-columna-fecha">Fecha del Pago</th>
                                <th class="tabla-pagos-columna-estado">Estado</th>
                                <th class="tabla-pagos-columna-acciones">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <!-- Modal para realizar pagos -->
    <div id="modal-pago" class="modal">
        <div class="modal-contenido">
            <div class="modal-header">
                <h2 class="modal-pago-titulo">Realizar Pago</h2>
                <button class="cerrar-modal">&times;</button>
            </div>

            <div class="modal-body">
                <form id="formularioPago">
                    <div class="grupo-formulario">
                        <label for="seleccionar-pago" class="modal-pago-label-seleccionar">
                            Seleccionar pago a realizar:
                        </label>
                        <select id="seleccionar-pago" name="seleccionar-pago" required>
                            <option value="" class="modal-pago-opcion-placeholder">Seleccione un pago</option>
                        </select>
                    </div>

                    <div class="info-pago-seleccionado" id="info-pago">
                        <h3 class="modal-pago-detalles-titulo">Detalles del pago seleccionado</h3>
                        <div class="detalles-pago">
                            <p>
                                <strong class="modal-pago-label-concepto">Concepto:</strong>
                                <span id="detalle-concepto">-</span>
                            </p>
                            <p>
                                <strong class="modal-pago-label-monto">Monto:</strong>
                                <span id="detalle-monto">-</span>
                            </p>
                            <p>
                                <strong class="modal-pago-label-vencimiento">Fecha de vencimiento:</strong>
                                <span id="detalle-vencimiento">-</span>
                            </p>
                        </div>
                    </div>

                    <div class="grupo-formulario">
                        <label for="comprobante-pago" class="modal-pago-label-comprobante">
                            Comprobante de pago:
                        </label>
                        <div class="carga-archivo">
                            <input type="file" id="comprobante-pago" name="comprobante-pago"
                                accept=".pdf,.jpg,.jpeg,.png">
                            <label for="comprobante-pago" class="boton-carga-archivo">
                                <i class="material-icons">cloud_upload</i>
                                <span class="modal-pago-boton-archivo">Seleccionar archivo</span>
                            </label>
                            <span class="nombre-archivo modal-pago-archivo-placeholder" id="nombre-archivo">
                                Ningún archivo seleccionado
                            </span>
                        </div>
                        <p class="texto-ayuda modal-pago-texto-ayuda">
                            Formatos aceptados: PDF, JPG, PNG (Tamaño máximo: 5MB)
                        </p>
                    </div>

                    <div class="modal-acciones">
                        <button type="button" class="boton-secundario cerrar-modal modal-boton-cancelar">
                            Cancelar
                        </button>
                        <button type="submit" class="boton-primario modal-boton-confirmar" id="confirmar-pago">
                            Confirmar Pago
                        </button>
                    </div>
                </form>
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

        // Funcionalidad para submenús
        document.querySelectorAll(".item-menu > a").forEach(boton => {
            boton.addEventListener("click", function (e) {
                if (this.nextElementSibling && this.nextElementSibling.classList.contains("submenu")) {
                    e.preventDefault();
                    this.parentElement.classList.toggle("open");
                }
            });
        });

        // Funcionalidad del modal de pago
        document.querySelectorAll('.btn-pago').forEach(boton => {
            boton.addEventListener('click', function () {
                console.log('Abriendo modal de pago');
                document.getElementById('modal-pago').style.display = 'flex';
            });
        });

        document.querySelectorAll('.cerrar-modal').forEach(boton => {
            boton.addEventListener('click', function () {
                console.log('Cerrando modal de pago');
                document.getElementById('modal-pago').style.display = 'none';
            });
        });

        // Funcionalidad para mostrar nombre del archivo seleccionado
        document.getElementById('comprobante-pago').addEventListener('change', function () {
            const nombreArchivo = this.files[0] ? this.files[0].name : 'Ningún archivo seleccionado';
            document.getElementById('nombre-archivo').textContent = nombreArchivo;
        });
    </script>
    <script src="../Javascript/FrontUsuario/pagos.js" type="module"></script>
    <script src="../Javascript/FrontUsuario/generalidades.js" type="module"></script>
    <script src="../Javascript/FrontUsuario/pagos_idioma.js" type="module"></script>
</body>

</html>
