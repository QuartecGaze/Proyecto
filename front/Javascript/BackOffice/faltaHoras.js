import { getFaltasPendientes } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { aprobarFalta } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { rechazarFalta } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { asignarMontoFalta } from '../../../BackEnd/APIFetchs/APIBackOffice.js';

const contenedor = document.querySelector('.contenedor-faltas');
const filtroEstado = document.getElementById('filtro-estado');
const filtroTipo = document.getElementById('filtro-tipo');
const btnAplicar = document.getElementById('btn-aplicar-filtros');
const btnConfirmarPago = document.querySelector('.btn-confirmar-pago');
const btnCancelarPago = document.querySelector('.btn-cancelar-pago');

let idFaltaSeleccionada = null;

function safe(v, fb = '—') {
  return (v !== null && v !== undefined && v !== '') ? v : fb;
}
function fmtFecha(iso) {
  if (!iso) return '—';
  const d = new Date((iso + '').slice(0, 10) + 'T00:00:00');
  if (isNaN(d.getTime())) return iso;
  return d.toLocaleDateString('es-ES', { day: '2-digit', month: 'short', year: 'numeric' });
}
function tipoToAttr(t) {
  const v = (t || '').toLowerCase();
  if (v.includes('pago') || v.includes('monet')) return 'monetaria';
  return 'horas';
}

// Cerrar modal de pago
btnCancelarPago.addEventListener('click', function () {
  document.getElementById('modalCompensacion').style.display = 'none';
  idFaltaSeleccionada = null;
  delete btnConfirmarPago.dataset.id;
});

// Confirmar pago (registrado UNA VEZ)
btnConfirmarPago.addEventListener('click', async function () {
  const modal = document.getElementById('modalCompensacion');
  const monto = document.getElementById('montoCompensacion').value;

  const idFalta = btnConfirmarPago.dataset.id || idFaltaSeleccionada; // cualquiera de los dos
  if (!idFalta) {
    alert('No se pudo identificar la falta.');
    return;
  }
  if (!monto || Number(monto) <= 0) {
    alert('Por favor ingrese un monto válido');
    return;
  }

  try {
    const datos = { idFalta, monto };
    const respuesta = await asignarMontoFalta(datos);

    if (respuesta?.status === 'exito') {
      alert('Pago asignado correctamente.');

      // Actualizar tarjeta en UI
      const tarjeta = contenedor.querySelector(`[data-id="${idFalta}"]`);
      if (tarjeta) {
        tarjeta.setAttribute('data-tipo', 'monetaria');
        const montoEl = tarjeta.querySelector('.monto-asignado');
        if (montoEl) {
          montoEl.style.display = 'flex';
          const val = montoEl.querySelector('.valor');
          if (val) val.textContent = `$${parseFloat(monto).toFixed(2)}`;
        }
      }
    } else {
      alert('Error: ' + (respuesta?.message ?? 'desconocido'));
    }
  } catch (error) {
    console.error('Error al asignar pago ', error);
    alert('Error del servidor');
  } finally {
    modal.style.display = 'none';
    idFaltaSeleccionada = null;
    delete btnConfirmarPago.dataset.id;
  }
});

function buildCard(f) {
  const fotoPath = f.foto ? `../../Recursos/FotosPerfil/${f.foto}` : '../../Recursos/FotosPerfil/usuario.webp';
  const tipoAttr = tipoToAttr(f.tipoCompensacion);

  const div = document.createElement('div');
  div.className = 'tarjeta-falta';
  div.setAttribute('data-id', String(f.idFalta));
  div.setAttribute('data-estado', 'pendiente');
  div.setAttribute('data-tipo', tipoAttr);

  div.innerHTML = `
    <div class="info-socio">
      <div class="foto-socio">
        <img src="${fotoPath}" alt="Foto socio" id="ftoSocio">
      </div>
      <div class="datos-socio">
        <h3>${safe(f.nombre)} ${safe(f.apellido)}</h3>
        <p>CI: ${safe(f.cedula)}</p>
        ${f.pasillo ? `<p>Pasillo: ${f.pasillo}</p>` : ''}
        ${f.nroPuerta ? `<p>Puerta: ${f.nroPuerta}</p>` : ''}
      </div>
    </div>

    <div class="detalles-falta">
      <div class="dato-falta">
        <span class="etiqueta">Horas faltantes:</span>
        <span class="valor">${safe(f.horasExonerar, 0)} horas</span>
      </div>
      <div class="dato-falta">
        <span class="etiqueta">Fecha de falta:</span>
        <span class="valor">${fmtFecha(f.fecha)}</span>
      </div>
      <div class="dato-falta">
        <span class="etiqueta">Motivo:</span>
        <span class="valor">${safe(f.motivo)}</span>
      </div>
      <div class="dato-falta">
        <span class="etiqueta">Tipo de compensación:</span>
        <span class="valor">${safe(f.tipoCompensacion)}</span>
      </div>
      <div class="dato-falta monto-asignado" style="display:${tipoAttr === 'monetaria' ? 'flex' : 'none'}">
        <span class="etiqueta">Monto asignado:</span>
        <span class="valor">$0.00</span>
      </div>
    </div>

    <div class="acciones-falta">
      <button class="btn-aprobar" data-action="aprobar" data-id="${f.idFalta}" data-tipo="${tipoAttr}">
        <i class="material-icons">check_circle</i> Aprobar
      </button>
      <button class="btn-compensar" data-action="compensar" data-id="${f.idFalta}" style="display:${tipoAttr === 'monetaria' ? 'flex' : 'none'}">
        <i class="material-icons">attach_money</i> Asignar monto
      </button>
      <button class="btn-rechazar" data-action="rechazar" data-id="${f.idFalta}">
        <i class="material-icons">cancel</i> Rechazar
      </button>
    </div>
  `;
  return div;
}

