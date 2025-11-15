import { getFaltasPendientes } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { aprobarFalta } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { rechazarFalta } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { asignarMontoFalta } from '../../../BackEnd/APIFetchs/APIBackOffice.js';

const contenedor       = document.querySelector('.contenedor-faltas');
const filtroEstado     = document.getElementById('filtro-estado');
const filtroTipo       = document.getElementById('filtro-tipo');
const btnAplicar       = document.getElementById('btn-aplicar-filtros');
const btnConfirmarPago = document.querySelector('.btn-confirmar-pago');
const btnCancelarPago  = document.querySelector('.btn-cancelar-pago');
const modalComp        = document.getElementById('modalCompensacion');
const inputMonto       = document.getElementById('montoCompensacion');

let idFaltaSeleccionada = null;

// ---------- Modal compensación ----------
if (btnCancelarPago) {
  btnCancelarPago.addEventListener('click', () => {
    modalComp.style.display = 'none';
    idFaltaSeleccionada = null;
    if (btnConfirmarPago) {
      delete btnConfirmarPago.dataset.id;
    }
    if (inputMonto) inputMonto.value = '';
  });
}

if (btnConfirmarPago) {
  btnConfirmarPago.addEventListener('click', async () => {
    const monto = Number(inputMonto.value);
    const idFalta = btnConfirmarPago.dataset.id;

    if (!idFalta) {
      await Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'No se pudo identificar la falta.',
        confirmButtonText: 'Aceptar'
      });
      return;
    }

    if (!monto || monto <= 0) {
      await Swal.fire({
        icon: 'warning',
        title: 'Monto inválido',
        text: 'Ingresá un monto válido mayor a 0.',
        confirmButtonText: 'Aceptar'
      });
      return;
    }

    try {
      const resp = await asignarMontoFalta({ idFalta, monto });

      if (resp?.status === 'exito') {
        await Swal.fire({
          icon: 'success',
          title: 'Monto asignado',
          text: `Se asignó un pago de $ ${monto.toFixed(2)} a la falta seleccionada.`,
          confirmButtonText: 'Aceptar'
        });

        // Actualizar tarjeta en la UI
        const tarjeta = contenedor.querySelector(`[data-id="${idFalta}"]`);
        if (tarjeta) {
          tarjeta.setAttribute('data-tipo', 'monetaria');
          const montoEl = tarjeta.querySelector('.monto-asignado');
          if (montoEl) {
            montoEl.style.display = 'flex';
            const val = montoEl.querySelector('.valor');
            if (val) val.textContent = `$ ${monto.toFixed(2)}`;
          }
        }
      } else {
        await Swal.fire({
          icon: 'error',
          title: 'Error',
          text: resp?.message ?? 'No se pudo asignar el monto.',
          confirmButtonText: 'Aceptar'
        });
      }
    } catch (err) {
      console.error('Error al asignar pago', err);
      await Swal.fire({
        icon: 'error',
        title: 'Error del servidor',
        text: 'Ocurrió un error al asignar el pago.',
        confirmButtonText: 'Aceptar'
      });
    } finally {
      modalComp.style.display = 'none';
      idFaltaSeleccionada = null;
      delete btnConfirmarPago.dataset.id;
      inputMonto.value = '';
    }
  });
}

