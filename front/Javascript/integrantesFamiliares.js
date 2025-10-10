// ===== IMPORTS =====
import {
  ingresarIntegrantesFamiliares,
  getIntegrantesFamiliares,
  eliminarIntegranteFamiliar,
} from '../../BackEnd/APIFetchs/APICooperativa.js';
import { getIdSesion } from '../../BackEnd/APIFetchs/APIUsuario.js';

// ===== HELPERS / DOM =====
const $ = (sel, ctx = document) => ctx.querySelector(sel);
const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));

const selectCantidad = $('#cantidad-personas');
const tabsHeader = $('.tabs-header');
const tabsContent = $('.tabs-content');
const residentesContainer = $('.residentes-container'); // <-- ancla de sección completa
const btnGuardar = $('.btn-guardar');

if (tabsHeader) tabsHeader.style.display = 'none';
if (tabsContent) tabsContent.style.display = 'none';

// Contenedor para cargar personas (inputs)
let lista = $('#personas-list');
if (!lista) {
  lista = document.createElement('div');
  lista.id = 'personas-list';
  if (tabsContent) {
    tabsContent.innerHTML = '';
    tabsContent.appendChild(lista);
  }
}

// ====== PANEL DE LISTADO (separado del cargador, DEBAJO de toda la sección 3) ======
let panel = document.getElementById('panel-integrantes');
if (!panel) {
  panel = document.createElement('section');
  panel.id = 'panel-integrantes';
  panel.className = 'panel-integrantes';
  panel.innerHTML = `
        <div class="panel-integrantes__header">
            <div class="panel-integrantes__title">
                <i class="material-icons">groups</i>
                <h3>Integrantes Familiares Registrados</h3>
            </div>
            <span id="integrantes-count" class="panel-integrantes__badge">0</span>
        </div>

        <div class="panel-integrantes__body">
            <div id="integrantes-spinner" class="panel-integrantes__spinner">
                <div class="panel-integrantes__loader"></div>
                <span>Cargando integrantes...</span>
            </div>

            <div id="integrantes-empty" class="panel-integrantes__empty" style="display:none;">
                <i class="material-icons">group</i>
                <p>No hay integrantes familiares registrados</p>
                <small>Agregue integrantes usando el formulario superior</small>
            </div>

            <div id="integrantes-table-wrapper" class="panel-integrantes__table-wrapper" style="display:none;">
                <table id="integrantes-table" class="panel-integrantes__table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Cédula</th>
                            <th>Fecha Nacimiento</th>
                            <th>Género</th>
                            <th>Email</th>
                            <th class="panel-integrantes__actions">Borrar</th>
                        </tr>
                    </thead>
                    <tbody id="integrantes-tbody"></tbody>
                </table>
            </div>
        </div>
    `;

  if (residentesContainer && residentesContainer.parentElement) {
    residentesContainer.insertAdjacentElement('afterend', panel);
  } else if (tabsContent && tabsContent.parentElement) {
    tabsContent.insertAdjacentElement('afterend', panel);
  } else {
    document.body.appendChild(panel);
  }
}


const integrantesCount = $('#integrantes-count', panel);
const integrantesSpinner = $('#integrantes-spinner', panel);
const integrantesEmpty = $('#integrantes-empty', panel);
const integrantesTableWrapper = $('#integrantes-table-wrapper', panel);
const integrantesTbody = $('#integrantes-tbody', panel);

// ====== ESTADO ======
let idPersonaLog = null;
let integrantesGuardados = [];

