<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senda Firme - Perfil</title>
    <link rel="stylesheet" href="../Css/backoffice.css">
    <link rel="stylesheet" href="../Css/estilosSocios.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="backoffice">
    <button class="boton-hamburguesa" id="botonHamburguesa">
        <span></span>
    </button>
    <div class="overlay" id="overlay"></div>

    <div class="contenedor-dashboard">
        <aside class="sidebar" id="sidebar">
            <div class="logo-dashboard">
                <img src="../../Fotos/logoBack.webp" alt="Logo Cooperativa">
                <span>Senda Firme</span>
                <p>Construyendo oportunidades juntos</p>
            </div>

            <nav id="NavegacionDashboard">
                <ul class="menu-dashboard">
                    <li class="item-menu">
                        <a href="index.php"><i class="material-icons">home</i> Inicio</a>
                    </li>
                    <li class="item-menu">
                        <a href="reuniones.php"><i class="material-icons">event</i> Reuniones</a>
                    </li>
                    <li class="item-menu activo">
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

        <!-- Contenido principal -->
        <main class="contenido-principal">
            <header class="header-principal">
                <h1>Socios</h1>
                <p>En esta pestaña puedes consultar todos los socios registrados en la cooperativa.</p>
            </header>

            <!-- Tarjetas de socios -->
            <div class="contenedor-socios">
                <div class="etiqueta">
                    <div class="card">
                        <div class="card-header">
                            <img src="avatar.jpg" alt="Usuario" class="avatar">
                            <div class="info">
                                <h3>Nombre Apellido</h3>
                                <p>Pasillo P-1 <br> Puerta 100</p>
                            </div>
                        </div>
                        <div class="card-footer">
                            <span class="tag gray">0 Horas Trabajadas Totales</span>
                            <span class="tag red">0/21</span>
                            <span class="tag green">$ 0</span>
                        </div>
                    </div>
                    <div class="actions">
                        <button>
                            <i class="material-icons" style="font-size: 40px;">visibility</i>
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal usuario -->
    <div id="modalUsuario" class="modal">
        <div class="modal-contenido">
            <div class="modal-header">
                <h2>Detalles del Socio</h2>
                <span class="cerrar-modal">&times;</span>
            </div>
            <div class="modal-body">

                <div class="perfil-completo">
                    <div class="foto-perfil-modal">
                        <img id="modalAvatar" src="avatar.jpg" alt="Foto del usuario">
                    </div>
                    <div class="info-completa">
                        <div class="campo">
                            <label>Nombre:</label>
                            <span id="modalNombre">Nombre</span>
                        </div>
                        <div class="campo">
                            <label>Apellido:</label>
                            <span id="modalApellido">Apellido</span>
                        </div>
                        <div class="campo">
                            <label>Cédula:</label>
                            <span id="modalCedula">5 705 183-0</span>
                        </div>
                        <div class="campo">
                            <label>Fecha de nacimiento:</label>
                            <span id="modalFechaNacimiento">15/03/1985</span>
                        </div>
                        <div class="campo">
                            <label>Dirección:</label>
                            <span id="modalDireccion">Pasillo P-1, Puerta 100</span>
                        </div>
                        <div class="campo">
                            <label>Email:</label>
                            <span id="modalEmail">usuario@ejemplo.com</span>
                        </div>
                        <div class="campo">
                            <label>Teléfono:</label>
                            <span id="modalTelefono">+34 123 456 789</span>
                        </div>
                        <div class="campo">
                            <label>Fecha de registro:</label>
                            <span id="modalFechaRegistro">01/01/2023</span>
                        </div>
                    </div>
                </div>

                <div class="historial">
                    <h3>Integrantes Familiares</h3>
                    <div class="tabla-contenedor">
                        <table class="tabla-pagos">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Cédula</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody id="tablaFamiliares"></tbody>
                        </table>
                    </div>
                </div>

                <div class="estadisticas">
                    <h3>Estadísticas</h3>
                    <div class="estadisticas-grid">
                        <div class="estadistica-item">
                            <span class="estadistica-valor" id="modalHorasTotales">0</span>
                            <span class="estadistica-etiqueta">Horas trabajadas totales</span>
                        </div>
                        <div class="estadistica-item">
                            <span class="estadistica-valor" id="modalHorasActual">0/21</span>
                            <span class="estadistica-etiqueta">Horas este mes</span>
                        </div>
                        <div class="estadistica-item">
                            <span class="estadistica-valor" id="modalSaldo">$ 0</span>
                            <span class="estadistica-etiqueta">Saldo actual</span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secundario cerrar-modal" id="btnCerrarModal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../Javascript/BackOffice/generalidades.js" type="module"></script>
    <script src="../Javascript/BackOffice/sociosOperador.js" type="module"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