// ---------- Tarjetas ----------
function buildCard(f) {
  const fotoPath = f.foto
    ? `../../Recursos/FotosPerfil/${f.foto}`
    : '../../Recursos/FotosPerfil/usuario.webp';

  const textoTipo = (f.tipoCompensacion || '').toLowerCase();
  const tipoAttr = (textoTipo.includes('pago') || textoTipo.includes('monet'))
    ? 'monetaria'
    : 'horas';

  const horasExo   = f.horasExonerar ?? 0;
  const cedula     = f.cedula ?? '—';
  const motivo     = f.motivo ?? '—';
  const tipoTexto  = f.tipoCompensacion ?? '—';
  const montoPago  = f.montoPago != null
    ? `${f.montoPago}$`
    : '—';

  let fechaFmt = '—';
  if (f.fecha) {
    const d = new Date((f.fecha + '').slice(0, 10) + 'T00:00:00');
    fechaFmt = isNaN(d.getTime())
      ? f.fecha
      : d.toLocaleDateString('es-ES', { day: '2-digit', month: 'short', year: 'numeric' });
  }

  const div = document.createElement('div');
  div.className = 'tarjeta-falta';
  div.dataset.id = String(f.idFalta);
  div.dataset.estado = 'pendiente';
  div.dataset.tipo = tipoAttr;

  div.innerHTML = `
    <div class="info-socio">
      <div class="foto-socio">
        <img src="${fotoPath}" alt="Foto socio">
      </div>
      <div class="datos-socio">
        <h3>${f.nombre} ${f.apellido}</h3>
        <p>CI: ${cedula}</p>
        ${f.pasillo   ? `<p>Pasillo: ${f.pasillo}</p>`   : ''}
        ${f.nroPuerta ? `<p>Puerta: ${f.nroPuerta}</p>` : ''}
      </div>
    </div>

    <div class="detalles-falta">
      <div class="dato-falta">
        <span class="etiqueta">Horas faltantes:</span>
        <span class="valor">${horasExo} horas</span>
      </div>
      <div class="dato-falta">
        <span class="etiqueta">Fecha de falta:</span>
        <span class="valor">${fechaFmt}</span>
      </div>
      <div class="dato-falta">
        <span class="etiqueta">Motivo:</span>
        <span class="valor">${motivo}</span>
      </div>
      <div class="dato-falta">
        <span class="etiqueta">Tipo de compensación:</span>
        <span class="valor">${tipoTexto}</span>
      </div>
      <div class="dato-falta monto-asignado" style="display:${tipoAttr === 'monetaria' ? 'flex' : 'none'}">
        <span class="etiqueta">Monto asignado:</span>
        <span class="valor">${montoPago}</span>
      </div>
    </div>

    <div class="acciones-falta">
      <button class="btn-aprobar" data-action="aprobar" data-id="${f.idFalta}">
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

function renderLista(faltas) {
  contenedor.innerHTML = '';
  if (!faltas.length) {
    contenedor.innerHTML = `
      <div class="estado-vacio" id="estadoVacio" style="display:flex;">
        <i class="material-icons" style="font-size: 68px;">inbox</i>
        <h1>No hay faltas pendientes.</h1>
      </div>`;
    return;
  }
  faltas.forEach(f => contenedor.appendChild(buildCard(f)));
}

// ---------- Filtros ----------
function aplicarFiltrosUI() {
  const estadoSel = (filtroEstado?.value || '').toLowerCase();
  const tipoSel   = (filtroTipo?.value || '').toLowerCase();

  contenedor.querySelectorAll('.tarjeta-falta').forEach(tarjeta => {
    const estado = (tarjeta.dataset.estado || '').toLowerCase();
    const tipo   = (tarjeta.dataset.tipo || '').toLowerCase();

    const visible =
      (estadoSel === 'todas' || estado === estadoSel) &&
      (tipoSel   === 'todos'  || tipo   === tipoSel);

    tarjeta.style.display = visible ? 'block' : 'none';
  });
}

// ---------- Acciones de las tarjetas ----------
contenedor.addEventListener('click', async (e) => {
  const btn = e.target.closest('button[data-action]');
  if (!btn) return;

  const id     = btn.dataset.id;
  const action = btn.dataset.action;

  if (action === 'compensar') {
    idFaltaSeleccionada = id;
    if (btnConfirmarPago) btnConfirmarPago.dataset.id = id;
    if (modalComp) modalComp.style.display = 'flex';
    return;
  }

  if (action === 'aprobar') {
    const result = await Swal.fire({
      icon: 'question',
      title: '¿Aprobar falta?',
      text: 'Esta acción aprobará la falta seleccionada.',
      showCancelButton: true,
      confirmButtonText: 'Sí, aprobar',
      cancelButtonText: 'Cancelar'
    });

    if (!result.isConfirmed) return;

    try {
      const resp = await aprobarFalta({ idFalta: id });
      if (resp?.status === 'exito') {
        await Swal.fire({
          icon: 'success',
          title: 'Falta aprobada',
          text: 'La falta fue aprobada correctamente.',
          confirmButtonText: 'Aceptar'
        });

        const tarjeta = contenedor.querySelector(`[data-id="${id}"]`);
        if (tarjeta) {
          tarjeta.dataset.estado = 'aprobada';
          tarjeta.style.opacity = '0.7';
        }
        aplicarFiltrosUI();
      } else {
        await Swal.fire({
          icon: 'error',
          title: 'Error',
          text: resp?.message ?? 'No se pudo aprobar la falta.',
          confirmButtonText: 'Aceptar'
        });
      }
    } catch (err) {
      console.error('Error al aprobar la falta', err);
      await Swal.fire({
        icon: 'error',
        title: 'Error del servidor',
        text: 'Ocurrió un error al aprobar la falta.',
        confirmButtonText: 'Aceptar'
      });
    }
    return;
  }

  if (action === 'rechazar') {
    const result = await Swal.fire({
      icon: 'warning',
      title: '¿Rechazar falta?',
      text: 'Esta acción rechazará la falta y no se podrá deshacer.',
      showCancelButton: true,
      confirmButtonText: 'Sí, rechazar',
      cancelButtonText: 'Cancelar'
    });

    if (!result.isConfirmed) return;

    try {
      const resp = await rechazarFalta({ idFalta: id });
      if (resp?.status === 'exito') {
        await Swal.fire({
          icon: 'success',
          title: 'Falta rechazada',
          text: 'La falta fue rechazada correctamente.',
          confirmButtonText: 'Aceptar'
        });

        const tarjeta = contenedor.querySelector(`[data-id="${id}"]`);
        if (tarjeta) tarjeta.remove();
      } else {
        await Swal.fire({
          icon: 'error',
          title: 'Error',
          text: resp?.message ?? 'No se pudo rechazar la falta.',
          confirmButtonText: 'Aceptar'
        });
      }
    } catch (err) {
      console.error('Error al rechazar la falta', err);
      await Swal.fire({
        icon: 'error',
        title: 'Error del servidor',
        text: 'Ocurrió un error al rechazar la falta.',
        confirmButtonText: 'Aceptar'
      });
    }
  }
});

// ---------- Init ----------
(async function init() {
  try {
    const res = await getFaltasPendientes();
    const faltas = res?.message?.faltas ?? [];
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
