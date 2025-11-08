import { getUsuario } from '../../../BackEnd/APIFetchs/APIUsuario.js';
import { getIdSesion } from '../../../BackEnd/APIFetchs/APIUsuario.js';
import { 
    getUnidadHabitacional, 
    editarUnidadHabitacional,
    getIntegrantesFamiliares,
    agregarIntegranteFamiliar,
    editarIntegranteFamiliar,
    eliminarIntegranteFamiliar
} from '../../../BackEnd/APIFetchs/APICooperativa.js';

// Elementos del DOM
const nombre = document.querySelectorAll(".nombreUsuario");
const foto = document.querySelectorAll(".fotoPerfil");
const fotoruta = "../../Recursos/FotosPerfil/";
const fotoUsuario = "usuario.webp";

// Elementos de la unidad
const numeroUnidad = document.getElementById("numeroUnidad");
const direccionUnidad = document.getElementById("direccionUnidad");
const manzanaUnidad = document.getElementById("manzanaUnidad");
const loteUnidad = document.getElementById("loteUnidad");
const estadoUnidad = document.getElementById("estadoUnidad");
const fechaAsignacion = document.getElementById("fechaAsignacion");

// Elementos de integrantes
const listaIntegrantes = document.getElementById("lista-integrantes");
const sinIntegrantes = document.getElementById("sin-integrantes");

// Botones
const btnEditarUnidad = document.getElementById("btnEditarUnidad");
const btnAgregarIntegrante = document.getElementById("btnAgregarIntegrante");

// Modales
const modalEditarUnidad = document.getElementById("modalEditarUnidad");
const modalIntegrante = document.getElementById("modalIntegrante");
const modalConfirmacion = document.getElementById("modalConfirmacion");

// Formularios
const formEditarUnidad = document.getElementById("formEditarUnidad");
const formIntegrante = document.getElementById("formIntegrante");

// Variables globales
let unidadActual = null;
let integrantesActuales = [];
let integranteEditando = null;

// Inicializar la página
document.addEventListener('DOMContentLoaded', async function() {
    await cargarDatosUsuario();
    await cargarDatosUnidad();
    await cargarIntegrantes();
    inicializarEventListeners();
});

// Cargar datos del usuario
async function cargarDatosUsuario() {
    try {
        const idUsuario = getIdSesion();
        if (!idUsuario) {
            console.error("No se pudo obtener el ID del usuario");
            return;
        }

        const usuario = await getUsuario(idUsuario);
        if (usuario) {
            nombre.forEach(elemento => {
                elemento.textContent = usuario.nombre + " " + usuario.apellido;
            });
            
            foto.forEach(elemento => {
                elemento.src = fotoruta + (usuario.foto || fotoUsuario);
                elemento.alt = "Foto de " + usuario.nombre;
            });
        }
    } catch (error) {
        console.error("Error al cargar datos del usuario:", error);
    }
}

// Cargar datos de la unidad
async function cargarDatosUnidad() {
    try {
        const idUsuario = getIdSesion();
        if (!idUsuario) return;

        unidadActual = await getUnidadHabitacional(idUsuario);
        
        if (unidadActual) {
            numeroUnidad.textContent = unidadActual.numero || "No asignado";
            direccionUnidad.textContent = unidadActual.direccion || "No especificada";
            manzanaUnidad.textContent = unidadActual.manzana || "No especificada";
            loteUnidad.textContent = unidadActual.lote || "No especificado";
            estadoUnidad.textContent = obtenerTextoEstado(unidadActual.estado);
            estadoUnidad.className = "valor estado " + obtenerClaseEstado(unidadActual.estado);
            fechaAsignacion.textContent = formatearFecha(unidadActual.fechaAsignacion) || "No asignada";
        } else {
            mostrarMensajeError("No se encontró información de la unidad habitacional");
        }
    } catch (error) {
        console.error("Error al cargar datos de la unidad:", error);
        mostrarMensajeError("Error al cargar los datos de la unidad");
    }
}

