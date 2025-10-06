<?php
require_once '../verificarSesion.php';
verificarAcceso(['Admin']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senda Firme - Reuniones</title>
    <link rel="stylesheet" href="../Css/backoffice.css">
    <link rel="stylesheet" href="../Css/estilosReuniones.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
                    <li class="item-menu activo">
                        <a href="reuniones.php"><i class="material-icons">event</i> Reuniones</a>
                    </li>
                    <li class="item-menu">
                        <a href="#"><i class="material-icons">people</i> Socios</a>
                    </li>
                    <li class="item-menu">
                        <a href="#">
                            <i class="material-icons">apartment</i> Proyectos
                        </a>
                        <ul class="submenu">
                            <a href="proyectos.php"><i class="material-icons">home_work</i>Gestionar Proyectos</a>
                            <a href="crearUnidad.php"><i class="material-icons">add_circle</i> Crear Unidad</a>
                            <a href="borrarUnidad.php"><i class="material-icons">delete</i> Borrar Unidad</a>
                            <a href="modificarUnidad.php"><i class="material-icons">edit</i> Modificar Unidad</a>
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

        <!-- Contenido principal -->
        <main class="contenido-principal">
            <header class="header-principal">
                <h1>Gestión de Reuniones</h1>
                <p>Organiza y gestiona las reuniones de la cooperativa</p>
            </header>

            <div class="controles-reuniones">
                <button class="btn-crear-reunion" id="btnCrearReunion">
                    <i class="material-icons">add</i> Crear Nueva Reunión
                </button>
                <div class="filtros">
                    <select id="filtroEstado">
                        <option value="todas">Todas las reuniones</option>
                        <option value="pendientes">Pendientes</option>
                        <option value="completadas">Completadas</option>
                        <option value="canceladas">Canceladas</option>
                    </select>
                    <input type="date" id="filtroFecha">
                </div>
            </div>

            <div class="contenedor-reuniones">
                <!-- Reuniones Pendientes -->
                <section class="seccion-reuniones">
                    <h2><i class="material-icons">schedule</i> Reuniones Pendientes</h2>
                    <div class="lista-reuniones" id="reunionesPendientes">
                        <!-- Las reuniones se cargarán dinámicamente -->
                    </div>
                </section>

                <!-- Historial de Reuniones -->
                <section class="seccion-reuniones">
                    <h2><i class="material-icons">history</i> Historial de Reuniones</h2>
                    <div class="lista-reuniones" id="historialReuniones">
                        <!-- El historial se cargará dinámicamente -->
                    </div>
                </section>
            </div>
        </main>
    </div>

    <!-- Modal para crear/editar reunión -->
    <div class="modal-reunion" id="modalReunion">
        <div class="modal-contenido">
            <h3 id="tituloModal">
                <i class="material-icons">event</i>
                Crear Nueva Reunión
            </h3>

            <form id="formReunion">
                <div class="campo-formulario">
                    <label for="tituloReunion">Título de la reunión</label>
                    <input type="text" id="tituloReunion" required placeholder="Ej: Reunión General Mensual">
                </div>

                <div class="campo-formulario">
                    <label for="descripcionReunion">Descripción</label>
                    <textarea id="descripcionReunion" rows="3" placeholder="Descripción de la reunión..."></textarea>
                </div>

                <div class="campos-doble">
                    <div class="campo-formulario">
                        <label for="fechaReunion">Fecha</label>
                        <input type="date" id="fechaReunion" required>
                    </div>
                    <div class="campo-formulario">
                        <label for="horaReunion">Hora</label>
                        <input type="time" id="horaReunion" required>
                    </div>
                </div>

                <div class="campo-formulario">
                    <label for="lugarReunion">Lugar</label>
                    <input type="text" id="lugarReunion" required placeholder="Ej: Auditorio Principal">
                </div>

                <div class="campo-formulario">
                    <label for="tipoReunion">Tipo de reunión</label>
                    <select id="tipoReunion">
                        <option value="general">General</option>
                        <option value="comision">Comisión</option>
                        <option value="emergencia">Emergencia</option>
                        <option value="planificacion">Planificación</option>
                    </select>
                </div>

                <div class="modal-acciones">
                    <button type="button" class="btn-cancelar" id="btnCancelarReunion">
                        <i class="material-icons">close</i> Cancelar
                    </button>
                    <button type="submit" class="btn-confirmar">
                        <i class="material-icons">check</i> Guardar Reunión
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para ver detalles de reunión -->
    <div class="modal-reunion" id="modalDetallesReunion">
        <div class="modal-contenido">
            <h3>
                <i class="material-icons">info</i>
                Detalles de la Reunión
            </h3>

            <div class="detalles-reunion">
                <div class="detalle-item">
                    <strong>Título:</strong>
                    <span id="detalleTitulo"></span>
                </div>
                <div class="detalle-item">
                    <strong>Descripción:</strong>
                    <span id="detalleDescripcion"></span>
                </div>
                <div class="detalle-item">
                    <strong>Fecha y Hora:</strong>
                    <span id="detalleFechaHora"></span>
                </div>
                <div class="detalle-item">
                    <strong>Lugar:</strong>
                    <span id="detalleLugar"></span>
                </div>
                <div class="detalle-item">
                    <strong>Tipo:</strong>
                    <span id="detalleTipo"></span>
                </div>
                <div class="detalle-item">
                    <strong>Estado:</strong>
                    <span id="detalleEstado"></span>
                </div>
                <div class="detalle-item">
                    <strong>Asistentes:</strong>
                    <span id="detalleAsistentes"></span>
                </div>
            </div>

            <div class="modal-acciones">
                <button type="button" class="btn-cerrar" id="btnCerrarDetalles">
                    <i class="material-icons">close</i> Cerrar
                </button>
                <button type="button" class="btn-editar" id="btnEditarReunion">
                    <i class="material-icons">edit</i> Editar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal para confirmar asistencia -->
    <div class="modal-reunion modal-asistencia" id="modalAsistencia">
        <div class="modal-contenido">
            <div class="encabezado-asistencia">
                <h3>
                    <i class="material-icons">groups</i>
                    Confirmar Asistencia
                </h3>
                <div class="contador-asistencia" id="contadorAsistencia">
                    Socio 1 de 10
                </div>
            </div>

            <div class="indicador-accion indicador-falta" id="indicadorFalta">FALTA</div>
            <div class="indicador-accion indicador-asistencia" id="indicadorAsistencia">ASISTIÓ</div>

            <div class="tarjeta-socio" id="tarjetaSocio">
                <img src="" alt="Foto del socio" class="foto-socio" id="fotoSocio">
                <div class="nombre-socio" id="nombreSocio">Nombre del Socio</div>
                <div class="info-socio" id="infoSocio"><p>CI: 57051830 <br> Unidad Habitacional: 5 </p></div>
            </div>

            <div class="controles-asistencia">
                <button class="btn-asistencia btn-no-asistio" id="btnNoAsistio" title="Marcar como falta">
                    <i class="material-icons">close</i>
                </button>
                <button class="btn-asistencia btn-si-asistio" id="btnSiAsistio" title="Marcar como asistió">
                    <i class="material-icons">check</i>
                </button>
            </div>

            <div class="resumen-asistencia" id="resumenAsistencia" style="display: none;">
                <h4>Resumen de Asistencia</h4>
                <div class="estadisticas-asistencia">
                    <div class="estadistica asistieron">
                        <span class="numero-estadistica" id="totalAsistieron">0</span>
                        <span class="texto-estadistica">Asistieron</span>
                    </div>
                    <div class="estadistica no-asistieron">
                        <span class="numero-estadistica" id="totalNoAsistieron">0</span>
                        <span class="texto-estadistica">No Asistieron</span>
                    </div>
                </div>
                <div class="modal-acciones">
                    <button type="button" class="btn-cancelar" id="btnCancelarAsistencia">
                        <i class="material-icons">close</i> Cancelar
                    </button>
                    <button type="button" class="btn-confirmar" id="btnGuardarAsistencia">
                        <i class="material-icons">save</i> Guardar Asistencia
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="../Javascript/BackOffice/generalidades.js" type="module"></script>
    <script src="../Javascript/BackOffice/reuniones.js" type="module"></script>
</body>
</html>