// ====== CARGAR NUEVOS INTEGRANTES ======
function crearBloquePersona(idx) {
  const hoy = new Date().toISOString().slice(0, 10);
  const fechaMin = new Date();
  fechaMin.setFullYear(fechaMin.getFullYear() - 150);
  const anosAtras = fechaMin.toISOString().slice(0, 10);

  if (tabsContent) tabsContent.style.display = 'block';
  const contenedor = document.createElement('section');
  contenedor.className = 'persona-bloque card';
  contenedor.dataset.index = idx;

  contenedor.innerHTML = `
    <h3 class="persona-titulo">Persona ${idx}</h3>
    <div class="form-grid">
      <div class="form-group">
        <label>Nombre</label>
        <input id="nombre${idx}" type="text" required>
      </div>
      <div class="form-group">
        <label>Apellido</label>
        <input id="apellido${idx}" type="text" required>
      </div>
      <div class="form-group">
        <label>Cédula</label>
        <input id="ci${idx}" type="text" required pattern="^\\d{7,8}$" inputmode="numeric">
      </div>
      <div class="form-group">
        <label>Fecha de Nacimiento</label>
        <input id="fecha${idx}" type="date" min="${anosAtras}" max="${hoy}" required>
      </div>
      <div class="form-group">
        <label>Género</label>
        <select id="genero${idx}" required>
          <option value="">Seleccione...</option>
          <option value="Masculino">Masculino</option>
          <option value="Femenino">Femenino</option>
        </select>
      </div>
      <div class="form-group">
        <label>Email</label>
        <input id="email${idx}" type="email" required>
      </div>
    </div>
    <hr>
  `;
  return contenedor;
}

function renderCantidad(nro) {
  if (!lista) return;
  lista.innerHTML = '';
  const cantidad = Number(nro);
  for (let i = 1; i <= cantidad; i++) {
    lista.appendChild(crearBloquePersona(i));
  }
}

(function ensureZeroOption() {
  if (!selectCantidad) return;
  if (![...selectCantidad.options].some(o => o.value === '0')) {
    const opt = new Option('0', '0');
    selectCantidad.insertBefore(opt, selectCantidad.firstChild);
  }
})();

if (selectCantidad) {
  selectCantidad.addEventListener('change', e => {
    renderCantidad(e.target.value);
  });
  renderCantidad(selectCantidad.value);
}

// Guardar
if (btnGuardar) {
  btnGuardar.addEventListener('click', async (e) => {
    e.preventDefault();

    const bloques = $$('.persona-bloque', lista);
    const campos = $$('.persona-bloque input, .persona-bloque select', lista);
    for (const el of campos) {
      if (!el.checkValidity()) {
        el.reportValidity();
        el.focus();
        return;
      }
    }

    const personas = bloques.map(b => {
      const i = b.dataset.index;
      return {
        nombre: document.getElementById('nombre' + i).value.trim(),
        apellido: document.getElementById('apellido' + i).value.trim(),
        ci: document.getElementById('ci' + i).value.trim(),
        fechaNacimiento: document.getElementById('fecha' + i).value.trim(),
        genero: document.getElementById('genero' + i).value.trim(),
        email: document.getElementById('email' + i).value.trim()
      };
    });

    try {
      const resp = await ingresarIntegrantesFamiliares({ cantidadIntegrantes: personas.length, integrantes: personas });
      if (resp?.status === 'exito') {
        document.querySelector('.mensaje-exito').style.display = 'block';
        document.querySelector('.mensaje-error').style.display = 'none';
        await cargarListadoIntegrantes();
        renderCantidad(0);
        if (selectCantidad) selectCantidad.value = '0';
      } else {
        document.querySelector('.mensaje-exito').style.display = 'none';
        document.querySelector('.mensaje-error').style.display = 'block';
        document.querySelector('.mensaje-error').textContent = resp?.message || 'Error al registrar integrantes';
        await cargarListadoIntegrantes();
        renderCantidad(0);
        if (selectCantidad) selectCantidad.value = '0';
      }
    } catch (err) {
      console.error('Error al enviar integrantes', err);
      alert('Error del servidor');
    }
  });
}

// ====== LISTADO (mostrar todos) ======
(async function initListado() {
  try {
    const ses = await getIdSesion();
    idPersonaLog = ses?.message;
    await cargarListadoIntegrantes();
    wireDelete(); // activar eventos de borrar
  } catch (e) {
    console.error('initListado error:', e);
  }
})();