// Cargar integrantes familiares
async function cargarIntegrantes() {
    try {
        const idUsuario = getIdSesion();
        if (!idUsuario) return;

        integrantesActuales = await getIntegrantesFamiliares(idUsuario);
        
        if (integrantesActuales && integrantesActuales.length > 0) {
            mostrarIntegrantes(integrantesActuales);
            sinIntegrantes.style.display = "none";
        } else {
            listaIntegrantes.innerHTML = "";
            sinIntegrantes.style.display = "block";
        }
    } catch (error) {
        console.error("Error al cargar integrantes:", error);
        mostrarMensajeError("Error al cargar los integrantes familiares");
    }
}

// Mostrar integrantes en la tabla
function mostrarIntegrantes(integrantes) {
    listaIntegrantes.innerHTML = "";
    
    integrantes.forEach(integrante => {
        const fila = document.createElement("tr");
        
        fila.innerHTML = `
            <td>${integrante.nombre || ""}</td>
            <td>${integrante.apellido || ""}</td>
            <td>${integrante.dni || ""}</td>
            <td>${obtenerTextoParentesco(integrante.parentesco)}</td>
            <td>${formatearFecha(integrante.fechaNacimiento) || ""}</td>
            <td>
                <button class="boton-icono editar-integrante" data-id="${integrante.id}">
                    <i class="material-icons">edit</i>
                </button>
                <button class="boton-icono peligro eliminar-integrante" data-id="${integrante.id}">
                    <i class="material-icons">delete</i>
                </button>
            </td>
        `;
        
        listaIntegrantes.appendChild(fila);
    });
    
    // Agregar event listeners a los botones de editar y eliminar
    document.querySelectorAll('.editar-integrante').forEach(boton => {
        boton.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            editarIntegrante(id);
        });
    });
    
    document.querySelectorAll('.eliminar-integrante').forEach(boton => {
        boton.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            confirmarEliminacionIntegrante(id);
        });
    });
}

// Inicializar event listeners
function inicializarEventListeners() {
    // Botones de editar unidad
    btnEditarUnidad.addEventListener('click', abrirModalEditarUnidad);
    
    // Botones de cerrar modal
    document.getElementById('cerrarModalUnidad').addEventListener('click', cerrarModalEditarUnidad);
    document.getElementById('cerrarModalIntegrante').addEventListener('click', cerrarModalIntegrante);
    document.getElementById('cerrarModalConfirmacion').addEventListener('click', cerrarModalConfirmacion);
    
    // Botones de cancelar
    document.getElementById('cancelarEdicionUnidad').addEventListener('click', cerrarModalEditarUnidad);
    document.getElementById('cancelarIntegrante').addEventListener('click', cerrarModalIntegrante);
    document.getElementById('cancelarEliminacion').addEventListener('click', cerrarModalConfirmacion);
    
    // Botones de confirmación
    document.getElementById('confirmarEliminacion').addEventListener('click', eliminarIntegrante);
    
    // Formularios
    formEditarUnidad.addEventListener('submit', guardarUnidad);
    formIntegrante.addEventListener('submit', guardarIntegrante);
    
    // Botón agregar integrante
    btnAgregarIntegrante.addEventListener('click', abrirModalAgregarIntegrante);
    
    // Cerrar modales al hacer clic fuera
    window.addEventListener('click', function(event) {
        if (event.target === modalEditarUnidad) {
            cerrarModalEditarUnidad();
        }
        if (event.target === modalIntegrante) {
            cerrarModalIntegrante();
        }
        if (event.target === modalConfirmacion) {
            cerrarModalConfirmacion();
        }
    });
}

// Funciones para abrir modales
function abrirModalEditarUnidad() {
    if (!unidadActual) return;
    
    // Llenar el formulario con los datos actuales
    document.getElementById('direccionEditar').value = unidadActual.direccion || "";
    document.getElementById('manzanaEditar').value = unidadActual.manzana || "";
    document.getElementById('loteEditar').value = unidadActual.lote || "";
    
    // Mostrar modal
    modalEditarUnidad.style.display = 'flex';
}

