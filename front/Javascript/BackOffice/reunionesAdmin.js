import { getReunionesTerminadas, getReunionesPendientes } from '../../../BackEnd/APIFetchs/APICooperativa.js';

// --- DOM principales (coinciden con tu maquetado) ---
const contenedorTerminadas   = document.getElementById('contenedorTerminadas');
const contenedorPendientes   = document.getElementById('contenedorPendientes');
const spanReunionesPendientes = document.getElementById('reunionesPendientes');

// --- DOM del modal (usa el HTML que pasaste modificado: tipo a la derecha; estado debajo) ---
const modalReunion = document.getElementById('modalReunion');
const btnCerrarModal = document.getElementById('botonCerrarModal');
const btnCerrarX = document.querySelector('#modalReunion .cerrar-modal');

const elTitulo = document.getElementById('modalTitulo');
const elTipo = document.getElementById('modalTipo');       // a la derecha del título
const elEstado = document.getElementById('modalEstado');     // debajo del título
const elId = document.getElementById('modalId');         // opcional
const elFechaHora = document.getElementById('modalFechaHora');
const elUbic = document.getElementById('modalUbicacion');
const elDesc = document.getElementById('modalDescripcion');

// --- Estado en memoria ---
let reunionesTerminadas = [];
let reunionesPendientes = [];

