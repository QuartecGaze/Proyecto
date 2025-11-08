// modalReuniones.js - Funcionalidad del modal para reuniones (sin confirmar asistencia)

document.addEventListener('DOMContentLoaded', function() {
    // Crear el modal dinámicamente si no existe
    if (!document.getElementById('modalReunion')) {
        crearModal();
    }
    
    inicializarModal();
    agregarEventosClickActividades();
});

function crearModal() {
    const modalHTML = `
        <div id="modalReunion" class="modal-reunion">
            <div class="modal-contenido">
                <div class="modal-header">
                    <h2 id="modalTitulo">Detalles de la Reunión</h2>
                    <span class="cerrar-modal">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="info-reunion">
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
                        <div class="info-item">
                            <i class="material-icons">assignment</i>
                            <div class="info-detalle">
                                <h3>Orden del Día</h3>
                                <ul id="modalOrdenDia" class="lista-orden-dia">
                                    <li>No se ha definido un orden del día</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="boton-modal boton-principal" id="botonCerrarModal">Cerrar</button>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

function inicializarModal() {
    const modal = document.getElementById('modalReunion');
    const botonCerrar = document.getElementById('botonCerrarModal');
    const cerrarModal = document.querySelector('.cerrar-modal');
    
    // Event listeners para cerrar el modal
    if (botonCerrar) {
        botonCerrar.addEventListener('click', cerrarModalReunion);
    }
    if (cerrarModal) {
        cerrarModal.addEventListener('click', cerrarModalReunion);
    }
    
    // Cerrar modal al hacer clic fuera del contenido
    if (modal) {
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                cerrarModalReunion();
            }
        });
    }
    
    // Cerrar con tecla ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            cerrarModalReunion();
        }
    });
}

function agregarEventosClickActividades() {
    // Usar event delegation para manejar clicks en actividades
    document.addEventListener('click', function(event) {
        const actividad = event.target.closest('.actividad');
        if (actividad) {
            // Obtener datos de la reunión desde los elementos HTML
            const titulo = actividad.querySelector('.actividad-detalle p')?.textContent || 'Reunión';
            const fechaHoraText = actividad.querySelector('.actividad-fecha')?.textContent || '';
            
            // Extraer fecha y hora del texto
            const fechaMatch = fechaHoraText.match(/El día (.+?) a las (.+?) en/);
            const lugarMatch = fechaHoraText.match(/en <strong>(.+)<\/strong>/);
            
            const fecha = fechaMatch ? fechaMatch[1] : 'Fecha no disponible';
            const hora = fechaMatch ? fechaMatch[2] : 'Hora no disponible';
            const lugar = lugarMatch ? lugarMatch[1] : 'Lugar no disponible';
            
            // Determinar si es reunión pendiente o terminada por el icono
            const icono = actividad.querySelector('.material-icons');
            const esTerminada = icono?.textContent === 'event_available';
            
            const reunion = {
                titulo: titulo,
                fecha: fecha,
                hora: hora,
                lugar: lugar,
                esTerminada: esTerminada,
                descripcion: esTerminada 
                    ? 'Esta reunión ya ha sido realizada. Puedes revisar los detalles y acuerdos tomados.'
                    : 'Reunión programada para el futuro.',
                ordenDia: [
                    'Revisión de avances del período anterior',
                    'Presentación de nuevos temas y proyectos',
                    'Distribución de tareas y responsabilidades',
                    'Análisis de presupuesto y recursos',
                    'Ronda de preguntas y comentarios',
                    'Definición de próximos pasos y fechas'
                ]
            };
            
            abrirModalReunion(reunion);
        }
    });
}

function abrirModalReunion(reunion) {
    const modal = document.getElementById('modalReunion');
    if (!modal) return;
    
    // Llenar el modal con los datos de la reunión
    document.getElementById('modalTitulo').textContent = reunion.titulo;
    document.getElementById('modalFechaHora').textContent = `El día ${reunion.fecha} a las ${reunion.hora}`;
    document.getElementById('modalUbicacion').textContent = reunion.lugar;
    document.getElementById('modalDescripcion').textContent = reunion.descripcion;
    
    // Actualizar orden del día
    const listaOrdenDia = document.getElementById('modalOrdenDia');
    if (listaOrdenDia) {
        listaOrdenDia.innerHTML = '';
        reunion.ordenDia.forEach(punto => {
            const li = document.createElement('li');
            li.textContent = punto;
            listaOrdenDia.appendChild(li);
        });
    }
    
    // Mostrar el modal
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function cerrarModalReunion() {
    const modal = document.getElementById('modalReunion');
    if (!modal) return;
    
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Observador para actividades cargadas dinámicamente
const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
        if (mutation.addedNodes.length) {
            // Las actividades ya se manejan con event delegation
            // No es necesario hacer nada adicional
        }
    });
});

// Iniciar observación cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    const contenedorTerminadas = document.getElementById('contenedorTerminadas');
    const contenedorPendientes = document.getElementById('contenedorPendientes');
    
    if (contenedorTerminadas) {
        observer.observe(contenedorTerminadas, { childList: true, subtree: true });
    }
    if (contenedorPendientes) {
        observer.observe(contenedorPendientes, { childList: true, subtree: true });
    }
});