async function cargarListadoIntegrantes() {
  try {
    toggleSpinner(true);
    const resp = await getIntegrantesFamiliares(idPersonaLog);
    const arr = Array.isArray(resp?.message) ? resp.message
      : (Array.isArray(resp?.message?.data) ? resp.message.data : []);
    integrantesGuardados = normalizarIntegrantes(arr);
    renderTablaIntegrantes();
  } catch (e) {
    console.error('getIntegrantesFamiliares falló:', e);
    integrantesGuardados = [];
    renderTablaIntegrantes();
  } finally {
    toggleSpinner(false);
  }
}

function normalizarIntegrantes(arr) {
  return (arr || []).map(f => ({
    id: f.id ?? f.ID_Integrante ?? '',
    nombre: f.nombre ?? f.Nombre ?? '',
    apellido: f.apellido ?? f.Apellido ?? '',
    ci: f.ci ?? f.CI ?? '',
    fechaNacimiento: f.fecha_nacimiento ?? f.FechaNacimiento ?? '',
    genero: f.genero ?? f.Genero ?? '',
    email: f.email ?? f.Email ?? ''
  }));
}

function renderTablaIntegrantes() {
  integrantesCount.textContent = String(integrantesGuardados.length);

  if (integrantesGuardados.length === 0) {
    integrantesTableWrapper.style.display = 'none';
    integrantesEmpty.style.display = 'flex';
    integrantesTbody.innerHTML = '';
    return;
  }

  integrantesEmpty.style.display = 'none';
  integrantesTableWrapper.style.display = 'block';

  integrantesTbody.innerHTML = integrantesGuardados.map((integrante, index) => `
        <tr data-id="${esc(integrante.id)}">
            <td data-label="Nombre">${esc(integrante.nombre)}</td>
            <td data-label="Apellido">${esc(integrante.apellido)}</td>
            <td data-label="Cédula">${esc(integrante.ci)}</td>
            <td data-label="Fecha Nac.">${esc(formatDate(integrante.fechaNacimiento))}</td>
            <td data-label="Género">${esc(integrante.genero)}</td>
            <td data-label="Email">${esc(integrante.email)}</td>
            <td class="panel-integrantes__actions" data-label="Acciones">
                <button type="button" class="btn btn--danger btn--sm btn-delete-integrante" 
                        data-id="${esc(integrante.id)}" title="Eliminar integrante">
                    <i class="material-icons">delete</i>
                </button>
            </td>
        </tr>
    `).join('');
}

// === Borrar ===
function wireDelete() {
  if (!integrantesTbody) return;
  integrantesTbody.addEventListener('click', async (ev) => {
    const btn = ev.target.closest('.btn-delete-integrante');
    if (!btn) return;
    const id = btn.dataset.id || btn.closest('tr')?.dataset?.id;
    if (!id) return;

    const ok = confirm('¿Seguro que deseas eliminar este integrante?');
    if (!ok) return;

    try {
      const resp = await eliminarIntegranteFamiliar(id); // tu API ya espera POST form-urlencoded
      if (resp?.status === 'exito') {
        integrantesGuardados = integrantesGuardados.filter(it => String(it.id) !== String(id));
        renderTablaIntegrantes();
      } else {
        alert('No se pudo eliminar: ' + (resp?.message || 'Error'));
      }
    } catch (e) {
      console.error('Eliminar integrante falló:', e);
      alert('Error del servidor al eliminar.');
    }
  });
}

function toggleSpinner(show) {
  integrantesSpinner.style.display = show ? 'flex' : 'none';
  if (show) {
    panel.classList.add('is-loading');
  } else {
    panel.classList.remove('is-loading');
  }
}

function formatDate(d) {
  if (!d) return '';
  const iso = d.includes('T') ? d.split('T')[0] : d;
  const [y, m, dd] = iso.split('-');
  if (!y || !m || !dd) return d;
  return `${dd}/${m}/${y}`;
}

function esc(s) {
  return String(s ?? '').replace(/[&<>"']/g, m => (
    { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m]
  ));
}