// --- Utils ---
const escapeAttr = (v) => String(v ?? '').replace(/"/g, '&quot;');

// --- Modal: abrir/cerrar (misma mecánica que tu modal de horas) ---
function abrirModalReunion({ idReunion, titulo, tipo, estado, fecha, hora, lugar, descripcion }) {
  if (!modalReunion) return;

  elTitulo.textContent    = titulo || 'Reunión';
  elTipo.textContent      = tipo   || '—';
  elEstado.textContent    = estado || '—';
  if (elId) elId.textContent = idReunion ?? '—';
  elFechaHora.textContent = `El día ${fecha || '—'} a las ${hora || '—'}`;
  elUbic.textContent      = lugar || '—';
  elDesc.textContent      = descripcion || '—';

  modalReunion.style.display = 'flex'; // igual que en tus modales (flex para centrar)
  document.body.style.overflow = 'hidden';
}

function cerrarModalReunion() {
  if (!modalReunion) return;
  modalReunion.style.display = 'none';
  document.body.style.overflow = 'auto';
}

// --- Cerrar modal (igual que el de horas) ---
btnCerrarX?.addEventListener('click', cerrarModalReunion);
btnCerrarModal?.addEventListener('click', cerrarModalReunion);
modalReunion?.addEventListener('click', (e) => { if (e.target === modalReunion) cerrarModalReunion(); });
document.addEventListener('keydown', (e) => { if (e.key === 'Escape') cerrarModalReunion(); });

// --- Render terminadas ---
function renderReunionesTerminadas(lista) {
  if (!contenedorTerminadas) return;
  contenedorTerminadas.innerHTML = '';

  if (!Array.isArray(lista) || !lista.length) {
    contenedorTerminadas.innerHTML = `<p class="estado-vacio">No hay reuniones finalizadas o canceladas.</p>`;
    return;
  }

  const frag = document.createDocumentFragment();
  for (const r of lista) {
    const wrapper = document.createElement('div');
    wrapper.innerHTML = `
      <div class="actividad"
           data-idreunion="${escapeAttr(r.idReunion)}"
           data-titulo="${escapeAttr(r.titulo)}"
           data-fecha="${escapeAttr(r.fecha)}"
           data-hora="${escapeAttr(r.hora)}"
           data-lugar="${escapeAttr(r.lugar)}"
           data-descripcion="${escapeAttr(r.descripcion)}"
           data-estado="${escapeAttr(r.estado)}"
           data-tipo="${escapeAttr(r.tipoDeReunion)}"
           data-terminada="true">
        <i class="material-icons actividad-icono">event_available</i>
        <div class="actividad-detalle">
          <p>${r.titulo}</p>
          <span class="actividad-fecha">El día ${r.fecha} a las ${r.hora} en <strong>${r.lugar}</strong></span>
        </div>
      </div>
    `;
    frag.appendChild(wrapper);
  }
  contenedorTerminadas.appendChild(frag);
}

// --- Render pendientes ---
function renderReunionesPendientes(lista, totalPendientes = 0) {
  if (!contenedorPendientes) return;

  if (spanReunionesPendientes) spanReunionesPendientes.textContent = Number(totalPendientes || 0);

  const listaTarget = contenedorPendientes.querySelector('.lista-eventos') || contenedorPendientes;
  listaTarget.innerHTML = '';

  if (!Array.isArray(lista) || !lista.length) {
    listaTarget.innerHTML = `<p class="estado-vacio">No hay reuniones pendientes.</p>`;
    return;
  }

  const frag = document.createDocumentFragment();
  for (const r of lista) {
    const wrapper = document.createElement('div');
    wrapper.innerHTML = `
      <div class="actividad"
           data-idreunion="${escapeAttr(r.idReunion)}"
           data-titulo="${escapeAttr(r.titulo)}"
           data-fecha="${escapeAttr(r.fecha)}"
           data-hora="${escapeAttr(r.hora)}"
           data-lugar="${escapeAttr(r.lugar)}"
           data-descripcion="${escapeAttr(r.descripcion)}"
           data-estado="${escapeAttr(r.estado)}"
           data-tipo="${escapeAttr(r.tipoDeReunion)}"
           data-terminada="false">
        <i class="material-icons actividad-icono">event</i>
        <div class="actividad-detalle">
          <p>${r.titulo}</p>
          <span class="actividad-fecha">El día ${r.fecha} a las ${r.hora} en <strong>${r.lugar}</strong></span>
        </div>
      </div>
    `;
    frag.appendChild(wrapper);
  }
  listaTarget.appendChild(frag);
}

// --- Delegación de clicks para abrir modal (como hacés con editar/eliminar) ---
function delegarClickReuniones() {
  // 1) Pendientes
  contenedorPendientes?.addEventListener('click', (e) => {
    const card = e.target.closest('.actividad');
    if (!card) return;

    abrirModalReunion({
      idReunion: card.dataset.idreunion || '—',
      titulo: card.dataset.titulo || 'Reunión',
      tipo: card.dataset.tipo || '—',
      estado: card.dataset.estado || 'Pendiente',
      fecha: card.dataset.fecha || '',
      hora:  card.dataset.hora  || '',
      lugar: card.dataset.lugar || '',
      descripcion: card.dataset.descripcion || ''
    });
  });

  // 2) Terminadas
  contenedorTerminadas?.addEventListener('click', (e) => {
    const card = e.target.closest('.actividad');
    if (!card) return;

    abrirModalReunion({
      idReunion: card.dataset.idreunion || '—',
      titulo: card.dataset.titulo || 'Reunión',
      tipo: card.dataset.tipo || '—',
      estado: card.dataset.estado || 'Finalizada',
      fecha: card.dataset.fecha || '',
      hora:  card.dataset.hora  || '',
      lugar: card.dataset.lugar || '',
      descripcion: card.dataset.descripcion || ''
    });
  });
}

// --- Carga inicial (estilo de tu otro archivo: top-level await) ---
try {
  if (contenedorTerminadas) {
    const respT = await getReunionesTerminadas();
    reunionesTerminadas = (respT?.status === 'exito' && Array.isArray(respT?.message?.reuniones))
      ? respT.message.reuniones : [];
    renderReunionesTerminadas(reunionesTerminadas);
  }

  if (contenedorPendientes) {
    const respP = await getReunionesPendientes();
    reunionesPendientes = (respP?.status === 'exito' && Array.isArray(respP?.message?.reuniones))
      ? respP.message.reuniones : [];
    const totalPend = respP?.message?.reunionesPendientes ?? reunionesPendientes.length;
    renderReunionesPendientes(reunionesPendientes, totalPend);
  }
} catch (err) {
  console.error('Error inicial en reuniones:', err);
  // Render vacío defensivo
  renderReunionesTerminadas([]);
  renderReunionesPendientes([], 0);
}

// --- Activar delegación (como en tu tabla de horas) ---
delegarClickReuniones();
