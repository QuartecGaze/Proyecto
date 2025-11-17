// ../Javascript/BackOffice/borrarAdmins.js
// type="module"

import { getAdmins, borrarAdmin } from '../../../BackEnd/APIFetchs/APIBackOffice.js';

// --- Referencias al DOM ---
const listaAdmins       = document.getElementById('listaAdmins');
const modalConfirmacion = document.getElementById('modalConfirmacion');
const textoConfirmacion = document.getElementById('textoConfirmacion');
const botonCancelar     = document.getElementById('botonCancelar');
const botonConfirmar    = document.getElementById('botonConfirmar');
const msgExito          = document.querySelector('.mensaje-exito');
const msgError          = document.querySelector('.mensaje-error');

let adminSeleccionado = null; // { idPersona, nombreCompleto, email }

// --- Utils simples ---
function mostrarMensaje(tipo, texto) {
    if (tipo === 'exito') {
        if (msgError) msgError.style.display = 'none';
        if (msgExito) {
            msgExito.textContent = texto || 'Administrador borrado correctamente.';
            msgExito.style.display = 'block';
        }
    } else {
        if (msgExito) msgExito.style.display = 'none';
        if (msgError) {
            msgError.textContent = texto || 'Error al borrar el administrador.';
            msgError.style.display = 'block';
        }
    }

    // Ocultar después de unos segundos (opcional)
    setTimeout(() => {
        if (msgExito) msgExito.style.display = 'none';
        if (msgError) msgError.style.display = 'none';
    }, 4000);
}

function mapearRol(nivelPermisos) {
    // Ajustá esto a cómo lo manejes en tu BD:
    // Ejemplos:
    //  - 'admin' / 'operador'
    //  - 1 = admin, 2 = operador
    let texto = 'Operador';
    let clase = 'operador';

    if (nivelPermisos === 'admin' || nivelPermisos === 1 || nivelPermisos === '1') {
        texto = 'Administrador';
        clase = 'admin';
    }

    return { texto, clase };
}

// --- Cargar lista de admins desde el backend ---
async function cargarAdmins() {
    try {
        const resp = await getAdmins();
        const admins = resp?.message || [];

        if (!Array.isArray(admins) || admins.length === 0) {
            listaAdmins.innerHTML = `
                <div class="sin-admins">
                    <p>No hay administradores u operadores para mostrar</p>
                </div>
            `;
            return;
        }

        listaAdmins.innerHTML = '';

        admins.forEach((admin) => {
            const nombreCompleto = `${admin.nombre ?? ''} ${admin.apellido ?? ''}`.trim();
            const email = admin.email ?? '';
            const { texto: rolTexto, clase: rolClass } = mapearRol(admin.nivelPermisos);

            const item = document.createElement('div');
            item.className = 'admin-item';

            item.innerHTML = `
                <div class="admin-info">
                    <div class="admin-nombre">${nombreCompleto}</div>
                    <div class="admin-detalles">
                        <span>${email}</span>
                        <span class="admin-rol ${rolClass}">${rolTexto}</span>
                    </div>
                </div>
                <button 
                    class="boton-borrar" 
                    data-id-persona="${admin.idPersona}" 
                    data-nombre="${nombreCompleto}" 
                    data-email="${email}">
                    <i class="material-icons">delete</i> Eliminar
                </button>
            `;

            listaAdmins.appendChild(item);
        });
    } catch (err) {
        console.error('Error al cargar admins:', err);
        listaAdmins.innerHTML = `
            <div class="sin-admins">
                <p>Error al cargar los administradores.</p>
            </div>
        `;
    }
}

// --- Modal ---
function abrirModalConfirmacion(datos) {
    adminSeleccionado = datos;
    if (!adminSeleccionado) return;

    const nombre = adminSeleccionado.nombreCompleto || '';
    const email  = adminSeleccionado.email || '';

    textoConfirmacion.textContent =
        `¿Estás seguro de que deseas eliminar a ${nombre} (${email}) del sistema? ` +
        `Esta acción no se puede deshacer.`;

    modalConfirmacion.style.display = 'flex';
}

function cerrarModalConfirmacion() {
    modalConfirmacion.style.display = 'none';
    adminSeleccionado = null;
}

// --- Lógica de borrado ---
async function confirmarBorrado() {
    if (!adminSeleccionado || !adminSeleccionado.idPersona) {
        cerrarModalConfirmacion();
        return;
    }

    try {
        // 👇 AHORA SÍ: mandamos { idPersona: ... }
        const resp = await borrarAdmin({
            idPersona: adminSeleccionado.idPersona
        });

        if (resp?.status === 'exito') {
            mostrarMensaje('exito', resp.message || 'Administrador borrado correctamente.');
            await cargarAdmins();
        } else {
            mostrarMensaje('error', resp?.message || 'No se pudo borrar el administrador.');
        }
    } catch (err) {
        console.error('Error al borrar admin:', err);
        mostrarMensaje('error', 'Error al borrar el administrador.');
    } finally {
        cerrarModalConfirmacion();
    }
}


// --- Eventos ---
function initEventos() {
    // Delegación para los botones de borrar
    listaAdmins.addEventListener('click', (e) => {
        const boton = e.target.closest('.boton-borrar');
        if (!boton) return;

        const idPersona = boton.getAttribute('data-id-persona');
        const nombre    = boton.getAttribute('data-nombre') || '';
        const email     = boton.getAttribute('data-email') || '';

        if (!idPersona) return;

        abrirModalConfirmacion({
            idPersona,
            nombreCompleto: nombre,
            email
        });
    });

    // Botones del modal
    botonCancelar.addEventListener('click', cerrarModalConfirmacion);
    botonConfirmar.addEventListener('click', confirmarBorrado);

    // Cerrar modal al hacer clic fuera del contenido
    modalConfirmacion.addEventListener('click', (e) => {
        if (e.target === modalConfirmacion) {
            cerrarModalConfirmacion();
        }
    });
}

// --- Init ---
document.addEventListener('DOMContentLoaded', async () => {
    initEventos();
    await cargarAdmins();
});
