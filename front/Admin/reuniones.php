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

    <script src="../Javascript/BackOffice/generalidades.js" type="module"></script>
    <script src="../Javascript/BackOffice/reuniones.js" type="module"></script>
    <script>
        document.querySelectorAll(".item-menu > a").forEach(boton => {
            boton.addEventListener("click", function (e) {
                if (this.nextElementSibling && this.nextElementSibling.classList.contains("submenu")) {
                    e.preventDefault();
                    this.parentElement.classList.toggle("open");
                }
            });
        });
    </script>

    <script>
        // Variables globales
let reuniones = [];
let reunionEditando = null;

// Elementos del DOM
const modalReunion = document.getElementById('modalReunion');
const modalDetalles = document.getElementById('modalDetallesReunion');
const btnCrearReunion = document.getElementById('btnCrearReunion');
const btnCancelarReunion = document.getElementById('btnCancelarReunion');
const btnCerrarDetalles = document.getElementById('btnCerrarDetalles');
const btnEditarReunion = document.getElementById('btnEditarReunion');
const formReunion = document.getElementById('formReunion');
const filtroEstado = document.getElementById('filtroEstado');
const filtroFecha = document.getElementById('filtroFecha');

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    cargarReuniones();
    configurarEventListeners();
});

function configurarEventListeners() {
    // Modal crear/editar reunión
    btnCrearReunion.addEventListener('click', abrirModalCrear);
    btnCancelarReunion.addEventListener('click', cerrarModalReunion);
    
    // Modal detalles
    btnCerrarDetalles.addEventListener('click', cerrarModalDetalles);
    btnEditarReunion.addEventListener('click', editarReunionDesdeDetalles);
    
    // Formulario
    formReunion.addEventListener('submit', guardarReunion);
    
    // Filtros
    filtroEstado.addEventListener('change', filtrarReuniones);
    filtroFecha.addEventListener('change', filtrarReuniones);
    
    // Cerrar modales al hacer click fuera
    modalReunion.addEventListener('click', function(e) {
        if (e.target === modalReunion) cerrarModalReunion();
    });
    
    modalDetalles.addEventListener('click', function(e) {
        if (e.target === modalDetalles) cerrarModalDetalles();
    });
}

// Funciones de modal
function abrirModalCrear() {
    reunionEditando = null;
    document.getElementById('tituloModal').innerHTML = '<i class="material-icons">event</i> Crear Nueva Reunión';
    formReunion.reset();
    modalReunion.style.display = 'flex';
}

function abrirModalEditar(reunion) {
    reunionEditando = reunion;
    document.getElementById('tituloModal').innerHTML = '<i class="material-icons">edit</i> Editar Reunión';
    
    // Llenar formulario con datos existentes
    document.getElementById('tituloReunion').value = reunion.titulo;
    document.getElementById('descripcionReunion').value = reunion.descripcion;
    document.getElementById('fechaReunion').value = reunion.fecha;
    document.getElementById('horaReunion').value = reunion.hora;
    document.getElementById('lugarReunion').value = reunion.lugar;
    document.getElementById('tipoReunion').value = reunion.tipo;
    
    modalReunion.style.display = 'flex';
}

function cerrarModalReunion() {
    modalReunion.style.display = 'none';
    reunionEditando = null;
    formReunion.reset();
}

function abrirModalDetalles(reunion) {
    // Llenar detalles
    document.getElementById('detalleTitulo').textContent = reunion.titulo;
    document.getElementById('detalleDescripcion').textContent = reunion.descripcion;
    document.getElementById('detalleFechaHora').textContent = `${reunion.fecha} a las ${reunion.hora}`;
    document.getElementById('detalleLugar').textContent = reunion.lugar;
    document.getElementById('detalleTipo').textContent = obtenerTextoTipo(reunion.tipo);
    document.getElementById('detalleEstado').textContent = obtenerTextoEstado(reunion.estado);
    document.getElementById('detalleAsistentes').textContent = reunion.asistentes || 'Por confirmar';
    
    // Configurar botón editar
    btnEditarReunion.onclick = () => {
        cerrarModalDetalles();
        abrirModalEditar(reunion);
    };
    
    modalDetalles.style.display = 'flex';
}