function renderLista(data) {
  contenedor.innerHTML = '';
  if (!Array.isArray(data) || !data.length) {
    contenedor.innerHTML = `
      <div class="estado-vacio">
        <i class="material-icons">inbox</i>
        <p>No hay faltas pendientes.</p>
      </div>`;
    return;
  }
  data.forEach(f => contenedor.appendChild(buildCard(f)));
}

function aplicarFiltrosUI() {
  const estadoSel = (filtroEstado?.value || 'todas').toLowerCase();
  const tipoSel = (filtroTipo?.value || 'todos').toLowerCase();

  contenedor.querySelectorAll('.tarjeta-falta').forEach(tarjeta => {
    const estado = (tarjeta.getAttribute('data-estado') || '').toLowerCase();
    const tipo = (tarjeta.getAttribute('data-tipo') || '').toLowerCase();
    const visible =
      (estadoSel === 'todas' || estado === estadoSel) &&
      (tipoSel === 'todos' || tipo === tipoSel);
    tarjeta.style.display = visible ? 'flex' : 'none';
  });
}

contenedor.addEventListener('click', async (e) => {
  const btn = e.target.closest('button[data-action]');
  if (!btn) return;

  const id = btn.dataset.id;
  const action = btn.dataset.action;
  const tipo = btn.dataset.tipo;

  if (action === 'compensar') {
    idFaltaSeleccionada = id;
    btnConfirmarPago.dataset.id = id;
    if (typeof window.mostrarModalCompensacion === 'function') {
      window.mostrarModalCompensacion(id);
    } else {
      document.getElementById('modalCompensacion').style.display = 'flex';
    }
    return;
  }

  if (action === 'aprobar') {
    try {
      const respuesta = await aprobarFalta({ idFalta: id });
      if (respuesta?.status === "exito") {
        alert("Falta aprobada con éxito.");
        const tarjeta = contenedor.querySelector(`[data-id="${id}"]`);
        if (tarjeta) {
          tarjeta.setAttribute('data-estado', 'aprobada');
          tarjeta.style.opacity = '0.7';
        }
      } else {
        alert("Error " + (respuesta?.message ?? 'desconocido'));
      }
    } catch (error) {
      console.error("Error al aprobar la falta", error);
      alert("Error del servidor");
    }
    return;
  }

  if (action === 'rechazar') {
    try {
      const respuesta = await rechazarFalta({ idFalta: id });
      if (respuesta?.status === "exito") {
        alert("Falta rechazada con éxito.");
        const tarjeta = contenedor.querySelector(`[data-id="${id}"]`);
        if (tarjeta) tarjeta.remove();
      } else {
        alert("Error " + (respuesta?.message ?? 'desconocido'));
      }
    } catch (error) {
      console.error("Error al rechazar la falta", error);
      alert("Error del servidor");
    }
    return;
  }
});

(async function init() {
  try {
    const res = await getFaltasPendientes();
    const payload = res?.message || res?.data || res || {};
    const faltas = Array.isArray(payload.faltas) ? payload.faltas
      : Array.isArray(payload) ? payload
      : [];
    renderLista(faltas);
    btnAplicar?.addEventListener('click', aplicarFiltrosUI);
  } catch (err) {
    console.error('Error al cargar faltas pendientes', err);
    contenedor.innerHTML = `
      <div class="estado-error">
        <i class="material-icons">error</i>
        <p>No se pudieron cargar las faltas pendientes.</p>
      </div>`;
  }
})();