function abrirModalAgregarIntegrante() {
    integranteEditando = null;
    document.getElementById('tituloModalIntegrante').textContent = "Agregar Integrante Familiar";
    formIntegrante.reset();
    modalIntegrante.style.display = 'flex';
}

function editarIntegrante(id) {
    const integrante = integrantesActuales.find(i => i.id == id);
    if (!integrante) return;
    
    integranteEditando = integrante;
    document.getElementById('tituloModalIntegrante').textContent = "Editar Integrante Familiar";
    
    // Llenar el formulario con los datos del integrante
    document.getElementById('nombreIntegrante').value = integrante.nombre || "";
    document.getElementById('apellidoIntegrante').value = integrante.apellido || "";
    document.getElementById('dniIntegrante').value = integrante.dni || "";
    document.getElementById('parentescoIntegrante').value = integrante.parentesco || "";
    document.getElementById('fechaNacimientoIntegrante').value = integrante.fechaNacimiento || "";
    
    modalIntegrante.style.display = 'flex';
}

function confirmarEliminacionIntegrante(id) {
    const integrante = integrantesActuales.find(i => i.id == id);
    if (!integrante) return;
    
    integranteEditando = integrante;
    document.getElementById('mensajeConfirmacion').textContent = 
        `¿Estás seguro de que deseas eliminar a ${integrante.nombre} ${integrante.apellido}?`;
    
    modalConfirmacion.style.display = 'flex';
}

// Funciones para cerrar modales
function cerrarModalEditarUnidad() {
    modalEditarUnidad.style.display = 'none';
    ocultarMensajesFormulario(formEditarUnidad);
}

function cerrarModalIntegrante() {
    modalIntegrante.style.display = 'none';
    integranteEditando = null;
    ocultarMensajesFormulario(formIntegrante);
}

function cerrarModalConfirmacion() {
    modalConfirmacion.style.display = 'none';
    integranteEditando = null;
}

// Funciones para guardar datos
async function guardarUnidad(event) {
    event.preventDefault();
    
    const idUsuario = getIdSesion();
    if (!idUsuario || !unidadActual) return;
    
    const datos = {
        direccion: document.getElementById('direccionEditar').value,
        manzana: document.getElementById('manzanaEditar').value,
        lote: document.getElementById('loteEditar').value
    };
    
    try {
        const resultado = await editarUnidadHabitacional(unidadActual.id, datos);
        
        if (resultado) {
            mostrarMensajeExito(formEditarUnidad, "Información actualizada correctamente");
            await cargarDatosUnidad();
            
            // Cerrar modal después de un tiempo
            setTimeout(() => {
                cerrarModalEditarUnidad();
            }, 1500);
        } else {
            mostrarMensajeError(formEditarUnidad, "Error al actualizar la información");
        }
    } catch (error) {
        console.error("Error al guardar unidad:", error);
        mostrarMensajeError(formEditarUnidad, "Error al actualizar la información");
    }
}

async function guardarIntegrante(event) {
    event.preventDefault();
    
    const idUsuario = getIdSesion();
    if (!idUsuario) return;
    
    const datos = {
        nombre: document.getElementById('nombreIntegrante').value,
        apellido: document.getElementById('apellidoIntegrante').value,
        dni: document.getElementById('dniIntegrante').value,
        parentesco: document.getElementById('parentescoIntegrante').value,
        fechaNacimiento: document.getElementById('fechaNacimientoIntegrante').value,
        unidadHabitacionalId: unidadActual.id
    };
    
    try {
        let resultado;
        
        if (integranteEditando) {
            // Editar integrante existente
            resultado = await editarIntegranteFamiliar(integranteEditando.id, datos);
        } else {
            // Agregar nuevo integrante
            resultado = await agregarIntegranteFamiliar(datos);
        }
        
        if (resultado) {
            mostrarMensajeExito(formIntegrante, 
                integranteEditando ? "Integrante actualizado correctamente" : "Integrante agregado correctamente");
            
            await cargarIntegrantes();
            
            // Cerrar modal después de un tiempo
            setTimeout(() => {
                cerrarModalIntegrante();
            }, 1500);
        } else {
            mostrarMensajeError(formIntegrante, 
                integranteEditando ? "Error al actualizar el integrante" : "Error al agregar el integrante");
        }
    } catch (error) {
        console.error("Error al guardar integrante:", error);
        mostrarMensajeError(formIntegrante, 
            integranteEditando ? "Error al actualizar el integrante" : "Error al agregar el integrante");
    }
}