function cerrarModalDetalles() {
    modalDetalles.style.display = 'none';
}

function editarReunionDesdeDetalles() {
    cerrarModalDetalles();
    // La función de edición ya está configurada en abrirModalDetalles
}

// Funciones de CRUD
function guardarReunion(e) {
    e.preventDefault();
    
    const reunionData = {
        titulo: document.getElementById('tituloReunion').value,
        descripcion: document.getElementById('descripcionReunion').value,
        fecha: document.getElementById('fechaReunion').value,
        hora: document.getElementById('horaReunion').value,
        lugar: document.getElementById('lugarReunion').value,
        tipo: document.getElementById('tipoReunion').value,
        estado: 'pendiente',
        asistentes: 0,
        fechaCreacion: new Date().toISOString().split('T')[0]
    };
    
    if (reunionEditando) {
        // Editar reunión existente
        reunionData.id = reunionEditando.id;
        actualizarReunion(reunionData);
    } else {
        // Crear nueva reunión
        reunionData.id = Date.now().toString();
        crearReunion(reunionData);
    }
    
    cerrarModalReunion();
}

function crearReunion(reunion) {
    // Aquí iría la llamada a la API
    reuniones.push(reunion);
    mostrarReuniones();
    mostrarMensaje('Reunión creada exitosamente', 'exito');
}

function actualizarReunion(reunion) {
    // Aquí iría la llamada a la API
    const index = reuniones.findIndex(r => r.id === reunion.id);
    if (index !== -1) {
        reuniones[index] = { ...reuniones[index], ...reunion };
        mostrarReuniones();
        mostrarMensaje('Reunión actualizada exitosamente', 'exito');
    }
}

function eliminarReunion(id) {
    if (confirm('¿Estás seguro de que deseas eliminar esta reunión?')) {
        // Aquí iría la llamada a la API
        reuniones = reuniones.filter(r => r.id !== id);
        mostrarReuniones();
        mostrarMensaje('Reunión eliminada exitosamente', 'exito');
    }
}

function completarReunion(id) {
    // Aquí iría la llamada a la API
    const reunion = reuniones.find(r => r.id === id);
    if (reunion) {
        reunion.estado = 'completada';
        mostrarReuniones();
        mostrarMensaje('Reunión marcada como completada', 'exito');
    }
}

// Funciones de visualización
function mostrarReuniones() {
    const reunionesPendientes = document.getElementById('reunionesPendientes');
    const historialReuniones = document.getElementById('historialReuniones');
    
    const reunionesFiltradas = filtrarReuniones();
    const pendientes = reunionesFiltradas.filter(r => r.estado === 'pendiente');
    const historial = reunionesFiltradas.filter(r => r.estado !== 'pendiente');
    
    reunionesPendientes.innerHTML = pendientes.map(crearTarjetaReunion).join('') || 
        '<p class="sin-reuniones">No hay reuniones pendientes</p>';
    
    historialReuniones.innerHTML = historial.map(crearTarjetaReunion).join('') || 
        '<p class="sin-reuniones">No hay reuniones en el historial</p>';
}

