<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senda Firme - Solicitudes</title>
    <link rel="stylesheet" href="../Css/estilosSolicitudes.css">
    <link rel="stylesheet" href="../Css/backoffice.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=event_available" />
</head>

<body class="backoffice">
    <button class="hamburger-btn">
        <span class="material-icons">menu</span>
    </button>
    <div class="contenedor-dashboard">
        <!-- Sidebar -->
        <aside class="sidebar">
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
                    <li class="item-menu">
                        <a href="socios.php"><i class="material-icons">people</i> Socios</a>
                    </li>
                    <li class="item-menu">
                        <a href="#">
                            <i class="material-icons">apartment</i> Unidades Habitacionales
                        </a>
                        <ul class="submenu">
                            <a href="unidades.php"><i class="material-icons">home_work</i>Gestionar Proyectos</a>
                            <a href="crearUnidad.php"><i class="material-icons">add_circle</i> Crear Unidad</a>
                        </ul>
                    </li>
                    <li class="item-menu">
                        <a href="#">
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
                        <a href="#">
                            <i class="material-icons">settings</i> Configuracion
                        </a>
                        <ul class="submenu">
                            <a href="configuracion.php"><i class="material-icons">star</i> Mi Perfil</a>
                            <a href="crearAdmin.php"><i class="material-icons">key</i> Crear Admin</a>
                            <a href="borrarAdmin.php"><i class="material-icons">backspace</i> Borrar Admin</a>

                        </ul>
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

        <!-- Contenido de solicitudes pendientes -->
        <main class="contenido-principal">
            <h1>Solicitudes Pendientes</h1>
            <div id="contenedor-solicitudes">
            </div>
            <div id="solicitudesIcon">
                <i class="material-icons">person_search</i>
                <h1>No hay solicitudes pendientes</h1>
            </div>
        </main>
    </div>



    <!-- Modal de confirmación -->
    <div class="modal-confirmacion" id="modalConfirmacion">
        <div class="modal-contenido">
            <h3>Confirmar Rechazo</h3>
            <p>¿Estás seguro que deseas rechazar esta solicitud?</p>
            <p>Esta acción no se puede deshacer.</p>
            <div class="modal-acciones">
                <button class="btn-cancelar">
                    <i class="material-icons">arrow_back</i> Cancelar
                </button>
                <button class="btn-confirmar-rechazo">
                    <i class="material-icons">delete_forever</i> Confirmar Rechazo
                </button>
            </div>
        </div>
    </div>

    <div class="modal-confirmacion-aprobar" id="modalConfirmacionAprobar">
        <div class="modal-contenido">
            <h3>
                <i class="material-icons">check_circle</i>
                Confirmar Aprobación
            </h3>
            <p>¿Estás seguro que deseas aprobar esta solicitud?</p>
            <p>Seleccione una unidad habitacional:</p>

            <div class="select-container">
                <label class="select-label">Unidad Habitacional</label>
                <select class="custom-select" id="selectUnidad">
                    <option value="">Seleccione una unidad</option>
                    <option value="1" data-habitaciones="2" data-baños="1">Unidad 1 - Avenida Principal
                        123</option>
                    <option value="2" data-habitaciones="3" data-baños="2">Unidad 2 - Calle Secundaria
                        456</option>
                    <option value="3" data-habitaciones="3" data-baños="2">Unidad 3 - Boulevard Central
                        789</option>
                    <option value="4" data-habitaciones="4" data-baños="2">Unidad 4 - Avenida Norte 101
                    </option>
                    <option value="5" data-habitaciones="2" data-baños="1">Unidad 5 - Calle Sur 202
                    </option>
                </select>
                <span class="select-arrow material-icons">arrow_drop_down</span>
            </div>

            <div class="modal-acciones">
                <button class="btn-cancelar">
                    <i class="material-icons">arrow_back</i> Cancelar
                </button>
                <button class="btn-confirmar-aprobar" id="btnConfirmarAprobar" disabled>
                    <i class="material-icons">check_circle</i> Confirmar Aprobación
                </button>
            </div>
        </div>
    </div>


    <!-- Modal de Asignar Monto -->
    <div class="modal-monto" id="modalPagoInicial">
        <div class="modal-contenido">
            <h3>Confirmar Monto</h3>
            <p>¿Cuánto querés asignar de pago inicial?</p>
            <input type="number" id="inputPagoInicial" placeholder="Monto en $">
            <div class="modal-acciones">
                <button class="btn-cancelar-pago">
                    <i class="material-icons">arrow_back</i> Cancelar
                </button>
                <button class="btn-confirmar-pago">
                    <i class="material-icons">check_circle</i> Confirmar Monto
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Asignar Unidad Habitacional -->
    <div class="modal-unidad" id="modalAsignarUnidad">
        <div class="modal-contenido">
            <h3>
            <i class="material-icons">apartment</i>
            Asignar Unidad Habitacional
            </h3>
            <p>Seleccione una unidad para asignar al interesado:</p>

            <div class="select-container">
            <label class="select-label">Unidad Habitacional</label>
            <select class="custom-select" id="selectUnidadHabitacional"></select>
            <span class="select-arrow material-icons">arrow_drop_down</span>
            </div>

            <!-- NUEVO: detalle de la unidad seleccionada -->
            <p id="infoUnidad" style="margin-top:10px; font-weight:500;">—</p>

            <div class="modal-acciones">
            <button class="btn-cancelar-unidad">
                <i class="material-icons">arrow_back</i> Cancelar
            </button>
            <!-- CAMBIO DE CLASE para no chocar -->
            <button class="btn-asignar-unidad-personalizada" onclick="window.location.href='crearUnidad.php'">
                <i class="material-icons">balcony</i> Asignar Unidad Personalizada
            </button>
            <button class="btn-confirmar-unidad" id="btnConfirmarUnidad" disabled>
                <i class="material-icons">check_circle</i> Asignar Unidad
            </button>
            </div>
        </div>
    </div>


    <script src="../Javascript/BackOffice/generalidades.js" type="module"></script>
    <script src="../Javascript/BackOffice/solicitudes.js" type="module"></script>
</body>

</html>