async function eliminarIntegrante() {
    if (!integranteEditando) return;
    
    try {
        const resultado = await eliminarIntegranteFamiliar(integranteEditando.id);
        
        if (resultado) {
            await cargarIntegrantes();
            cerrarModalConfirmacion();
            mostrarMensajeTemporal("Integrante eliminado correctamente", "exito");
        } else {
            mostrarMensajeError(null, "Error al eliminar el integrante");
        }
    } catch (error) {
        console.error("Error al eliminar integrante:", error);
        mostrarMensajeError(null, "Error al eliminar el integrante");
    }
}

// Funciones auxiliares
function obtenerTextoEstado(estado) {
    const estados = {
        'asignada': 'Asignada',
        'pendiente': 'Pendiente',
        'bloqueada': 'Bloqueada'
    };
    
    return estados[estado] || estado || 'Desconocido';
}

function obtenerClaseEstado(estado) {
    const clases = {
        'asignada': 'asignada',
        'pendiente': 'pendiente',
        'bloqueada': 'bloqueada'
    };
    
    return clases[estado] || '';
}

function obtenerTextoParentesco(parentesco) {
    const parentescos = {
        'conyuge': 'Cónyuge',
        'hijo': 'Hijo/a',
        'padre': 'Padre',
        'madre': 'Madre',
        'hermano': 'Hermano/a',
        'abuelo': 'Abuelo/a',
        'otro': 'Otro'
    };
    
    return parentescos[parentesco] || parentesco || 'No especificado';
}

function formatearFecha(fecha) {
    if (!fecha) return '';
    
    try {
        const fechaObj = new Date(fecha);
        return fechaObj.toLocaleDateString('es-ES');
    } catch (error) {
        return fecha;
    }
}

function mostrarMensajeExito(formulario, mensaje) {
    const mensajeExito = formulario.querySelector('.mensaje-exito');
    const mensajeError = formulario.querySelector('.mensaje-error');
    
    mensajeError.style.display = 'none';
    mensajeExito.textContent = mensaje;
    mensajeExito.style.display = 'block';
}

function mostrarMensajeError(formulario, mensaje) {
    if (formulario) {
        const mensajeExito = formulario.querySelector('.mensaje-exito');
        const mensajeError = formulario.querySelector('.mensaje-error');
        
        mensajeExito.style.display = 'none';
        mensajeError.textContent = mensaje;
        mensajeError.style.display = 'block';
    } else {
        // Mostrar mensaje global si no hay formulario específico
        mostrarMensajeTemporal(mensaje, "error");
    }
}

function ocultarMensajesFormulario(formulario) {
    const mensajeExito = formulario.querySelector('.mensaje-exito');
    const mensajeError = formulario.querySelector('.mensaje-error');
    
    mensajeExito.style.display = 'none';
    mensajeError.style.display = 'none';
}

function mostrarMensajeTemporal(mensaje, tipo) {
    // Crear elemento de mensaje
    const mensajeElemento = document.createElement('div');
    mensajeElemento.textContent = mensaje;
    mensajeElemento.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 4px;
        color: white;
        font-weight: 500;
        z-index: 10000;
        max-width: 300px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        animation: slideIn 0.3s ease;
    `;
    
    if (tipo === "exito") {
        mensajeElemento.style.backgroundColor = '#27ae60';
    } else {
        mensajeElemento.style.backgroundColor = '#e74c3c';
    }
    
    document.body.appendChild(mensajeElemento);
    
    // Remover después de 3 segundos
    setTimeout(() => {
        mensajeElemento.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            document.body.removeChild(mensajeElemento);
        }, 300);
    }, 3000);
}

// Agregar estilos de animación para los mensajes temporales
const estilosAnimacion = document.createElement('style');
estilosAnimacion.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(estilosAnimacion);