function crearTarjetaReunion(reunion) {
    const fechaFormateada = new Date(reunion.fecha).toLocaleDateString('es-ES');
    
    return `
        <div class="tarjeta-reunion ${reunion.estado} ${reunion.tipo === 'emergencia' ? 'emergencia' : ''}" 
             onclick="abrirModalDetalles(${JSON.stringify(reunion).replace(/"/g, '&quot;')})">
            <div class="cabecera-reunion">
                <div>
                    <div class="titulo-reunion">${reunion.titulo}</div>
                    <span class="badge-estado badge-${reunion.estado}">${obtenerTextoEstado(reunion.estado)}</span>
                </div>
            </div>
            
            <div class="info-reunion">
                <div class="info-item">
                    <i class="material-icons">event</i>
                    <span>${fechaFormateada}</span>
                </div>
                <div class="info-item">
                    <i class="material-icons">schedule</i>
                    <span>${reunion.hora}</span>
                </div>
                <div class="info-item">
                    <i class="material-icons">place</i>
                    <span>${reunion.lugar}</span>
                </div>
                <div class="info-item">
                    <i class="material-icons">group</i>
                    <span>${reunion.asistentes} asistentes</span>
                </div>
            </div>
            
            <div class="descripcion-reunion">${reunion.descripcion}</div>
            
            <div class="acciones-reunion">
                ${reunion.estado === 'pendiente' ? `
                    <button class="btn-accion btn-editar" onclick="event.stopPropagation(); abrirModalEditar(${JSON.stringify(reunion).replace(/"/g, '&quot;')})">
                        <i class="material-icons">edit</i> Editar
                    </button>
                    <button class="btn-accion btn-completar" onclick="event.stopPropagation(); completarReunion('${reunion.id}')">
                        <i class="material-icons">check</i> Completar
                    </button>
                    <button class="btn-accion btn-eliminar" onclick="event.stopPropagation(); eliminarReunion('${reunion.id}')">
                        <i class="material-icons">delete</i> Eliminar
                    </button>
                ` : ''}
            </div>
        </div>
    `;
}

// Funciones de utilidad
function filtrarReuniones() {
    let reunionesFiltradas = [...reuniones];
    
    // Filtrar por estado
    if (filtroEstado.value !== 'todas') {
        reunionesFiltradas = reunionesFiltradas.filter(r => r.estado === filtroEstado.value);
    }
    
    // Filtrar por fecha
    if (filtroFecha.value) {
        reunionesFiltradas = reunionesFiltradas.filter(r => r.fecha === filtroFecha.value);
    }
    
    return reunionesFiltradas;
}

function obtenerTextoEstado(estado) {
    const estados = {
        'pendiente': 'Pendiente',
        'completada': 'Completada',
        'cancelada': 'Cancelada'
    };
    return estados[estado] || estado;
}

function obtenerTextoTipo(tipo) {
    const tipos = {
        'general': 'General',
        'comision': 'Comisión',
        'emergencia': 'Emergencia',
        'planificacion': 'Planificación'
    };
    return tipos[tipo] || tipo;
}

function mostrarMensaje(mensaje, tipo) {
    // Aquí podrías implementar un sistema de notificaciones
    alert(mensaje);
}

function cargarReuniones() {
    // Datos de ejemplo - reemplazar con llamada a la API
    reuniones = [
        {
            id: '1',
            titulo: 'Reunión General Mensual',
            descripcion: 'Reunión ordinaria para revisar los avances del mes.',
            fecha: '2024-01-15',
            hora: '18:00',
            lugar: 'Auditorio Principal',
            tipo: 'general',
            estado: 'pendiente',
            asistentes: 45,
            fechaCreacion: '2024-01-01'
        },
        {
            id: '2',
            titulo: 'Comisión de Proyectos',
            descripcion: 'Revisión del progreso de los proyectos en curso.',
            fecha: '2024-01-10',
            hora: '16:00',
            lugar: 'Sala de Conferencias',
            tipo: 'comision',
            estado: 'completada',
            asistentes: 12,
            fechaCreacion: '2024-01-05'
        }
    ];
    
    mostrarReuniones();
}

// Hacer funciones disponibles globalmente para los event listeners en HTML
window.abrirModalDetalles = abrirModalDetalles;
window.abrirModalEditar = abrirModalEditar;
window.completarReunion = completarReunion;
window.eliminarReunion = eliminarReunion;
    </script>
</body>

</html>